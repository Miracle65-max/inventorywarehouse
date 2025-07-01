<style>
    /* WordPress-like Minimalist Design */
    .wp-reports-container {
        background: #f1f1f1;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        width: 100%;
        box-sizing: border-box;
    }

    .wp-reports-header {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 0;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }

    .wp-reports-title {
        font-size: 23px;
        font-weight: 400;
        margin: 0;
        padding: 9px 0 4px 0;
        line-height: 1.3;
        color: #23282d;
    }

    .wp-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 0;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }

    .wp-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 15px 20px;
        font-weight: 600;
        color: #23282d;
        font-size: 14px;
        line-height: 1.4;
    }

    .wp-card-body {
        padding: 20px;
    }

    .wp-form-row {
        display: flex;
        gap: 20px;
        align-items: flex-end;
        margin-bottom: 20px;
    }

    .wp-form-group {
        flex: 1;
    }

    .wp-form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #23282d;
        font-size: 13px;
    }

    .wp-form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 0;
        font-size: 14px;
        line-height: 1.4;
        background: #fff;
        color: #23282d;
        transition: border-color 0.15s ease-in-out;
    }

    .wp-form-control:focus {
        outline: none;
        border-color: #136735;
        box-shadow: 0 0 0 1px #136735;
    }

    .wp-form-control:focus-visible {
        outline: 2px solid #136735;
        outline-offset: 2px;
    }

    .wp-button-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .wp-button {
        display: inline-block;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.4;
        text-align: center;
        text-decoration: none;
        border: 1px solid transparent;
        border-radius: 0;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        background: #fff;
        color: #23282d;
        min-height: 30px;
        box-shadow: 0 1px 0 #ccc;
    }

    .wp-button:hover {
        background: #f6f7f7;
        border-color: #0073aa;
        color: #0073aa;
    }

    .wp-button:active {
        background: #e5e5e5;
        border-color: #0073aa;
        box-shadow: inset 0 1px 0 #ccc;
    }

    .wp-button-primary {
        background: #136735;
        border-color: #136735;
        color: #fff;
        box-shadow: 0 1px 0 #0f4f28;
    }

    .wp-button-primary:hover {
        background: #0f4f28;
        border-color: #0f4f28;
        color: #fff;
    }

    .wp-button-secondary {
        background: #3c343c;
        border-color: #3c343c;
        color: #fff;
        box-shadow: 0 1px 0 #2a2a2a;
    }

    .wp-button-secondary:hover {
        background: #2a2a2a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .wp-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        font-size: 13px;
        line-height: 1.4;
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
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .wp-badge-success {
        background: #46b450;
        color: #fff;
    }

    .wp-badge-danger {
        background: #dc3232;
        color: #fff;
    }

    .wp-badge-warning {
        background: #ffb900;
        color: #23282d;
    }

    .wp-badge-secondary {
        background: #e2e3e5;
        color: #23282d;
    }

    .wp-summary {
        background: #f8f9fa;
        border-top: 1px solid #ddd;
        padding: 15px 20px;
        font-size: 13px;
        color: #23282d;
        font-weight: 600;
    }

    .wp-no-data {
        text-align: center;
        padding: 40px 20px;
        color: #666;
        font-style: italic;
    }

    .wp-report-meta {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        font-weight: normal;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .wp-reports-container {
            padding: 15px;
        }
        
        .wp-form-row {
            flex-direction: column;
            gap: 15px;
        }
        
        .wp-button-group {
            justify-content: flex-start;
        }
        
        .wp-table {
            font-size: 12px;
        }
        
        .wp-table th,
        .wp-table td {
            padding: 8px 10px;
        }
    }

    /* Print Styles */
    @media print {
        .wp-reports-container {
            background: #fff;
            padding: 0;
        }
        
        .wp-card {
            border: none;
            box-shadow: none;
        }
        
        .wp-button-group {
            display: none;
        }
    }
</style>

@extends('layouts.app')
@section('content')
<div class="main-content">
    <div class="wp-reports-container">
        <div class="wp-reports-header">
            <h1 class="wp-reports-title">Reports & Analytics</h1>
        </div>
        
        <div class="wp-card">
            <div class="wp-card-header">
                Generate Report
            </div>
            <div class="wp-card-body">
                <form method="GET">
                    <div class="wp-form-row">
                        <div class="wp-form-group">
                            <label class="wp-form-label">Report Type</label>
                            <select name="type" class="wp-form-control" onchange="toggleDateFields(this.value)">
                                <option value="inventory" @if($report_type=='inventory') selected @endif>Inventory Report</option>
                                <option value="low_stock" @if($report_type=='low_stock') selected @endif>Low Stock Report</option>
                                <option value="stock_movements" @if($report_type=='stock_movements') selected @endif>Stock Movements Report</option>
                                <option value="sales" @if($report_type=='sales') selected @endif>Sales Report</option>
                                <option value="suppliers" @if($report_type=='suppliers') selected @endif>Suppliers Report</option>
                            </select>
                        </div>
                        
                        <div class="wp-form-group" id="dateFields" style="@if(!in_array($report_type, ['stock_movements','sales']))display:none;@endif">
                            <div class="wp-form-row" style="margin-bottom: 0;">
                                <div class="wp-form-group">
                                    <label class="wp-form-label">Date From</label>
                                    <input type="date" name="date_from" class="wp-form-control" value="{{ $date_from }}">
                                </div>
                                <div class="wp-form-group">
                                    <label class="wp-form-label">Date To</label>
                                    <input type="date" name="date_to" class="wp-form-control" value="{{ $date_to }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="wp-button-group">
                            <button type="submit" class="wp-button wp-button-primary">Generate Report</button>
                            <button type="button" class="wp-button wp-button-secondary" onclick="printReport()">Print Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="wp-card" id="reportContent">
            <div class="wp-card-header">
                {{ $report_title }}
                <div class="wp-report-meta">
                    Generated on: {{ now()->format('F j, Y g:i A') }}
                    @if(in_array($report_type, ['stock_movements','sales']))
                        | Period: {{ \Carbon\Carbon::parse($date_from)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($date_to)->format('M j, Y') }}
                    @endif
                </div>
            </div>
            
            @if(count($report_data) == 0)
                <div class="wp-no-data">
                    No data found for the selected criteria.
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="wp-table">
                        <thead>
                            <tr>
                                @if($report_type == 'inventory')
                                    <th>Item Code</th><th>Item Name</th><th>Category</th><th>Quantity</th><th>Unit</th><th>Cost Price</th><th>Total Value</th><th>Supplier</th><th>Location</th>
                                @elseif($report_type == 'low_stock')
                                    <th>Item Code</th><th>Item Name</th><th>Current Stock</th><th>Unit</th><th>Supplier</th><th>Location</th><th>Status</th>
                                @elseif($report_type == 'stock_movements')
                                    <th>Date/Time</th><th>Item</th><th>Movement Type</th><th>Quantity Changed</th><th>New Total</th><th>User</th><th>Remarks</th>
                                @elseif($report_type == 'sales')
                                    <th>Order Number</th><th>Customer</th><th>Order Date</th><th>Total Amount</th><th>Status</th><th>Created By</th>
                                @elseif($report_type == 'suppliers')
                                    <th>Supplier Name</th><th>Contact Person</th><th>Contact Info</th><th>Items Count</th><th>Total Value</th><th>Status</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($report_data as $row)
                            <tr>
                                @if($report_type == 'inventory')
                                    <td>{{ $row->item_code }}</td>
                                    <td>{{ $row->item_name }}</td>
                                    <td>{{ ucfirst($row->category) }}</td>
                                    <td>{{ $row->quantity }}</td>
                                    <td>{{ $row->unit }}</td>
                                    <td>₱{{ number_format($row->cost_price, 2) }}</td>
                                    <td>₱{{ number_format($row->total_value, 2) }}</td>
                                    <td>{{ $row->supplier_name ?? 'N/A' }}</td>
                                    <td>{{ $row->location ?? 'N/A' }}</td>
                                @elseif($report_type == 'low_stock')
                                    <td>{{ $row->item_code }}</td>
                                    <td>{{ $row->item_name }}</td>
                                    <td>{{ $row->quantity }}</td>
                                    <td>{{ $row->unit }}</td>
                                    <td>{{ $row->supplier_name ?? 'N/A' }}</td>
                                    <td>{{ $row->location ?? 'N/A' }}</td>
                                    <td>
                                        <span class="wp-badge wp-badge-{{ $row->quantity == 0 ? 'danger' : 'warning' }}">
                                            {{ $row->quantity == 0 ? 'Out of Stock' : 'Low Stock' }}
                                        </span>
                                    </td>
                                @elseif($report_type == 'stock_movements')
                                    <td>{{ \Carbon\Carbon::parse($row->timestamp)->format('M j, Y g:i A') }}</td>
                                    <td>{{ $row->item_code }} - {{ $row->item_name }}</td>
                                    <td>
                                        <span class="wp-badge wp-badge-{{ $row->movement_type == 'inbound' ? 'success' : ($row->movement_type == 'outbound' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($row->movement_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->quantity_changed }}</td>
                                    <td>{{ $row->new_total_quantity }}</td>
                                    <td>{{ $row->full_name }}</td>
                                    <td>{{ $row->remarks ?? '-' }}</td>
                                @elseif($report_type == 'sales')
                                    <td>{{ $row->order_number }}</td>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>
                                        @if($row->order_date)
                                            {{ \Carbon\Carbon::parse($row->order_date)->format('M j, Y') }}
                                        @else
                                            <span style="color: #666;">Not set</span>
                                        @endif
                                    </td>
                                    <td>₱{{ number_format($row->total_amount, 2) }}</td>
                                    <td>
                                        <span class="wp-badge wp-badge-{{ $row->status == 'completed' ? 'success' : ($row->status == 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->created_by_name }}</td>
                                @elseif($report_type == 'suppliers')
                                    <td>{{ $row->supplier_name }}</td>
                                    <td>{{ $row->contact_person ?? 'N/A' }}</td>
                                    <td>
                                        @if($row->contact_number)
                                            📞 {{ $row->contact_number }}<br>
                                        @endif
                                        @if($row->email)
                                            ✉️ {{ $row->email }}
                                        @endif
                                    </td>
                                    <td>{{ $row->item_count }}</td>
                                    <td>₱{{ number_format($row->total_value ?? 0, 2) }}</td>
                                    <td>
                                        <span class="wp-badge wp-badge-{{ $row->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="wp-summary">
                    @if($report_type == 'inventory')
                        Total Items: {{ count($report_data) }} | Total Value: ₱{{ number_format($report_data->sum('total_value'), 2) }}
                    @elseif($report_type == 'low_stock')
                        Low Stock Items: {{ count($report_data) }}
                    @elseif($report_type == 'stock_movements')
                        Total Movements: {{ count($report_data) }}
                    @elseif($report_type == 'sales')
                        Total Orders: {{ count($report_data) }} | Total Sales: ₱{{ number_format($report_data->sum('total_amount'), 2) }}
                    @elseif($report_type == 'suppliers')
                        Total Suppliers: {{ count($report_data) }}
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleDateFields(reportType) {
    const dateFields = document.getElementById('dateFields');
    if (reportType === 'stock_movements' || reportType === 'sales') {
        dateFields.style.display = 'block';
    } else {
        dateFields.style.display = 'none';
    }
}

function printReport() {
    const printContent = document.getElementById('reportContent').innerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = `
        <div style="padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #136735; padding-bottom: 20px;">
                <h1 style="color: #136735; margin: 0;">SBT Constructions</h1>
                <p style="color: #666; margin: 5px 0 0 0;">Warehouse Inventory System</p>
            </div>
            ${printContent}
        </div>
    `;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}
</script>
@endsection
