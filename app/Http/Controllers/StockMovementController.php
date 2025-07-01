<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use App\Models\DefectiveItem;
use App\Models\BorrowedItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::orderBy('item_name')->get();
        $query = StockMovement::with(['item', 'user']);

        // Filtering
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('item', function($q2) use ($search) {
                    $q2->where('item_name', 'like', "%$search%")
                        ->orWhere('item_code', 'like', "%$search%")
                        ->orWhere('unit', 'like', "%$search%")
                        ->orWhere('category', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%")
                        ;
                })
                ->orWhere('remarks', 'like', "%$search%")
                ->orWhere('movement_type', 'like', "%$search%")
                ;
            });
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->input('movement_type'));
        }
        if ($request->filled('item_id')) {
            $query->where('item_id', $request->input('item_id'));
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $movements = $query->orderByDesc('created_at')->paginate(15)->appends($request->except('page'));

        return view('stock_movements.index', compact('items', 'movements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'movement_type' => 'required|in:inbound,outbound,borrowed,defective',
            'quantity_changed' => 'required|integer|min:1',
            'remarks' => 'nullable|string',
            'borrower_name' => 'required_if:movement_type,borrowed|nullable|string',
            'source_warehouse' => 'required_if:movement_type,borrowed|nullable|string',
            'expected_return_date' => 'required_if:movement_type,borrowed|nullable|date|after:today',
            'defect_reason' => 'required_if:movement_type,defective|nullable|string',
        ]);
        
        $item = \App\Models\Item::findOrFail($request->item_id);
        $user = Auth::user();
        $new_total = $item->quantity;
        
        if ($request->movement_type === 'inbound') {
            $new_total += $request->quantity_changed;
        } elseif (in_array($request->movement_type, ['outbound', 'borrowed', 'defective'])) {
            $new_total -= $request->quantity_changed;
            if ($new_total < 0) {
                return back()->withErrors(['quantity_changed' => 'Insufficient stock.']);
            }
        }
        
        DB::beginTransaction();
        try {
            $item->quantity = $new_total;
            $item->save();
            
            StockMovement::create([
                'item_id' => $item->item_id,
                'movement_type' => $request->movement_type,
                'quantity_changed' => $request->quantity_changed,
                'new_total_quantity' => $new_total,
                'user_id' => $user->id,
                'remarks' => $request->remarks,
            ]);
            
            // If movement type is borrowed, create a borrowed item record
            if ($request->movement_type === 'borrowed') {
                BorrowedItem::create([
                    'item_id' => $item->item_id,
                    'quantity' => $request->quantity_changed,
                    'borrower_name' => $request->borrower_name,
                    'source_warehouse' => $request->source_warehouse,
                    'expected_return_date' => $request->expected_return_date,
                    'borrowed_date' => now(),
                    'status' => 'borrowed'
                ]);
            }
            
            // If movement type is defective, create a defective item record
            if ($request->movement_type === 'defective') {
                DefectiveItem::create([
                    'item_id' => $item->item_id,
                    'supplier_id' => $item->supplier_id,
                    'quantity_defective' => $request->quantity_changed,
                    'defect_reason' => $request->defect_reason,
                    'defect_date' => now(),
                    'status' => 'pending',
                    'reported_by' => $user->id
                ]);
            }
            
            DB::commit();
            return redirect()->route('stock-movements.index')->with('success', 'Stock movement recorded successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }
}
