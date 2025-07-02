@extends('layouts.app')
@section('title', 'Inventory Items')
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh;">
    <div class="justify-between mb-4 d-flex align-center">
        <h1 class="mb-0" style="font-size: 2rem; letter-spacing: 0.5px; color: #1d2327;">Inventory Items</h1>
        <div>
            @auth
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                    <a href="{{ route('items.create') }}" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2);">+ Add New Item</a>
                @endif
            @endauth
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 alert alert-success" style="box-shadow: 0 1px 4px rgba(70,180,80,0.08); background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 12px 16px; border-radius: 3px; border: 1px solid;">{{ session('success') }}</div>
    @endif

    <!-- Search and Filter Section -->
    <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white; margin-bottom: 20px;">
        <div class="card-body" style="padding: 20px;">
            <form method="GET" action="{{ route('items.index') }}" style="margin: 0;">
                <div class="row" style="display: flex; gap: 20px; align-items: end;">
                    <div class="col-md-4" style="flex: 1;">
                        <label for="search" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Search Items</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name, code, or description..." 
                               style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
            </div>
                    <div class="col-md-2" style="flex: 0 0 auto;">
                        <label for="category" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Category</label>
                        <select name="category" 
                                id="category" 
                                style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                    <option value="">All Categories</option>
                            <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                            <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                            <option value="hardware" {{ request('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                            <option value="tools" {{ request('category') == 'tools' ? 'selected' : '' }}>Tools</option>
                            <option value="materials" {{ request('category') == 'materials' ? 'selected' : '' }}>Materials</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-2" style="flex: 0 0 auto;">
                        <label for="supplier" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Supplier</label>
                        <select name="supplier" 
                                id="supplier" 
                                style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->supplier_id }}" {{ request('supplier') == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                    @endforeach
                </select>
            </div>
                    <div class="col-md-2" style="flex: 0 0 auto;">
                        <button type="submit" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); cursor: pointer; width: 100%;">Search</button>
                    </div>
                    <div class="col-md-1" style="flex: 0 0 auto;">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2); cursor: pointer; width: 100%;">Clear</a>
                    </div>
        </div>
            </form>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white;">
        <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Items ({{ $items->total() }})</h3>
                <div style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container" style="padding: 20px;">
                @if($items->count() > 0)
                    <table class="table" style="border-radius: 3px; overflow: hidden; width: 100%; border-collapse: collapse;">
                        <thead style="position: sticky; top: 0; background: #f1f1f1; z-index: 1;">
                            <tr>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 20%;">Item Name</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 15%;">Code & Barcode</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 10%;">Category</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 10%;">Qty</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 12%;">Price</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 12%;">Total</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; width: 12%;">Actions</th>
                    </tr>
                </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr style="transition: background 0.2s; border-bottom: 1px solid #f0f0f0; height: 60px;">
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                <div>
                                            <strong style="color: #1d2327; font-size: 14px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $item->item_name }}</strong>
                                            @if($item->description)
                                                <div style="color: #646970; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ Str::limit($item->description, 25) }}</div>
                                            @endif
                                    @if($item->location)
                                                <div style="color: #646970; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">📍 {{ Str::limit($item->location, 20) }}</div>
                                    @endif
                                </div>
                            </td>
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <div style="margin-bottom: 5px;">
                                            <span style="color: #646970; font-size: 13px; font-family: monospace; font-weight: 600;">{{ $item->item_code }}</span>
                                        </div>
                                        <div class="barcode-container" style="position: relative; display: inline-block;">
                                            <img src="{{ route('barcode.generate', $item->item_code) }}" 
                                                 alt="Barcode for {{ $item->item_code }}" 
                                                 class="barcode-image"
                                                 style="max-width: 120px; height: 30px; cursor: pointer; transition: transform 0.2s ease;"
                                                 title="Click to enlarge barcode"
                                                 onclick="showBarcodeModal('{{ $item->item_code }}', '{{ $item->item_name }}')">
                                        </div>
                                    </td>
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <span class="badge badge-secondary" style="font-weight:600;text-transform:uppercase;letter-spacing:0.5px;box-shadow:0 1px 3px #e2e3e5; background-color: #6c757d; color: white; padding: 3px 6px; border-radius: 3px; font-size: 9px;">{{ ucfirst($item->category) }}</span>
                            </td>
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <div style="color: #1d2327; font-size: 14px; font-weight: 600;">{{ number_format($item->quantity) }}</div>
                                        <div style="color: #646970; font-size: 11px;">{{ $item->unit }}</div>
                            </td>
                                    <td style="padding: 12px 16px; color: #1d2327; font-size: 13px; vertical-align: middle;">₱{{ number_format($item->cost_price, 2) }}</td>
                                    <td style="padding: 12px 16px; color: #1d2327; font-size: 13px; font-weight: 600; vertical-align: middle;">₱{{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                                    <td style="padding: 12px 16px; vertical-align: middle;">
                                        <div class="action-buttons" style="display: flex; gap: 2px; flex-wrap: wrap; max-width: 100%;">
                                            <a href="{{ route('items.show', $item) }}" class="btn btn-sm btn-info" style="background-color: #17a2b8; color: white; padding: 3px 6px; border-radius: 3px; text-decoration: none; font-size: 10px; font-weight: 500; border: 1px solid #17a2b8; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(23,162,184,0.2); white-space: nowrap; min-width: 40px; text-align: center;">View</a>
                                            <button onclick="showBarcodeModal('{{ $item->item_code }}', '{{ $item->item_name }}')" class="btn btn-sm btn-success" style="background-color: #28a745; color: white; padding: 3px 6px; border-radius: 3px; text-decoration: none; font-size: 10px; font-weight: 500; border: 1px solid #28a745; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(40,167,69,0.2); white-space: nowrap; min-width: 40px; text-align: center; cursor: pointer;">Barcode</button>
                                            @auth
                                                @php $user = auth()->user(); @endphp
                                                @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning" style="background-color: #ffc107; color: #212529; padding: 3px 6px; border-radius: 3px; text-decoration: none; font-size: 10px; font-weight: 500; border: 1px solid #ffc107; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(255,193,7,0.2); white-space: nowrap; min-width: 40px; text-align: center;">Edit</a>
                                                    <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline; margin: 0; padding: 0;" onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" style="background-color: #dc3545; color: white; padding: 3px 6px; border-radius: 3px; text-decoration: none; font-size: 10px; font-weight: 500; border: 1px solid #dc3545; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(220,53,69,0.2); white-space: nowrap; min-width: 40px; text-align: center; cursor: pointer;">Del</button>
                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                        @if($item->supplier)
                                            <div style="margin-top: 4px;">
                                                <span style="color: #136735; font-size: 10px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">🏢 {{ Str::limit($item->supplier->supplier_name, 12) }}</span>
                                </div>
                                        @endif
                            </td>
                        </tr>
                            @endforeach
                </tbody>
            </table>

                    <!-- Pagination -->
                    @if($items->hasPages())
                        <div class="pagination-container" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e1e5e9;">
                            {{ $items->appends(request()->query())->links() }}
        </div>
                    @endif
                @else
                    <div class="text-center" style="text-align: center; color: #646970; margin: 40px 0; padding: 40px;">
                        <span style="font-size: 48px; display: block; margin-bottom: 16px;">📦</span>
                        <p style="margin: 0; font-size: 14px; color: #646970;">No items found matching your criteria.</p>
                        @if(request('search') || request('category') || request('supplier'))
                            <a href="{{ route('items.index') }}" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); margin-top: 16px; display: inline-block;">Clear Filters</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Barcode Modal -->
<div id="barcodeModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 id="modalTitle" style="margin: 0; color: #1d2327; font-size: 18px; font-weight: 600;">Item Barcode</h3>
            <span class="close" onclick="closeBarcodeModal()" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1;">&times;</span>
        </div>
        <div class="modal-body" style="text-align: center;">
            <div id="modalBarcodeContainer" style="margin: 20px 0; padding: 20px; border: 2px solid #e1e5e9; border-radius: 8px; background: white;">
                <img id="modalBarcodeImage" src="" alt="Barcode" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                <div id="modalBarcodeText" style="margin-top: 10px; font-family: monospace; font-size: 18px; font-weight: 600; color: #1d2327;"></div>
            </div>
            <div class="modal-actions" style="margin-top: 20px;">
                <button onclick="printBarcode()" class="btn btn-primary" style="background-color: #136735; color: white; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-size: 14px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); cursor: pointer; margin-right: 10px;">Print Barcode</button>
                <button onclick="closeBarcodeModal()" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-size: 14px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2); cursor: pointer;">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus { 
    outline: none; 
    border-color: #136735; 
    box-shadow: 0 0 0 2px rgba(19,103,53,0.2); 
}
.table tbody tr:hover { background: #f6f8fa !important; }
.btn:hover { 
    filter: brightness(0.95); 
    box-shadow: 0 2px 8px rgba(44,62,80,0.15); 
    transform: translateY(-1px); 
}
.badge { font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 1px 3px #e2e3e5; }
.pagination { justify-content: center; }
.pagination .page-link { 
    border-radius: 3px; 
    border: 1px solid #e1e5e9; 
    color: #136735; 
    margin: 0 2px; 
    padding: 8px 12px; 
    font-size: 13px; 
    font-weight: 500; 
}
.pagination .page-link:hover { 
    background-color: #136735; 
    border-color: #136735; 
    color: white; 
}
.pagination .page-item.active .page-link { 
    background-color: #136735; 
    border-color: #136735; 
    color: white; 
}
.action-buttons {
    display: flex;
    gap: 2px;
    flex-wrap: wrap;
    max-width: 100%;
}
.action-buttons .btn {
    flex: 1;
    min-width: 40px;
    max-width: 50px;
    text-align: center;
    font-size: 10px !important;
    padding: 3px 6px !important;
}
.action-buttons form {
    flex: 1;
    min-width: 40px;
    max-width: 50px;
}
.action-buttons form button {
    width: 100%;
    min-width: 40px;
    max-width: 50px;
    text-align: center;
    font-size: 10px !important;
    padding: 3px 6px !important;
}
.table {
    table-layout: fixed;
    width: 100%;
}
.table th,
.table td {
    word-wrap: break-word;
    overflow: hidden;
}
@media (max-width: 768px) {
    .row { flex-direction: column; }
    .col-md-3, .col-md-2, .col-md-1 { flex: none; }
    .gap-1 { gap: 8px !important; }
    .btn { min-width: auto !important; }
    .table { font-size: 12px; }
    .table th, .table td { padding: 8px 12px; }
    .action-buttons {
        flex-direction: column;
        gap: 2px;
    }
    .action-buttons .btn {
        width: 100%;
        min-width: auto;
    }
    .table {
        table-layout: auto;
    }
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Barcode hover effect */
.barcode-image:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
</style>

<script>
function showBarcodeModal(itemCode, itemName) {
    const modal = document.getElementById('barcodeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBarcodeImage = document.getElementById('modalBarcodeImage');
    const modalBarcodeText = document.getElementById('modalBarcodeText');
    
    modalTitle.textContent = 'Barcode for ' + itemName;
    modalBarcodeImage.src = '{{ route("barcode.generate", ":itemCode") }}'.replace(':itemCode', itemCode);
    modalBarcodeText.textContent = itemCode;
    
    modal.style.display = 'block';
}

function closeBarcodeModal() {
    const modal = document.getElementById('barcodeModal');
    modal.style.display = 'none';
}

function printBarcode() {
    const modalBarcodeContainer = document.getElementById('modalBarcodeContainer');
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Barcode</title>
                <style>
                    body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
                    .barcode-container { text-align: center; padding: 20px; border: 1px solid #000; }
                    .barcode-image { max-width: 100%; height: auto; }
                    .barcode-text { margin-top: 10px; font-family: monospace; font-size: 18px; font-weight: 600; }
                </style>
            </head>
            <body>
                <div class="barcode-container">
                    <img src="${document.getElementById('modalBarcodeImage').src}" alt="Barcode" class="barcode-image">
                    <div class="barcode-text">${document.getElementById('modalBarcodeText').textContent}</div>
                </div>
            </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('barcodeModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeBarcodeModal();
    }
});
</script>
@endsection
