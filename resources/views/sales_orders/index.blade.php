@extends('layouts.app')
@section('content')
<style>
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
.wp-badge-success { background: #46b450; color: #fff; }
.wp-badge-danger { background: #dc3232; color: #fff; }
.wp-badge-warning { background: #ffb900; color: #23282d; }
.wp-badge-secondary { background: #e2e3e5; color: #23282d; }
.dropdown-item:hover {
    background-color: #f8f9fa !important;
}
.dropdown-item:last-child {
    border-bottom: none !important;
}
.main-content {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
body {
    padding-top: 70px !important;
    /* Adjust if your header height is different */
}
</style>
<div class="main-content">
    <div class="wp-header">
        <h1 class="wp-title">Sales Orders Management</h1>
        <a href="{{ route('sales-orders.create') }}" class="wp-btn wp-btn-primary">+ Create New Order</a>
    </div>
    <div style="background: #f8f9fa; border: 1px solid #ddd; padding: 16px; margin-bottom: 24px; border-radius: 0;">
        <h4 style="margin: 0 0 8px 0; color: #23282d;">Order Status Workflow:</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; font-size: 13px;">
            <div><strong>Pending Approval:</strong> Order created, waiting for admin approval</div>
            <div><strong>Ready for Pick & Pack:</strong> Approved, warehouse can prepare items</div>
            <div><strong>Completed:</strong> Order fulfilled and delivered</div>
            <div><strong>Cancelled:</strong> Order cancelled by admin</div>
        </div>
    </div>
    <div class="mb-3" style="display:flex;gap:10px;align-items:center;">
        <select name="status" id="statusFilter" style="padding:6px 12px;font-size:14px;border:1px solid #ddd;border-radius:0;">
            <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>All Status</option>
            <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ ($status ?? '') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="text" id="orderSearchInput" placeholder="Search orders..." class="form-control" style="width:250px;padding:8px 12px;border:1px solid #ddd;border-radius:0;font-size:14px;">
    </div>
    @if(session('success'))
        <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400" style="border-radius:0;">{{ session('success') }}</div>
    @endif
    <div class="wp-card" style="overflow-x:auto;max-height:600px;overflow-y:auto;">
        <table class="wp-table" id="ordersTable">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Pickup Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="order-row">
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->customer_name }}<br><small style="color:#888;">{{ $order->customer_email ?? 'No email' }}</small></td>
                        <td>
                            @if($order->order_date)
                                {{ \Carbon\Carbon::parse($order->order_date)->format('M j, Y') }}
                            @else
                                <span style="color: #666;">Not set</span>
                            @endif
                        </td>
                        <td>
                            @if($order->pickup_date)
                                {{ \Carbon\Carbon::parse($order->pickup_date)->format('M j, Y') }}
                            @else
                                <span style="color: #666;">Not set</span>
                            @endif
                        </td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="wp-badge wp-badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : ($order->status == 'processing' ? 'warning' : 'secondary')) }}">
                                @if($order->status == 'pending')
                                    Pending Approval
                                @elseif($order->status == 'processing')
                                    Ready for Pick & Pack
                                @elseif($order->status == 'completed')
                                    Completed
                                @elseif($order->status == 'cancelled')
                                    Cancelled
                                @else
                                    {{ ucfirst($order->status) }}
                                @endif
                            </span>
                        </td>
                        <td>{{ $order->user->full_name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $user = auth()->user();
                                $isAdmin = $user && in_array(strtolower($user->role), ['superadmin', 'admin']);
                                $isSuperAdmin = $user && strtolower($user->role) === 'superadmin';
                            @endphp
                            @if($isAdmin)
                                <div style="position:relative;display:inline-block;">
                                    <button type="button" class="wp-btn wp-btn-secondary dropdown-toggle" style="padding:6px 14px;font-size:13px;" onclick="toggleDropdown(this)">Actions &#x25BC;</button>
                                    <div class="dropdown-menu" style="display:none;position:absolute;z-index:1000;min-width:120px;background:#fff;border:1px solid #ddd;box-shadow:0 2px 8px rgba(0,0,0,0.08);border-radius:0;top:100%;left:0;">
                                        <a href="{{ route('sales-orders.show', $order) }}" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;text-decoration:none;color:#23282d;border-bottom:1px solid #f0f0f1;">View</a>
                                        @if($order->status == 'pending')
                                            <form action="{{ route('sales-orders.processOrder', $order) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;background:#136735;border:none;color:#fff;cursor:pointer;border-bottom:1px solid #f0f0f1;">Processing</button>
                                            </form>
                                            <form action="{{ route('sales-orders.cancelOrder', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;background:#ffb900;border:none;color:#23282d;cursor:pointer;border-bottom:1px solid #f0f0f1;">Cancel</button>
                                            </form>
                                            <form action="{{ route('sales-orders.deleteOrder', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;background:#dc3232;border:none;color:#fff;cursor:pointer;">Delete</button>
                                            </form>
                                        @endif
                                        @if($order->status == 'processing')
                                            <form action="{{ route('sales-orders.cancelOrder', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to cancel this processing order?')">
                                                @csrf
                                                <button type="submit" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;background:#ffb900;border:none;color:#23282d;cursor:pointer;border-bottom:1px solid #f0f0f1;">Cancel Processing</button>
                                            </form>
                                            <form action="{{ route('sales-orders.deleteOrder', $order) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this order? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item" style="display:block;width:100%;text-align:left;padding:8px 16px;background:#dc3232;border:none;color:#fff;cursor:pointer;">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('sales-orders.show', $order) }}" class="wp-btn wp-btn-secondary" style="padding:6px 14px;font-size:13px;">View</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr id="noOrdersRow">
                        <td colspan="8" style="text-align:center;color:#888;">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div id="customPagination" class="mt-4" style="display:flex;gap:4px;justify-content:flex-end;align-items:center;"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('statusFilter');
    const searchInput = document.getElementById('orderSearchInput');
    let currentPage = 1;
    const rowsPerPage = 10;

    function getAllRows() {
        // Only select actual data rows
        return Array.from(document.querySelectorAll('#ordersTable tbody tr.order-row'));
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
        // Show/hide the No Orders row
        const noOrdersRow = document.getElementById('noOrdersRow');
        if (allRows.length === 0) {
            if (noOrdersRow) noOrdersRow.style.display = '';
        } else {
            if (noOrdersRow) noOrdersRow.style.display = 'none';
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
        let selectedStatus = statusSelect.value;
        allRows.forEach(row => {
            let rowText = Array.from(row.querySelectorAll('td'))
                .map(td => td.textContent.replace(/\s+/g, ' ').trim().toLowerCase())
                .join(' ');
            let statusCell = row.querySelector('td:nth-child(6)');
            let statusText = statusCell ? statusCell.textContent.replace(/\s+/g, ' ').trim().toLowerCase() : '';
            let matchesStatus = (selectedStatus === 'all') || (statusText.includes(selectedStatus));
            let matchesSearch = rowText.includes(searchTerm);
            if (matchesStatus && matchesSearch) {
                row.classList.remove('filtered-out');
            } else {
                row.classList.add('filtered-out');
            }
        });
        renderTablePage(1);
    }

    statusSelect.addEventListener('change', filterAndRender);
    searchInput.addEventListener('input', filterAndRender);

    // Initial setup
    filterAndRender();

    // Dropdown functionality
    window.toggleDropdown = function(button) {
        const dropdown = button.nextElementSibling;
        const isVisible = dropdown.style.display === 'block';
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
        dropdown.style.display = isVisible ? 'none' : 'block';
    };
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown-toggle') && !event.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
});
</script>
@endsection
