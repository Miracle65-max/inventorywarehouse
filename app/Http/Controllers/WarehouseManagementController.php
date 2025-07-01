<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class WarehouseManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'user') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Orders to process (processing and cancelled orders for warehouse users)
        $orders = SalesOrder::withCount('orderItems')
            ->whereIn('status', ['processing', 'cancelled'])
            ->orderBy('order_date', 'asc')
            ->get();

        // Storage locations
        $locations = Item::whereNotNull('location')
            ->where('location', '!=', '')
            ->select('location', DB::raw('COUNT(*) as item_count'), DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('location')
            ->orderBy('location')
            ->get();

        // Low stock items
        $low_stock_items = Item::where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();

        return view('warehouse_management.index', [
            'orders' => $orders,
            'locations' => $locations,
            'low_stock_items' => $low_stock_items,
        ]);
    }

    public function completeOrder(Request $request, SalesOrder $order)
    {
        $userId = auth()->id();

        // Check if all items are available
        $can_complete = true;
        foreach ($order->orderItems as $item) {
            if ($item->item->quantity < $item->quantity) {
                $can_complete = false;
                break;
            }
        }

        if (!$can_complete) {
            return redirect()->back()->with('error', 'Cannot complete order. Insufficient stock for some items.');
        }

        \DB::beginTransaction();
        try {
            // Reduce stock for each item
            foreach ($order->orderItems as $item) {
                $itemModel = $item->item;
                $new_quantity = $itemModel->quantity - $item->quantity;
                $itemModel->quantity = $new_quantity;
                $itemModel->save();

                // Record stock movement
                \App\Models\StockMovement::create([
                    'item_id' => $itemModel->item_id,
                    'movement_type' => 'outbound',
                    'quantity_changed' => $item->quantity,
                    'new_total_quantity' => $new_quantity,
                    'user_id' => $userId,
                    'remarks' => 'Order completion - Order ID: ' . $order->order_id,
                ]);
            }

            // Update order status
            $order->status = 'completed';
            $order->save();

            \DB::commit();
            return redirect()->back()->with('success', 'Order completed successfully. Stock levels updated.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Error completing order: ' . $e->getMessage());
        }
    }
} 