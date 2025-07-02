@extends('layouts.app')
@section('content')
{{--
  NOTE: All global design tokens and styles should be in resources/css/app.css and loaded in your layout.
  Only keep page-specific styles here if absolutely necessary.
--}}
<style>
    body, .main-content {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f6f7f7;
        color: #23282d;
    }
    .main-content {
        padding: 24px 0;
    }
    .wp-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 0;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        margin-bottom: 24px;
    }
    .wp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .wp-title {
        font-size: 2rem;
        font-weight: 400;
        color: #23282d;
        margin: 0;
    }
    .wp-btn {
        display: inline-block;
        padding: 8px 18px;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid transparent;
        border-radius: 0;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        box-shadow: 0 1px 0 #ccc;
    }
    .wp-btn-primary {
        background: #136735;
        border-color: #136735;
        color: #fff;
    }
    .wp-btn-primary:hover {
        background: #0f4f28;
        border-color: #0f4f28;
        color: #fff;
    }
    .wp-btn-secondary {
        background: #3c343c;
        border-color: #3c343c;
        color: #fff;
    }
    .wp-btn-secondary:hover {
        background: #23282d;
        border-color: #23282d;
        color: #fff;
    }
    .wp-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        font-size: 14px;
    }
    .wp-table th {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #23282d;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .wp-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f1;
        vertical-align: middle;
        color: #23282d;
    }
    .wp-table tbody tr:hover {
        background: #f6f7f7;
    }
    .wp-badge {
        display: inline-block;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .wp-badge-secondary { background: #e2e3e5; color: #23282d; }
    .pagination { justify-content: flex-end; }
    .pagination .page-link { 
        border-radius: 0; 
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
    .pagination .page-item.active .budgets .page-link { 
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
    .main-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    body {
        padding-top: 70px !important;
        /* Adjust if your header height is different */
    }
    @media (max-width: 768px) {
        .wp-header { flex-direction: column; align-items: stretch; gap: 10px; }
        .wp-title { font-size: 1.3rem; }
        .wp-btn { width: 100%; min-width: auto !important; }
        .wp-card { font-size: 12px; }
        .wp-table th, .wp-table td { padding: 8px 12px; }
        .action-buttons { flex-direction: column; gap: 2px; }
        .action-buttons .wp-btn { width: 100%; min-width: auto; }
        .wp-table { font-size: 12px; }
    }
    .success-message {
        position: relative;
        padding: 12px 15px;
        margin-bottom: 24px;
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 0;
        opacity: 1;
        transition: opacity 0.3s ease;
    }
    .success-message.fade {
        opacity: 0;
    }
</style>
<div class="main-content">
    <div class="wp-header">
        <h1 class="wp-title">Stock Movements</h1>
        @if(auth()->user() && in_array(auth()->user()->role, ['super_admin', 'admin']))
            <button type="button" class="wp-btn wp-btn-primary" onclick="toggleMovementForm()">Record Movement</button>
        @endif
    </div>
    @if(auth()->user() && in_array(auth()->user()->role, ['super_admin', 'admin']))
    <div class="wp-card" id="movementForm" style="display: none; margin-top: 24px;">
        <div class="card-header">
            <h3 class="card-title">Record Stock Movement</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('stock-movements.store') }}" onsubmit="return validateForm(event)">
                @csrf
                <div class="form-group">
                    <label for="item_id" class="form-label">Item</label>
                    <select name="item_id" id="item_id" class="form-control" required onchange="updateItemInfo(this)">
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                        <option value="{{ $item->item_id }}" data-quantity="{{ $item->quantity }}" data-unit="{{ $item->unit }}">{{ $item->item_code }} - {{ $item->item_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="current_stock" class="form-label">Current Stock: <span id="currentStockDisplay" style="font-weight: bold; color: #136735;">-</span></label>
                </div>
                <div class="form-group">
                    <label for="movement_type" class="form-label">Movement Type</label>
                    <select name="movement_type" id="movement_type" class="form-control" required disabled onchange="handleMovementTypeChange(this)">
                        <option value="">Select Type</option>
                        <option value="inbound">Inbound</option>
                        <option value="outbound">Outbound</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="defective">Defective</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity_changed" class="form-label">Quantity</label>
                    <input type="number" name="quantity_changed" id="quantity_changed" class="form-control" min="1" step="1" required disabled>
                </div>
                <div id="borrowingDetails" style="display: none;">
                    <div class="form-group">
                        <label for="borrower_name" class="form-label">Borrower Name</label>
                        <input type="text" name="borrower_name" id="borrower_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="source_warehouse" class="form-label">Source Warehouse</label>
                        <input type="text" name="source_warehouse" id="source_warehouse" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="expected_return_date" class="form-label">Expected Return Date</label>
                        <input type="date" name="expected_return_date" id="expected_return_date" class="form-control">
                    </div>
                </div>
                <div id="defectiveDetails" style="display: none;">
                    <div class="form-group">
                        <label for="defect_reason" class="form-label">Defect Reason</label>
                        <textarea name="defect_reason" id="defect_reason" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="wp-btn wp-btn-primary">Submit</button>
            </form>
        </div>
    </div>
    @endif
    <div class="mb-3" style="display:flex;gap:10px;align-items:center;">
        <select name="movement_type" id="movementTypeFilter" style="padding:6px 12px;font-size:14px;border:1px solid #ddd;border-radius:0;">
            <option value="all">All Types</option>
            <option value="inbound">Inbound</option>
            <option value="outbound">Outbound</option>
            <option value="borrowed">Borrowed</option>
            <option value="defective">Defective</option>
        </select>
        <input type="text" id="movementSearchInput" placeholder="Search movements..." class="form-control" style="width:250px;padding:8px 12px;border:1px solid #ddd;border-radius:0;font-size:14px;">
    </div>
    @if(session('success'))
        <div id="success-message" class="success-message">
            {{ session('success') }}
        </div>
        <script>
            // Auto-dismiss the success message after 3 seconds
            setTimeout(function() {
                let alert = document.getElementById('success-message');
                if (alert) {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 3000);
        </script>
    @endif
    <div class="wp-card" style="overflow-x:auto;max-height:600px;overflow-y:auto;">
        <table class="wp-table" id="movementsTable">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Item</th>
                    <th>Movement Type</th>
                    <th>Quantity</th>
                    <th>User</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr class="movement-row">
                        <td>
                            @php
                                $formatted = null;
                                try {
                                    if ($movement->created_at) {
                                        $formatted = \Carbon\Carbon::parse($movement->created_at)->format('Y-m-d H:i');
                                    }
                                } catch (Exception $e) {
                                    $formatted = null;
                                }
                            @endphp
                            {{ $formatted ?? 'N/A' }}
                        </td>
                        <td>
                            @if($movement->item)
                                {{ $movement->item->item_code }} - {{ $movement->item->item_name }}
                            @else
                                <span style="color:#dc3232;">Item not found</span>
                            @endif
                        </td>
                        <td><span class="wp-badge wp-badge-secondary">{{ ucfirst($movement->movement_type) }}</span></td>
                        <td>{{ $movement->quantity_changed }}</td>
                        <td>
                            @if($movement->user)
                                {{ $movement->user->username ?? $movement->user->name }}
                            @else
                                <span style="color:#dc3232;">N/A</span>
                            @endif
                        </td>
                        <td>{{ $movement->remarks }}</td>
                        <td>
                            <div class="action-buttons">
                                {{-- No action buttons since no routes are defined --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="noMovementsRow">
                        <td colspan="7" style="text-align:center;color:#888;">No stock movements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="customPagination" class="mt-4" style="display:flex;gap:4px;justify-content:flex-end;align-items:center;"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const movementTypeSelect = document.getElementById('movementTypeFilter');
    const searchInput = document.getElementById('movementSearchInput');
    let currentPage = 1;
    const rowsPerPage = 10;

    function getAllRows() {
        // Only select actual data rows
        return Array.from(document.querySelectorAll('#movementsTable tbody tr.movement-row'));
    }

    function renderTablePage(page) {
        let allRows = getAllRows().filter(row => !row.classList.contains('filtered-out'));
        let totalPages = Math.max(1, Math.ceil(allRows.length / rowsPerPage));
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;
        currentPage = page;
        getAllRows().forEach(row => row.style.display = 'none');
        allRows.forEach((row, idx) => {
            row.style.display = (idx >= (page-1)*rowsPerPage && idx < page*rowsPerPage) ? '' : 'none';
        });
        // Show/hide the No Movements row
        const noMovementsRow = document.getElementById('noMovementsRow');
        if (allRows.length === 0) {
            if (noMovementsRow) noMovementsRow.style.display = '';
        } else {
            if (noMovementsRow) noMovementsRow.style.display = 'none';
        }
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        let pagDiv = document.getElementById('customPagination');
        let html = '';
        if (totalPages > 1) {
            html += `<button onclick=\"gotoPage(${currentPage-1})\" class=\"wp-btn wp-btn-secondary\" style=\"padding:6px 14px;font-size:13px;${currentPage==1?'opacity:0.5;pointer-events:none;':''}\">Prev</button>`;
            for (let i=1; i<=totalPages; i++) {
                html += `<button onclick=\"gotoPage(${i})\" class=\"wp-btn ${i==currentPage?'wp-btn-primary':'wp-btn-secondary'}\" style=\"padding:6px 14px;font-size:13px;${i==currentPage?'font-weight:bold;':''}\">${i}</button>`;
            }
            html += `<button onclick=\"gotoPage(${currentPage+1})\" class=\"wp-btn wp-btn-secondary\" style=\"padding:6px 14px;font-size:13px;${currentPage==totalPages?'opacity:0.5;pointer-events:none;':''}\">Next</button>`;
        }
        pagDiv.innerHTML = html;
    }

    window.gotoPage = function(page) {
        renderTablePage(page);
    };

    function filterAndRender() {
        let allRows = getAllRows();
        let searchTerm = searchInput.value.trim().toLowerCase();
        let selectedType = movementTypeSelect.value;
        allRows.forEach(row => {
            let rowText = Array.from(row.querySelectorAll('td'))
                .map(td => td.textContent.replace(/\s+/g, ' ').trim().toLowerCase())
                .join(' ');
            let typeCell = row.querySelector('td:nth-child(3)');
            let typeText = typeCell ? typeCell.textContent.replace(/\s+/g, ' ').trim().toLowerCase() : '';
            let matchesType = (selectedType === 'all') || (typeText.includes(selectedType));
            let matchesSearch = rowText.includes(searchTerm);
            if (matchesType && matchesSearch) {
                row.classList.remove('filtered-out');
            } else {
                row.classList.add('filtered-out');
            }
        });
        renderTablePage(1);
    }

    movementTypeSelect.addEventListener('change', filterAndRender);
    searchInput.addEventListener('input', filterAndRender);

    // Initial setup
    filterAndRender();
});

function toggleMovementForm() {
    const form = document.getElementById('movementForm');
    if (!form) return;
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    if (form.style.display === 'block') {
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
function updateItemInfo(select) {
    const option = select.options[select.selectedIndex];
    const currentStockDisplay = document.getElementById('currentStockDisplay');
    const movementType = document.getElementById('movement_type');
    const quantityInput = document.getElementById('quantity_changed');
    if (option.value) {
        // Fetch current stock via AJAX for real-time accuracy
        fetch(`/items/${option.value}/current-stock`)
            .then(response => response.json())
            .then(data => {
                if (data.quantity !== undefined && data.unit !== undefined) {
                    currentStockDisplay.textContent = data.quantity + ' ' + data.unit;
                } else {
                    currentStockDisplay.textContent = 'N/A';
                }
            })
            .catch(() => {
                currentStockDisplay.textContent = 'N/A';
            });
        movementType.disabled = false;
        quantityInput.disabled = false;
    } else {
        currentStockDisplay.textContent = '-';
        movementType.disabled = true;
        quantityInput.disabled = true;
    }
    clearErrors();
}
function handleMovementTypeChange(select) {
    const borrowingDetails = document.getElementById('borrowingDetails');
    const defectiveDetails = document.getElementById('defectiveDetails');
    if (borrowingDetails) borrowingDetails.style.display = 'none';
    if (defectiveDetails) defectiveDetails.style.display = 'none';
    const borrowerName = document.getElementById('borrower_name');
    const sourceWarehouse = document.getElementById('source_warehouse');
    const expectedReturnDate = document.getElementById('expected_return_date');
    const defectReason = document.getElementById('defect_reason');
    if (borrowerName) borrowerName.required = false;
    if (sourceWarehouse) sourceWarehouse.required = false;
    if (expectedReturnDate) expectedReturnDate.required = false;
    if (defectReason) defectReason.required = false;
    switch(select.value) {
        case 'borrowed':
            if (borrowingDetails) borrowingDetails.style.display = 'block';
            if (borrowerName) borrowerName.required = true;
            if (sourceWarehouse) sourceWarehouse.required = true;
            if (expectedReturnDate) expectedReturnDate.required = true;
            break;
        case 'defective':
            if (defectiveDetails) defectiveDetails.style.display = 'block';
            if (defectReason) defectReason.required = true;
            break;
    }
    clearErrors();
}
function validateForm(event) {
    event.preventDefault();
    clearErrors();
    let isValid = true;
    const itemSelect = document.getElementById('item_id');
    const movementType = document.getElementById('movement_type');
    const quantityInput = document.getElementById('quantity_changed');
    if (!itemSelect.value) {
        showError(itemSelect, 'Please select an item');
        isValid = false;
    }
    if (!movementType.value) {
        showError(movementType, 'Please select a movement type');
        isValid = false;
    }
    const quantity = parseInt(quantityInput.value);
    if (!quantity || quantity <= 0) {
        showError(quantityInput, 'Please enter a valid quantity');
        isValid = false;
    }
    if (movementType.value === 'borrowed') {
        const borrowerName = document.getElementById('borrower_name');
        const sourceWarehouse = document.getElementById('source_warehouse');
        const expectedReturnDate = document.getElementById('expected_return_date');
        if (!borrowerName.value.trim()) {
            showError(borrowerName, 'Please enter the borrower name');
            isValid = false;
        }
        if (!sourceWarehouse.value.trim()) {
            showError(sourceWarehouse, 'Please enter the source warehouse');
            isValid = false;
        }
        if (!expectedReturnDate.value) {
            showError(expectedReturnDate, 'Please select an expected return date');
            isValid = false;
        } else {
            const returnDate = new Date(expectedReturnDate.value);
            const today = new Date();
            if (returnDate <= today) {
                showError(expectedReturnDate, 'Expected return date must be in the future');
                isValid = false;
            }
        }
    }
    if (movementType.value === 'defective') {
        const defectReason = document.getElementById('defect_reason');
        if (!defectReason.value.trim()) {
            showError(defectReason, 'Please enter the defect reason');
            isValid = false;
        }
    }
    if (isValid) {
        event.target.submit();
    }
    return false;
}
function showError(element, message) {
    element.classList.add('is-invalid');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    element.parentNode.appendChild(errorDiv);
}
function clearError(element) {
    element.classList.remove('is-invalid');
    const errorMessage = element.parentNode.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}
function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(element => {
        clearError(element);
    });
    document.querySelectorAll('.error-message').forEach(el => el.remove());
}
document.addEventListener('DOMContentLoaded', function() {
    // Responsive: show/hide sidebar on mobile
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    if (window.innerWidth < 768 && sidebar) {
        sidebar.classList.remove('active');
        mainContent.style.marginLeft = '0';
    }
    // Form events
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', validateForm);
        const movementType = document.getElementById('movement_type');
        if (movementType) {
            movementType.addEventListener('change', function() {
                handleMovementTypeChange(this);
            });
        }
    }
});
</script>
@endsection