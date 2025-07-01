@extends('layouts.app')
@section('content')
<style>
.wp-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-bottom: 16px;
}
.wp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
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
.wp-btn-success {
    background: #46b450;
    border-color: #46b450;
    color: #fff;
}
.wp-btn-success:hover {
    background: #3d8b46;
    border-color: #3d8b46;
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
.wp-badge-info { background: #0073aa; color: #fff; }
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.stat-card {
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 0;
    padding: 18px 14px;
    text-align: center;
    transition: all 0.15s;
}
.stat-card:hover {
    border-color: #136735;
    box-shadow: 0 2px 4px rgba(19, 103, 53, 0.1);
}
.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #136735;
    margin-bottom: 4px;
}
.stat-label {
    font-size: 1rem;
    color: #23282d;
    font-weight: 500;
}
.location-card {
    border: 1px solid #ddd;
    border-radius: 0;
    padding: 12px;
    margin-bottom: 8px;
    background: #fff;
    transition: all 0.15s;
}
.location-card:hover {
    border-color: #136735;
    box-shadow: 0 2px 4px rgba(19, 103, 53, 0.1);
}
.alert {
    padding: 10px 14px;
    margin-bottom: 12px;
    border: 1px solid transparent;
    border-radius: 0;
    font-size: 14px;
}
.alert-success {
    background: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}
.alert-danger {
    background: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
.alert-warning {
    background: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}
.quick-info {
    background: #f8f9fa;
    border: 1px solid #ddd;
    padding: 8px 12px;
    margin-bottom: 16px;
    border-radius: 0;
    font-size: 13px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
}
@media (max-width: 900px) { 
    .stats-grid { grid-template-columns: 1fr 1fr; } 
    .main-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) { 
    .stats-grid { grid-template-columns: 1fr; } 
}
</style>

<div class="main-content" style="margin-top: -20px;">
    <div class="wp-header">
        <h1 class="wp-title">Warehouse Management</h1>
        <div style="display: flex; gap: 12px;">
            <span class="wp-badge wp-badge-info">{{ $orders->where('status', 'processing')->count() }} Processing</span>
            <span class="wp-badge wp-badge-secondary">{{ $orders->where('status', 'cancelled')->count() }} Cancelled</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="quick-info">
        <span style="font-size: 16px;">📋</span>
        <span><strong>Quick Guide:</strong> Process orders marked "Ready for Pick & Pack". Check pickup dates and click "Complete Order" when packed. Cancelled orders are for reference only.</span>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" style="position:relative;">
                {{ $orders->where('status', 'processing')->count() }}
                @if($orders->where('status', 'processing')->count() > 0)
                    <span style="position:absolute;top:-8px;right:-18px;background:#46b450;color:#fff;font-size:11px;padding:2px 6px;border-radius:10px;font-weight:600;">NEW</span>
                @endif
            </div>
            <div class="stat-label">Orders to Process</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $locations->count() }}</div>
            <div class="stat-label">Storage Locations</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $low_stock_items->count() }}</div>
            <div class="stat-label">Low Stock Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($locations->sum('total_quantity')) }}</div>
            <div class="stat-label">Total Items in Stock</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;" class="main-grid">
        <div class="wp-card">
            <div style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 12px 16px;">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0; color: #23282d;">📦 Orders to Pick & Pack</h3>
            </div>
            <div style="padding: 16px;">
                @if($orders->isEmpty())
                    <div style="text-align: center; padding: 30px 16px; color: #666;">
                        <div style="font-size: 48px; margin-bottom: 16px;">📭</div>
                        <h4 style="margin: 0 0 8px 0; color: #23282d;">No Orders to Process</h4>
                        <p style="margin: 0; font-size: 14px;">All orders have been completed or there are no new orders to process.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table class="wp-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Pickup Date</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong style="color: #136735;">{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div style="font-weight: 500;">{{ $order->customer_name }}</div>
                                        @if($order->customer_email)
                                            <small style="color: #666;">{{ $order->customer_email }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->pickup_date)
                                            <div style="font-weight: 500;">{{ $order->pickup_date->format('M j, Y') }}</div>
                                            <small style="color: #666;">{{ $order->pickup_date->diffForHumans() }}</small>
                                        @else
                                            <div style="font-weight: 500; color: #666;">Not set</div>
                                            <small style="color: #999;">No pickup date</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="wp-badge wp-badge-secondary">{{ $order->order_items_count }} items</span>
                                    </td>
                                    <td>
                                        <strong>₱{{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($order->status == 'processing')
                                            <span class="wp-badge wp-badge-warning">Ready for Pick & Pack</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="wp-badge wp-badge-danger">Cancelled</span>
                                        @else
                                            <span class="wp-badge wp-badge-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                            <a href="{{ route('sales-orders.show', $order) }}" class="wp-btn wp-btn-secondary" style="padding:6px 12px;font-size:12px;">View</a>
                                            @if($order->status == 'processing')
                                                <form method="POST" action="{{ route('warehouse-management.completeOrder', $order) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="wp-btn wp-btn-success" style="padding:6px 12px;font-size:12px;" onclick="return confirm('Complete this order? This will reduce stock levels and mark the order as completed.')">
                                                        Complete Order
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="wp-card">
            <div style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 12px 16px;">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0; color: #23282d;">🏢 Storage Locations</h3>
            </div>
            <div style="padding: 16px;">
                @if($locations->isEmpty())
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <div style="font-size: 32px; margin-bottom: 12px;">🏪</div>
                        <p style="margin: 0; font-size: 14px;">No storage locations configured.</p>
                    </div>
                @else
                    @foreach($locations as $location)
                        <div class="location-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <strong style="color: #23282d;">{{ $location->location }}</strong>
                                    <br>
                                    <small style="color: #666;">{{ $location->item_count }} different items</small>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size: 20px; font-weight: 700; color: #136735; margin-bottom: 4px;">{{ number_format($location->total_quantity) }}</div>
                                    <small style="color: #666;">Total Units</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @if($low_stock_items->isNotEmpty())
    <div class="wp-card">
        <div style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 12px 16px;">
            <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0; color: #23282d;">⚠️ Low Stock Alert</h3>
        </div>
        <div style="padding: 16px;">
            <div style="overflow-x:auto;">
                <table class="wp-table">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($low_stock_items as $item)
                        <tr>
                            <td><strong>{{ $item->item_code }}</strong></td>
                            <td>{{ $item->item_name }}</td>
                            <td>
                                <span style="font-weight: 600; color: {{ $item->quantity == 0 ? '#dc3232' : '#ffb900' }};">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ $item->location ?? 'N/A' }}</td>
                            <td>
                                <span class="wp-badge wp-badge-{{ $item->quantity == 0 ? 'danger' : 'warning' }}">
                                    {{ $item->quantity == 0 ? 'Out of Stock' : 'Low Stock' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 