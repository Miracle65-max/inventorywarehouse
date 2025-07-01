<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AuditTrail;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = SalesOrder::with('user');

        // Filtering by status if provided
        if (in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        // Custom ordering: pending & processing at top, completed/cancelled at bottom
        $query->orderByRaw(
            "CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'processing' THEN 2 
                WHEN status = 'completed' THEN 3 
                WHEN status = 'cancelled' THEN 4 
                ELSE 5 END ASC"
        )->orderByDesc('created_at');

        $orders = $query->paginate(20)->appends($request->all());
        return view('sales_orders.index', compact('orders', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('quantity', '>', 0)
            ->where('item_name', 'not like', '[DELETED]%')
            ->orderBy('item_name')
            ->get();
        return view('sales_orders.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s\.\-\']+$/',
            'customer_email' => 'nullable|email|max:100',
            'customer_phone' => 'nullable|string|max:20|regex:/^[\+\d\s\-\(\)]+$/',
            'customer_address' => 'nullable|string|max:500',
            'pickup_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,item_id',
            'items.*.quantity' => 'required|integer|min:1|max:999999',
            'items.*.unit_price' => 'required|numeric|min:0.01|max:999999.99',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.regex' => 'Customer name can only contain letters, spaces, hyphens, and apostrophes.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_phone.regex' => 'Please enter a valid phone number.',
            'pickup_date.required' => 'Pickup/delivery date is required.',
            'pickup_date.after_or_equal' => 'Pickup/delivery date must be today or a future date.',
            'items.required' => 'At least one item must be selected for the order.',
            'items.min' => 'At least one item must be selected for the order.',
            'items.*.item_id.required' => 'Please select an item.',
            'items.*.item_id.exists' => 'The selected item is not available in inventory.',
            'items.*.quantity.required' => 'Quantity is required.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.min' => 'Unit price must be greater than 0.',
        ]);

        DB::beginTransaction();
        try {
            // Generate unique order number
            do {
                $order_number = 'ORD-' . date('Y') . date('m') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            } while (SalesOrder::where('order_number', $order_number)->exists());

            $total_order_amount = 0;
            $orderItems = [];
            foreach ($request->items as $itemData) {
                $item = Item::where('item_id', $itemData['item_id'])
                    ->where('item_name', 'not like', '[DELETED]%')
                    ->firstOrFail();
                if ($item->quantity < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for item: {$item->item_name}");
                }
                $total_price = $itemData['quantity'] * $itemData['unit_price'];
                $total_order_amount += $total_price;
                $orderItems[] = [
                    'item_id' => $item->item_id,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'total_price' => $total_price
                ];
            }

            $order = SalesOrder::create([
                'order_number' => $order_number,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'order_date' => now()->toDateString(),
                'pickup_date' => $request->pickup_date,
                'total_amount' => $total_order_amount,
                'notes' => $request->notes,
                'status' => 'pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($orderItems as $orderItem) {
                $item = Item::find($orderItem['item_id']);
                $item->quantity -= $orderItem['quantity'];
                $item->save();
                $order->orderItems()->create($orderItem);
            }

            // Audit trail log
            AuditTrail::create([
                'user_id' => Auth::id(),
                'module' => 'Sales Orders',
                'action' => 'Created sales order: ' . $order->order_number,
                'details' => [
                    'order_id' => $order->order_id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'items' => array_map(function($oi) { return [
                        'item_id' => $oi['item_id'],
                        'quantity' => $oi['quantity'],
                        'unit_price' => $oi['unit_price'],
                        'total_price' => $oi['total_price'],
                    ]; }, $orderItems),
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return redirect()->route('sales-orders.index')->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $sales_order)
    {
        $sales_order->load(['orderItems.item', 'user']);
        return view('sales_orders.show', compact('sales_order'));
    }

    /**
     * Process an order (mark as processing)
     */
    public function processOrder(Request $request, $orderId)
    {
        $order = SalesOrder::findOrFail($orderId);
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be processed.');
        }
        $order->status = 'processing';
        $order->save();
        return redirect()->back()->with('success', 'Order marked as processing.');
    }

    /**
     * Cancel an order
     */
    public function cancelOrder(Request $request, $orderId)
    {
        $order = SalesOrder::findOrFail($orderId);
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()->with('error', 'Only pending or processing orders can be cancelled.');
        }
        
        // Restore stock for both pending and processing orders
        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;
            $item->quantity += $orderItem->quantity;
            $item->save();
            
            // Log stock movement for cancelled order
            \App\Models\StockMovement::create([
                'item_id' => $item->item_id,
                'movement_type' => 'inbound',
                'quantity_changed' => $orderItem->quantity,
                'new_total_quantity' => $item->quantity,
                'user_id' => auth()->id(),
                'remarks' => 'Order cancellation - Order ID: ' . $order->order_id,
            ]);
        }
        
        $order->status = 'cancelled';
        $order->save();
        return redirect()->back()->with('success', 'Order has been cancelled and stock has been restored.');
    }

    /**
     * Delete an order (only pending orders can be deleted)
     */
    public function deleteOrder(Request $request, $orderId)
    {
        $order = SalesOrder::findOrFail($orderId);
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending orders can be deleted.');
        }
        
        // Restore stock
        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;
            $item->quantity += $orderItem->quantity;
            $item->save();
        }
        
        $order->delete();
        return redirect()->back()->with('success', 'Order has been deleted.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, SalesOrder $sales_order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);
        $sales_order->status = $request->status;
        $sales_order->save();
        return back()->with('success', 'Order status updated!');
    }
}
