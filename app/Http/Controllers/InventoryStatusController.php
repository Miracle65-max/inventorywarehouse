<?php

namespace App\Http\Controllers;

use App\Models\BorrowedItem;
use App\Models\DefectiveItem;
use App\Models\Item;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class InventoryStatusController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'borrowed');

        // Get active borrowed items
        $borrowedItems = BorrowedItem::with(['item'])
            ->where('status', 'borrowed')
            ->orderBy('borrowed_date', 'desc')
            ->get();

        // Filtering and pagination for defective items
        $query = DefectiveItem::with(['item.supplier']);
        $search = $request->get('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($q2) use ($search) {
                    $q2->where('item_name', 'like', "%$search%")
                        ->orWhere('item_code', 'like', "%$search%")
                        ->orWhereHas('supplier', function($q3) use ($search) {
                            $q3->where('supplier_name', 'like', "%$search%")
                                ->orWhere('contact_person', 'like', "%$search%")
                                ->orWhere('contact_number', 'like', "%$search%");
                        });
                })
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('defect_reason', 'like', "%$search%");
            });
        }
        $defectiveItems = $query->get()->map(function ($item) {
            $item->days_outstanding = (int) now()->diffInDays($item->defect_date);
            return $item;
        });
        // Prioritize 'pending' at the top, then 'repaired', then 'disposed'
        $defectiveItems = $defectiveItems->sortBy(function($item) {
            return $item->status === 'pending' ? 0 : ($item->status === 'repaired' ? 1 : 2);
        })->values();
        // Paginate manually since we used collection
        $perPage = 10;
        $page = $request->get('page', 1);
        $paginatedDefective = new \Illuminate\Pagination\LengthAwarePaginator(
            $defectiveItems->forPage($page, $perPage),
            $defectiveItems->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('inventory-status.index', [
            'borrowedItems' => $borrowedItems,
            'defectiveItems' => $paginatedDefective,
            'tab' => $tab,
            'search' => $search,
        ]);
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

            // Check authorization
            if (!Gate::allows('return', $borrowedItem)) {
                return $request->ajax()
                    ? response()->json(['error' => 'Unauthorized: You do not have permission to return items.'], 403)
                    : redirect()->back()->with('error', 'Unauthorized: You do not have permission to return items.');
            }

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
                'new_total_quantity' => $item->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item returned from borrowing'
            ]);

            DB::commit();
            return $request->ajax()
                ? response()->json(['success' => 'Item successfully returned!', 'html' => $this->getBorrowedTableHtml($request)])
                : redirect()->back()->with('success', 'Item successfully returned!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $request->ajax()
                ? response()->json(['error' => 'Error returning item: ' . $e->getMessage()], 500)
                : redirect()->back()->with('error', 'Error returning item: ' . $e->getMessage());
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

            // Check authorization
            if (!Gate::allows('repair', $defectiveItem)) {
                return $request->ajax()
                    ? response()->json(['error' => 'Unauthorized: You do not have permission to repair items.'], 403)
                    : redirect()->back()->with('error', 'Unauthorized: You do not have permission to repair items.');
            }

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
                'new_total_quantity' => $item->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item repaired from defective inventory'
            ]);

            DB::commit();
            return $request->ajax()
                ? response()->json(['success' => 'Item successfully repaired!', 'html' => $this->getDefectiveTableHtml($request)])
                : redirect()->back()->with('success', 'Item successfully repaired!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $request->ajax()
                ? response()->json(['error' => 'Error repairing item: ' . $e->getMessage()], 500)
                : redirect()->back()->with('error', 'Error repairing item: ' . $e->getMessage());
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

            // Check authorization
            if (!Gate::allows('dispose', $defectiveItem)) {
                return $request->ajax()
                    ? response()->json(['error' => 'Unauthorized: You do not have permission to dispose items.'], 403)
                    : redirect()->back()->with('error', 'Unauthorized: You do not have permission to dispose items.');
            }

            $item = Item::findOrFail($request->item_id);

            // Update defective item status
            $defectiveItem->update([
                'status' => 'disposed',
                'disposal_date' => now()
            ]);

            // Optionally reduce item quantity (uncomment if desired)
            // $item->decrement('quantity', $request->quantity);

            // Record stock movement
            StockMovement::create([
                'item_id' => $request->item_id,
                'movement_type' => 'disposed',
                'quantity_changed' => -$request->quantity, // Negative to indicate removal
                'new_total_quantity' => $item->quantity,
                'user_id' => Auth::id(),
                'remarks' => 'Item disposed from defective inventory'
            ]);

            DB::commit();
            return $request->ajax()
                ? response()->json(['success' => 'Item successfully disposed!', 'html' => $this->getDefectiveTableHtml($request)])
                : redirect()->back()->with('success', 'Item successfully disposed!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $request->ajax()
                ? response()->json(['error' => 'Error disposing item: ' . $e->getMessage()], 500)
                : redirect()->back()->with('error', 'Error disposing item: ' . $e->getMessage());
        }
    }

    public function ajaxSearch(Request $request)
    {
        $search = $request->get('search');
        $query = DefectiveItem::with(['item.supplier']);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($q2) use ($search) {
                    $q2->where('item_name', 'like', "%$search%")
                        ->orWhere('item_code', 'like', "%$search%")
                        ->orWhereHas('supplier', function($q3) use ($search) {
                            $q3->where('supplier_name', 'like', "%$search%")
                                ->orWhere('contact_person', 'like', "%$search%")
                                ->orWhere('contact_number', 'like', "%$search%");
                        });
                })
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('defect_reason', 'like', "%$search%");
            });
        }
        $defectiveItems = $query->get()->map(function ($item) {
            $item->days_outstanding = (int) now()->diffInDays($item->defect_date);
            return $item;
        });
        $defectiveItems = $defectiveItems->sortBy(function($item) {
            return $item->status === 'pending' ? 0 : ($item->status === 'repaired' ? 1 : 2);
        })->values();
        $html = view('inventory-status.partials.defective_table_body', ['defectiveItems' => $defectiveItems])->render();
        return response()->json(['html' => $html]);
    }

    // Helper method to get defective table HTML
    protected function getDefectiveTableHtml(Request $request)
    {
        $search = $request->get('search');
        $query = DefectiveItem::with(['item.supplier']);
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($q2) use ($search) {
                    $q2->where('item_name', 'like', "%$search%")
                        ->orWhere('item_code', 'like', "%$search%")
                        ->orWhereHas('supplier', function($q3) use ($search) {
                            $q3->where('supplier_name', 'like', "%$search%")
                                ->orWhere('contact_person', 'like', "%$search%")
                                ->orWhere('contact_number', 'like', "%$search%");
                        });
                })
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('defect_reason', 'like', "%$search%");
            });
        }
        $defectiveItems = $query->get()->map(function ($item) {
            $item->days_outstanding = (int) now()->diffInDays($item->defect_date);
            return $item;
        });
        $defectiveItems = $defectiveItems->sortBy(function($item) {
            return $item->status === 'pending' ? 0 : ($item->status === 'repaired' ? 1 : 2);
        })->values();
        return view('inventory-status.partials.defective_table_body', ['defectiveItems' => $defectiveItems])->render();
    }

    // Helper method to get borrowed table HTML (for returnItem AJAX)
    protected function getBorrowedTableHtml(Request $request)
    {
        $borrowedItems = BorrowedItem::with(['item'])
            ->where('status', 'borrowed')
            ->orderBy('borrowed_date', 'desc')
            ->get();
        return view('inventory-status.partials.borrowed_table_body', ['borrowedItems' => $borrowedItems])->render();
    }
}


