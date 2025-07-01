<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditTrail;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('items')->orderByDesc('created_at')->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:20',
        ]);
        $supplier = Supplier::create($validated);
        // Audit trail log
        AuditTrail::create([
            'user_id' => Auth::id(),
            'module' => 'Suppliers',
            'action' => 'Created supplier: ' . $supplier->supplier_name,
            'details' => $supplier->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('items');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'nullable|string|max:20',
        ]);
        $supplier->update($validated);
        // Audit trail log
        AuditTrail::create([
            'user_id' => Auth::id(),
            'module' => 'Suppliers',
            'action' => 'Updated supplier: ' . $supplier->supplier_name,
            'details' => $supplier->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->supplier_name;
        $supplierData = $supplier->toArray();
        $supplier->delete();
        // Audit trail log
        AuditTrail::create([
            'user_id' => Auth::id(),
            'module' => 'Suppliers',
            'action' => 'Deleted supplier: ' . $supplierName,
            'details' => $supplierData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }
}
