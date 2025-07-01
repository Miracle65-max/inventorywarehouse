<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecentlyDeletedController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        // Get soft deleted items from different tables
        $deletedUsers = User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $deletedItems = Item::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $deletedSalesOrders = SalesOrder::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $deletedSuppliers = Supplier::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        // Combine all deleted items with their type
        $allDeletedItems = collect();

        foreach ($deletedUsers as $user) {
            $deletedBy = null;
            if (isset($user->deleted_by)) {
                $deletedByUser = \App\Models\User::find($user->deleted_by);
                $deletedBy = $deletedByUser ? ($deletedByUser->full_name ?? $deletedByUser->name ?? $deletedByUser->username) : null;
            }
            $allDeletedItems->push([
                'id' => $user->id,
                'name' => $user->full_name ?? $user->name ?? $user->username,
                'type' => 'User',
                'deleted_at' => $user->deleted_at,
                'model' => $user,
                'description' => $user->email . ' (' . ucfirst($user->role) . ')',
                'deleted_by' => $deletedBy,
            ]);
        }

        foreach ($deletedItems as $item) {
            $deletedBy = null;
            if ($item->deleted_by) {
                $user = \App\Models\User::find($item->deleted_by);
                $deletedBy = $user ? ($user->full_name ?? $user->name ?? $user->username) : null;
            }
            $allDeletedItems->push([
                'id' => $item->item_id,
                'name' => $item->name,
                'type' => 'Item',
                'deleted_at' => $item->deleted_at,
                'model' => $item,
                'description' => 'SKU: ' . ($item->item_code ?? '') . ' - Stock: ' . ($item->current_stock ?? $item->quantity ?? ''),
                'deleted_by' => $deletedBy,
            ]);
        }

        foreach ($deletedSalesOrders as $order) {
            $deletedBy = null;
            if (isset($order->deleted_by)) {
                $deletedByUser = \App\Models\User::find($order->deleted_by);
                $deletedBy = $deletedByUser ? ($deletedByUser->full_name ?? $deletedByUser->name ?? $deletedByUser->username) : null;
            }
            $allDeletedItems->push([
                'id' => $order->order_id,
                'name' => 'Order #' . $order->order_number,
                'type' => 'Sales Order',
                'deleted_at' => $order->deleted_at,
                'model' => $order,
                'description' => 'Customer: ' . $order->customer_name . ' - Total: ₱' . number_format($order->total_amount, 2),
                'deleted_by' => $deletedBy,
            ]);
        }

        foreach ($deletedSuppliers as $supplier) {
            $deletedBy = null;
            if (isset($supplier->deleted_by)) {
                $deletedByUser = \App\Models\User::find($supplier->deleted_by);
                $deletedBy = $deletedByUser ? ($deletedByUser->full_name ?? $deletedByUser->name ?? $deletedByUser->username) : null;
            }
            $allDeletedItems->push([
                'id' => $supplier->supplier_id,
                'name' => $supplier->name,
                'type' => 'Supplier',
                'deleted_at' => $supplier->deleted_at,
                'model' => $supplier,
                'description' => $supplier->email . ' - ' . $supplier->phone,
                'deleted_by' => $deletedBy,
            ]);
        }

        // Sort by deletion date (most recent first)
        $allDeletedItems = $allDeletedItems->sortByDesc('deleted_at');

        // Get statistics
        $stats = [
            'total_deleted' => $allDeletedItems->count(),
            'users_deleted' => $deletedUsers->count(),
            'items_deleted' => $deletedItems->count(),
            'orders_deleted' => $deletedSalesOrders->count(),
            'suppliers_deleted' => $deletedSuppliers->count(),
            'oldest_deletion' => $allDeletedItems->last() ? $allDeletedItems->last()['deleted_at'] : null,
            'newest_deletion' => $allDeletedItems->first() ? $allDeletedItems->first()['deleted_at'] : null,
        ];

        return view('recently-deleted.index', compact('allDeletedItems', 'stats'));
    }

    public function restore(Request $request, $type, $id)
    {
        try {
            switch ($type) {
                case 'user':
                    $item = User::onlyTrashed()->findOrFail($id);
                    $this->authorize('restore', $item);
                    $item->restore();
                    $message = 'User restored successfully!';
                    break;
                case 'item':
                    $item = Item::onlyTrashed()->findOrFail($id);
                    $this->authorize('restore', $item);
                    // Check if item code exists in active items
                    $code = $item->item_code;
                    $exists = Item::where('item_code', $code)->whereNull('deleted_at')->exists();
                    if ($exists) {
                        return redirect()->back()->with('error', 'Cannot restore: Item code already exists in active items.');
                    }
                    $item->restore();
                    $message = 'Item restored successfully!';
                    break;
                case 'sales-order':
                    $item = SalesOrder::onlyTrashed()->findOrFail($id);
                    $this->authorize('restore', $item);
                    $item->restore();
                    $message = 'Sales order restored successfully!';
                    break;
                case 'supplier':
                    $item = Supplier::onlyTrashed()->findOrFail($id);
                    $this->authorize('restore', $item);
                    $item->restore();
                    $message = 'Supplier restored successfully!';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid item type.');
            }

            return redirect()->route('recently-deleted.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to restore item. ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $type, $id)
    {
        try {
            switch ($type) {
                case 'user':
                    $item = User::onlyTrashed()->findOrFail($id);
                    $this->authorize('forceDelete', $item);
                    $item->forceDelete();
                    $message = 'User permanently deleted!';
                    break;
                case 'item':
                    $item = Item::onlyTrashed()->findOrFail($id);
                    $this->authorize('forceDelete', $item);
                    $item->forceDelete();
                    $message = 'Item permanently deleted!';
                    break;
                case 'sales-order':
                    $item = SalesOrder::onlyTrashed()->findOrFail($id);
                    $this->authorize('forceDelete', $item);
                    $item->forceDelete();
                    $message = 'Sales order permanently deleted!';
                    break;
                case 'supplier':
                    $item = Supplier::onlyTrashed()->findOrFail($id);
                    $this->authorize('forceDelete', $item);
                    $item->forceDelete();
                    $message = 'Supplier permanently deleted!';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid item type.');
            }

            return redirect()->route('recently-deleted.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to permanently delete item. ' . $e->getMessage());
        }
    }

    public function restoreAll(Request $request)
    {
        $this->authorize('restore', User::class);

        try {
            DB::beginTransaction();

            User::onlyTrashed()->restore();
            Item::onlyTrashed()->restore();
            SalesOrder::onlyTrashed()->restore();
            Supplier::onlyTrashed()->restore();

            DB::commit();

            return redirect()->route('recently-deleted.index')->with('success', 'All items restored successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to restore all items. ' . $e->getMessage());
        }
    }

    public function destroyAll(Request $request)
    {
        $this->authorize('forceDelete', User::class);

        try {
            DB::beginTransaction();

            User::onlyTrashed()->forceDelete();
            Item::onlyTrashed()->forceDelete();
            SalesOrder::onlyTrashed()->forceDelete();
            Supplier::onlyTrashed()->forceDelete();

            DB::commit();

            return redirect()->route('recently-deleted.index')->with('success', 'All items permanently deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to permanently delete all items. ' . $e->getMessage());
        }
    }
} 