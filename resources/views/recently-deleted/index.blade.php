@extends('layouts.app')
{{-- @include('components.header') --}}

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin:0;">Recently Deleted</h1>
        <div style="display:flex;gap:0.75rem;">
            @if($stats['total_deleted'] > 0)
                <a href="{{ route('recently-deleted.restore-all') }}" 
                   class="wp-sharp-btn wp-sharp-btn-success"
                   onclick="return confirm('Are you sure you want to restore all items?')">
                    Restore All
                </a>
                <a href="{{ route('recently-deleted.destroy-all') }}" 
                   class="wp-sharp-btn wp-sharp-btn-danger"
                   onclick="return confirm('Are you sure you want to permanently delete ALL items? This action cannot be undone!')">
                    Delete All
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2;color:#dc2626;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;border:1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Overview -->
    <div class="wp-card" style="margin-bottom:1.5rem;">
        <div class="wp-card-header">
            <h3>Deletion Statistics</h3>
        </div>
        <div class="wp-card-body">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">🗑️</div>
                    <div class="stat-number">{{ $stats['total_deleted'] }}</div>
                    <div class="stat-label">Total Deleted</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">{{ $stats['users_deleted'] }}</div>
                    <div class="stat-label">Users</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">📦</div>
                    <div class="stat-number">{{ $stats['items_deleted'] }}</div>
                    <div class="stat-label">Items</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🛒</div>
                    <div class="stat-number">{{ $stats['orders_deleted'] }}</div>
                    <div class="stat-label">Sales Orders</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-number">{{ $stats['suppliers_deleted'] }}</div>
                    <div class="stat-label">Suppliers</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deleted Items List -->
    <div class="wp-card">
        <div class="wp-card-header">
            <h3>Deleted Items ({{ $stats['total_deleted'] }})</h3>
        </div>
        <div class="wp-card-body">
            @if($allDeletedItems->isEmpty())
                <div class="empty-state">
                    <span class="empty-icon">✅</span>
                    <p>No deleted items found</p>
                    <p class="empty-subtitle">All items are currently active in the system.</p>
                </div>
            @else
                <div class="table-container">
                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Deleted Info</th>
                                <th>Deleted Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allDeletedItems as $item)
                            <tr>
                                <td>
                                    <div class="item-info">
                                        <div class="item-icon">
                                            @switch($item['type'])
                                                @case('User')
                                                    👤
                                                    @break
                                                @case('Item')
                                                    📦
                                                    @break
                                                @case('Sales Order')
                                                    🛒
                                                    @break
                                                @case('Supplier')
                                                    🏢
                                                    @break
                                                @default
                                                    📄
                                            @endswitch
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name">{{ $item['name'] }}</div>
                                            <div class="item-id">ID: {{ $item['id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="type-badge type-{{ strtolower(str_replace(' ', '-', $item['type'])) }}">
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td>
                                    <div class="item-description">{{ $item['description'] }}</div>
                                </td>
                                <td>
                                    <div class="deletion-info">
                                        @if($item['deleted_by'])
                                            <div>By: {{ $item['deleted_by'] }}</div>
                                        @endif
                                        <div class="deletion-date">{{ $item['deleted_at']->format('M j, Y g:i A') }}</div>
                                        <div class="deletion-time">{{ $item['deleted_at']->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <form action="{{ route('recently-deleted.restore', ['type' => strtolower(str_replace(' ', '-', $item['type'])), 'id' => $item['id']]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="wp-sharp-btn wp-sharp-btn-success wp-sharp-btn-small"
                                                onclick="return confirm('Are you sure you want to restore this {{ strtolower($item['type']) }}?')">
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('recently-deleted.destroy', ['type' => strtolower(str_replace(' ', '-', $item['type'])), 'id' => $item['id']]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="wp-sharp-btn wp-sharp-btn-danger wp-sharp-btn-small"
                                                onclick="return confirm('Are you sure you want to permanently delete this {{ strtolower($item['type']) }}? This action cannot be undone!')">
                                                Delete
                                            </button>
                                        </form>
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
</div>

<style>
/* WordPress-inspired Card Styles */
.wp-card {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    overflow: hidden;
}

.wp-card-header {
    padding: 12px 16px;
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
}

.wp-card-header h3 {
    margin: 0;
    color: #1d2327;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
}

.wp-card-body {
    padding: 16px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.stat-item {
    text-align: center;
    padding: 16px;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
}

.stat-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.stat-number {
    font-size: 24px;
    font-weight: 600;
    color: #1d2327;
    margin-bottom: 4px;
}

.stat-label {
    color: #646970;
    font-size: 13px;
    line-height: 1.4;
}

/* Empty State */
.empty-state {
    text-align: center;
    color: #646970;
    padding: 32px;
}

.empty-icon {
    font-size: 48px;
    display: block;
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0 0 8px 0;
    font-size: 16px;
}

.empty-subtitle {
    font-size: 14px !important;
    color: #8c8f94 !important;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.wp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.wp-table th {
    background: #f6f7f7;
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #1d2327;
    border-bottom: 1px solid #c3c4c7;
    font-size: 13px;
    line-height: 1.4;
}

.wp-table td {
    padding: 16px;
    color: #646970;
    border-bottom: 1px solid #f0f0f1;
    vertical-align: top;
}

.wp-table .text-center {
    text-align: center;
}

/* Item Info */
.item-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-icon {
    font-size: 20px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
}

.item-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.item-name {
    font-weight: 500;
    color: #1d2327;
    font-size: 13px;
    line-height: 1.4;
}

.item-id {
    font-size: 11px;
    color: #646970;
    line-height: 1.4;
}

/* Type Badges */
.type-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 500;
    line-height: 1.4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.type-user {
    background: #e7f3ff;
    color: #0073aa;
}

.type-item {
    background: #f0f6fc;
    color: #007cba;
}

.type-sales-order {
    background: #fcf9e8;
    color: #996800;
}

.type-supplier {
    background: #f0f6fc;
    color: #007cba;
}

/* Item Description */
.item-description {
    font-size: 13px;
    color: #646970;
    line-height: 1.4;
}

/* Deletion Info */
.deletion-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.deletion-date {
    color: #1d2327;
    font-size: 13px;
    line-height: 1.4;
}

.deletion-time {
    font-size: 11px;
    color: #646970;
    line-height: 1.4;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
    flex-wrap: wrap;
}

/* WordPress Sharp Buttons */
.wp-sharp-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1px solid;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    line-height: 1.4;
    min-height: 30px;
}

.wp-sharp-btn-small {
    padding: 6px 12px;
    font-size: 11px;
    min-height: 24px;
}

.wp-sharp-btn-primary {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
}

.wp-sharp-btn-primary:hover {
    background: #135e96;
    border-color: #135e96;
    color: #fff;
}

.wp-sharp-btn-secondary {
    background: #f6f7f7;
    border-color: #8c8f94;
    color: #1d2327;
}

.wp-sharp-btn-secondary:hover {
    background: #f0f0f1;
    border-color: #646970;
    color: #1d2327;
}

.wp-sharp-btn-danger {
    background: #d63638;
    border-color: #d63638;
    color: #fff;
}

.wp-sharp-btn-danger:hover {
    background: #b32d2e;
    border-color: #b32d2e;
    color: #fff;
}

.wp-sharp-btn-warning {
    background: #dba617;
    border-color: #dba617;
    color: #fff;
}

.wp-sharp-btn-warning:hover {
    background: #c08a00;
    border-color: #c08a00;
    color: #fff;
}

.wp-sharp-btn-success {
    background: #00a32a;
    border-color: #00a32a;
    color: #fff;
}

.wp-sharp-btn-success:hover {
    background: #008a20;
    border-color: #008a20;
    color: #fff;
}
</style>
@endsection