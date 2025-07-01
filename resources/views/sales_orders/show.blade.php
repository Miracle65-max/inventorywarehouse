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
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}
.info-card {
    background: #f8f9fa;
    padding: 16px;
    border: 1px solid #ddd;
    border-radius: 0;
}
.info-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #666;
    font-weight: 600;
    margin-bottom: 4px;
}
.info-value {
    font-size: 14px;
    color: #23282d;
    font-weight: 500;
}
</style>

<div class="main-content">
    <div class="wp-header">
        <h1 class="wp-title">Order Details</h1>
        <a href="{{ route('sales-orders.index') }}" class="wp-btn wp-btn-secondary">← Back to Orders</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mb-4" style="border-radius:0;">{{ session('success') }}</div>
    @endif

    <div class="wp-card">
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Order Number</div>
                <div class="info-value">{{ $sales_order->order_number }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="wp-badge wp-badge-{{ $sales_order->status == 'completed' ? 'success' : ($sales_order->status == 'cancelled' ? 'danger' : ($sales_order->status == 'processing' ? 'warning' : 'secondary')) }}">
                        {{ ucfirst($sales_order->status) }}
                    </span>
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">Order Date</div>
                <div class="info-value">
                    @if($sales_order->order_date)
                        {{ \Carbon\Carbon::parse($sales_order->order_date)->format('M j, Y') }}
                    @else
                        <span style="color: #666;">Not set</span>
                    @endif
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">Pickup/Delivery Date</div>
                <div class="info-value">
                    @if($sales_order->pickup_date)
                        {{ \Carbon\Carbon::parse($sales_order->pickup_date)->format('M j, Y') }}
                    @else
                        <span style="color: #666;">Not set</span>
                    @endif
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">Total Amount</div>
                <div class="info-value">₱{{ number_format($sales_order->total_amount, 2) }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Customer Name</div>
                <div class="info-value">{{ $sales_order->customer_name }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Customer Email</div>
                <div class="info-value">{{ $sales_order->customer_email ?? 'Not provided' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Customer Phone</div>
                <div class="info-value">{{ $sales_order->customer_phone ?? 'Not provided' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Created By</div>
                <div class="info-value">{{ $sales_order->user->full_name ?? 'N/A' }}</div>
            </div>
        </div>

        @if($sales_order->customer_address)
        <div class="info-card" style="margin-bottom: 24px;">
            <div class="info-label">Customer Address</div>
            <div class="info-value">{{ $sales_order->customer_address }}</div>
        </div>
        @endif

        @if($sales_order->notes)
        <div class="info-card" style="margin-bottom: 24px;">
            <div class="info-label">Notes</div>
            <div class="info-value">{{ $sales_order->notes }}</div>
        </div>
        @endif
    </div>

    <div class="wp-card">
        <h2 style="margin: 0 0 20px 0; font-size: 1.5rem; color: #23282d;">Order Items</h2>
        <div style="overflow-x:auto;">
            <table class="wp-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Item Code</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales_order->orderItems as $orderItem)
                        <tr>
                            <td>{{ $orderItem->item->item_name ?? 'Item not found' }}</td>
                            <td>{{ $orderItem->item->item_code ?? 'N/A' }}</td>
                            <td>{{ $orderItem->quantity }}</td>
                            <td>₱{{ number_format($orderItem->unit_price, 2) }}</td>
                            <td>₱{{ number_format($orderItem->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#888;">No items found for this order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 