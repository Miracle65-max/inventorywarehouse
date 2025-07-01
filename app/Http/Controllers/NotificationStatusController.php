<?php

namespace App\Http\Controllers;

use App\Models\BorrowedItem;
use App\Models\DefectiveItem;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationStatusController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'borrowed');

        // Get active borrowed items
        $borrowedItems = BorrowedItem::with(['item'])
            ->where('status', 'borrowed')
            ->orderBy('borrowed_date', 'desc')
            ->get();

        // Get all defective items with supplier info
        $defectiveItems = DefectiveItem::with(['item.supplier'])
            ->orderBy('defect_date', 'desc')
            ->get()
            ->map(function ($item) {
                $item->days_outstanding = now()->diffInDays($item->defect_date);
                return $item;
            });

        return view('inventory-status.index', compact('borrowedItems', 'defectiveItems', 'tab'));
    }

    public function returnItem(Request $request)
    {
        $request->validate([
            'borrowed_id' => 'required|integer|exists:borrowed_items_new,borrowed_id',
            'item_id' => 'required|integer|exists:items,item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $borrowedItem = BorrowedItem::findOrFail($request->borrowed_id);
            $item = Item::findOrFail($request->item_id);

            // Update borrowed item status
            $borrowedItem->update([
                'status' => 'returned',
                'returned_date' => now()
            ]);

            // Update item quantity
            $item->increment('quantity', $request->quantity);

            // Record stock movement
            StockMovement::create([
                'item_id' => $request->item_id,
                'movement_type' => 'borrowed_return',
                'quantity_changed' => $request->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item returned from borrowing'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Item successfully returned!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error returning item: ' . $e->getMessage());
        }
    }

    public function repairItem(Request $request)
    {
        $request->validate([
            'defect_id' => 'required|integer|exists:defective_items,defect_id',
            'item_id' => 'required|integer|exists:items,item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $defectiveItem = DefectiveItem::findOrFail($request->defect_id);
            $item = Item::findOrFail($request->item_id);

            // Update defective item status
            $defectiveItem->update([
                'status' => 'repaired',
                'repair_date' => now()
            ]);

            // Add back to inventory
            $item->increment('quantity', $request->quantity);

            // Record stock movement
            StockMovement::create([
                'item_id' => $request->item_id,
                'movement_type' => 'repaired',
                'quantity_changed' => $request->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item repaired from defective inventory'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Item successfully repaired!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error repairing item: ' . $e->getMessage());
        }
    }

    public function disposeItem(Request $request)
    {
        $request->validate([
            'defect_id' => 'required|integer|exists:defective_items,defect_id',
            'item_id' => 'required|integer|exists:items,item_id',
            'quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $defectiveItem = DefectiveItem::findOrFail($request->defect_id);
            $item = Item::findOrFail($request->item_id);

            // Update defective item status
            $defectiveItem->update([
                'status' => 'disposed',
                'disposal_date' => now()
            ]);

            // Record stock movement
            StockMovement::create([
                'item_id' => $request->item_id,
                'movement_type' => 'disposed',
                'quantity_changed' => $request->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item disposed from defective inventory'
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Item successfully disposed!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error disposing item: ' . $e->getMessage());
        }
    }
}
