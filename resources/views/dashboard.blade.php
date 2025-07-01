@extends('layouts.app')
{{-- @include('components.header') --}}

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin-bottom:2rem;">Dashboard Overview</h1>
    <div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-bottom:2rem;">
        <div class="stat-card" style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);transition:transform 0.2s;">
            <div class="stat-number" style="font-size:2rem;font-weight:600;color:#2563eb;margin-bottom:0.5rem;">{{ number_format($stats['total_items']) }}</div>
            <div class="stat-label" style="color:#6b7280;font-size:0.875rem;">Total Items</div>
        </div>
        <div class="stat-card" style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);transition:transform 0.2s;">
            <div class="stat-number" style="font-size:2rem;font-weight:600;color:#2563eb;margin-bottom:0.5rem;">{{ number_format($stats['low_stock']) }}</div>
            <div class="stat-label" style="color:#6b7280;font-size:0.875rem;">Low Stock Items</div>
        </div>
        <div class="stat-card" style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);transition:transform 0.2s;">
            <div class="stat-number" style="font-size:2rem;font-weight:600;color:#2563eb;margin-bottom:0.5rem;">{{ number_format($stats['total_suppliers']) }}</div>
            <div class="stat-label" style="color:#6b7280;font-size:0.875rem;">Active Suppliers</div>
        </div>
        <div class="stat-card" style="background:white;padding:1.5rem;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);transition:transform 0.2s;">
            <div class="stat-number" style="font-size:2rem;font-weight:600;color:#2563eb;margin-bottom:0.5rem;">{{ number_format($stats['today_movements']) }}</div>
            <div class="stat-label" style="color:#6b7280;font-size:0.875rem;">Today's Movements</div>
        </div>
    </div>
    <div class="dashboard-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">
        <!-- Recent Stock Movements -->
        <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);margin-bottom:1.5rem;overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
                <h3 class="card-title" style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Recent Stock Movements</h3>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                @if($recent_movements->isEmpty())
                    <div class="text-center" style="color:#6b7280;">No recent movements found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table" style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Item</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Type</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Quantity</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">User</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_movements as $movement)
                                    <tr>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $movement->item->item_name ?? '-' }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                            <span class="badge"
                                                style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                                    background:{{ $movement->movement_type == 'inbound' ? '#dcfce7' : ($movement->movement_type == 'outbound' ? '#fef3c7' : '#f3f4f6') }};
                                                    color:{{ $movement->movement_type == 'inbound' ? '#166534' : ($movement->movement_type == 'outbound' ? '#92400e' : '#4b5563') }};">
                                                {{ ucfirst($movement->movement_type) }}
                                            </span>
                                        </td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $movement->quantity_changed }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $movement->user->full_name ?? '-' }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                            @if($movement->created_at)
                                                {{ \Carbon\Carbon::parse($movement->created_at)->format('M j, Y') }}
                                            @else
                                                <span style="color: #999;">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <!-- Low Stock Alert -->
        <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);margin-bottom:1.5rem;overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
                <h3 class="card-title" style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Low Stock Alert</h3>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                @if($low_stock_items->isEmpty())
                    <div class="text-center" style="color:#6b7280;">All items are well stocked!</div>
                @else
                    <div class="table-responsive">
                        <table class="table" style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Item</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Current Stock</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Unit</th>
                                    <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($low_stock_items as $item)
                                    <tr>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $item->item_name }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $item->quantity }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">{{ $item->unit }}</td>
                                        <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                            <span class="badge"
                                                style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                                    background:{{ $item->quantity == 0 ? '#fee2e2' : '#fef3c7' }};
                                                    color:{{ $item->quantity == 0 ? '#dc2626' : '#92400e' }};">
                                                {{ $item->quantity == 0 ? 'Out of Stock' : 'Low Stock' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
