<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\AuditTrail;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('supplier');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('item_name', 'like', '%'.$request->search.'%')
                  ->orWhere('item_code', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('supplier')) {
            $query->where('supplier_id', $request->supplier);
        }
        
        $items = $query->paginate(15);
        $suppliers = Supplier::all();
        return view('items.index', compact('items', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('items.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'item_name' => 'required|string|min:3|max:255',
                'category' => 'required|string|max:100|in:electrical,plumbing,hardware,tools,materials,other',
                'description' => 'nullable|string|max:500',
                'quantity' => 'required|integer|min:0',
                'unit' => 'required|string|min:1|max:50',
                'cost_price' => 'required|numeric|min:0|max:999999999.99',
                'location' => 'nullable|string|max:255',
                'supplier_id' => 'nullable|exists:suppliers,supplier_id',
                'date_received' => 'required|date|before_or_equal:today',
            ], [
                'item_name.min' => 'Item name must be at least 3 characters long.',
                'item_name.max' => 'Item name must not exceed 255 characters.',
                'category.in' => 'Please select a valid category.',
                'quantity.min' => 'Quantity cannot be negative.',
                'quantity.integer' => 'Quantity must be a whole number.',
                'unit.min' => 'Unit is required.',
                'unit.max' => 'Unit must not exceed 50 characters.',
                'cost_price.min' => 'Cost price cannot be negative.',
                'cost_price.max' => 'Cost price is too large.',
                'location.max' => 'Location must not exceed 255 characters.',
                'date_received.before_or_equal' => 'Date received cannot be in the future.',
            ]);

            // Generate a unique item_code
            $category = $request->input('category');
            $prefix = strtoupper(substr($category, 0, 3));
            $yearMonth = Carbon::now()->format('Ym'); // e.g., 202507
            $latestItem = Item::where('item_code', 'like', "$prefix-$yearMonth%")
                ->orderBy('item_code', 'desc')
                ->first();

            $sequence = 1;
            if ($latestItem) {
                $parts = explode('-', $latestItem->item_code);
                $sequence = (int) $parts[2] + 1;
            }
            $itemCode = sprintf("%s-%s-%04d", $prefix, $yearMonth, $sequence);

            // Add item_code to validated data
            $validated['item_code'] = $itemCode;

            // Create the item
            $item = Item::create($validated);

            // Audit trail log
            AuditTrail::create([
                'user_id' => Auth::id(),
                'module' => 'Items',
                'action' => 'Created item: ' . $item->item_name,
                'details' => ['item_id' => $item->item_id ?? $item->id],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('items.index')->with('success', 'Item added successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) { // Integrity constraint violation
                return back()->withErrors(['item_code' => 'The generated item code is already in use. Please try again.'])->withInput();
            }
            throw $e;
        }
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $suppliers = Supplier::all();
        return view('items.edit', compact('item', 'suppliers'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|min:3|max:255',
            'item_code' => 'required|string|min:2|max:100|unique:items,item_code,'.$item->item_id.',item_id|regex:/^[A-Za-z0-9-_]+$/',
            'category' => 'required|string|max:100|in:electrical,plumbing,hardware,tools,materials,other',
            'description' => 'nullable|string|max:500',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|min:1|max:50',
            'cost_price' => 'required|numeric|min:0|max:999999999.99',
            'location' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'date_received' => 'required|date|before_or_equal:today',
        ], [
            'item_name.min' => 'Item name must be at least 3 characters long.',
            'item_name.max' => 'Item name must not exceed 255 characters.',
            'item_code.min' => 'Item code must be at least 2 characters long.',
            'item_code.max' => 'Item code must not exceed 100 characters.',
            'item_code.regex' => 'Item code can only contain letters, numbers, hyphens, and underscores.',
            'item_code.unique' => 'Item code already exists!',
            'category.in' => 'Please select a valid category.',
            'quantity.min' => 'Quantity cannot be negative.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'unit.min' => 'Unit is required.',
            'unit.max' => 'Unit must not exceed 50 characters.',
            'cost_price.min' => 'Cost price cannot be negative.',
            'cost_price.max' => 'Cost price is too large.',
            'location.max' => 'Location must not exceed 255 characters.',
            'date_received.before_or_equal' => 'Date received cannot be in the future.',
        ]);

        $item->update($validated);

        // Audit trail log
        AuditTrail::create([
            'user_id' => Auth::id(),
            'module' => 'Items',
            'action' => 'Updated item: ' . $item->item_name,
            'details' => ['item_id' => $item->item_id ?? $item->id],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $itemName = $item->item_name;
        $itemId = $item->item_id ?? $item->id;
        $item->delete();

        // Audit trail log
        AuditTrail::create([
            'user_id' => Auth::id(),
            'module' => 'Items',
            'action' => 'Deleted item: ' . $itemName,
            'details' => ['item_id' => $itemId],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    // Fetch current stock for AJAX
    public function currentStock($id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        return response()->json([
            'quantity' => $item->quantity,
            'unit' => $item->unit,
        ]);
    }

    // Generate unique item_code for AJAX preview
    public function generateItemCode($category)
    {
        $prefix = strtoupper(substr($category, 0, 3));
        $yearMonth = Carbon::now()->format('Ym');
        $latestItem = Item::where('item_code', 'like', "$prefix-$yearMonth%")
            ->orderBy('item_code', 'desc')
            ->first();

        $sequence = 1;
        if ($latestItem) {
            $parts = explode('-', $latestItem->item_code);
            $sequence = (int) $parts[2] + 1;
        }
        $itemCode = sprintf("%s-%s-%04d", $prefix, $yearMonth, $sequence);

        return response()->json(['item_code' => $itemCode]);
    }

    public function listNotifications(Request $request)
    {
        $user = Auth::user();
        $notifications = Notification::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id'); // global notifications
            })
            ->orderBy('is_read') // unread first
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'type' => $n->type ?? 'info',
                    'is_read' => $n->is_read,
                    'created_at_human' => \Carbon\Carbon::parse($n->created_at)->diffForHumans(),
                ];
            });
        $unreadCount = Notification::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id');
            })
            ->where('is_read', false)
            ->count();
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markNotificationRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id'); // Allow marking global notifications as read
            })
            ->firstOrFail();
        $notification->is_read = true;
        $notification->save();
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();
        Notification::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereNull('user_id'); // Include global notifications
            })
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}