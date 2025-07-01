@extends('layouts.app')

@section('title', 'Audit Trails')

@section('content')
<div class="max-w-6xl mx-auto main-content">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Audit Trails & Activity Logs</h1>
        <button onclick="window.print();" class="px-4 py-2 rounded print-btn hover:bg-[#0f522a] hover:border-[#0f522a] transition shadow-sm border" style="background:#136735!important;border-color:#136735!important;color:#fff!important;">Print Log</button>
    </div>
    <!-- Filters -->
    <form method="GET" class="w-full max-w-6xl mx-auto mb-6 p-4 bg-[#f8faf9] rounded-lg shadow-sm border-l-4 border-[#136735] border border-gray-100" style="border-left: 6px solid #136735;">
        <div class="flex flex-wrap items-end gap-x-4 gap-y-2">
            <div class="flex flex-col" style="min-width: 150px;">
                <label class="mb-1 text-xs font-semibold text-[#136735]">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from', now()->subDays(30)->toDateString()) }}" class="px-2 py-1 text-sm border border-gray-200 rounded focus:ring-1 focus:ring-[#136735]/20 focus:border-[#136735]">
            </div>
            <div class="flex flex-col" style="min-width: 150px;">
                <label class="mb-1 text-xs font-semibold text-[#136735]">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to', now()->toDateString()) }}" class="px-2 py-1 text-sm border border-gray-200 rounded focus:ring-1 focus:ring-[#136735]/20 focus:border-[#136735]">
            </div>
            <div class="flex flex-col" style="min-width: 140px;">
                <label class="mb-1 text-xs font-semibold text-[#136735]">User</label>
                <select name="user_id" class="px-2 py-1 text-sm border border-gray-200 rounded focus:ring-1 focus:ring-[#136735]/20 focus:border-[#136735]">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->username }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col" style="min-width: 140px;">
                <label class="mb-1 text-xs font-semibold text-[#136735]">Module</label>
                <select name="module" class="px-2 py-1 text-sm border border-gray-200 rounded focus:ring-1 focus:ring-[#136735]/20 focus:border-[#136735]">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" @selected(request('module') == $module)>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col flex-1 min-w-[180px]">
                <label class="mb-1 text-xs font-semibold text-[#136735]">Action</label>
                <input type="text" name="action" value="{{ request('action') }}" class="px-2 py-1 text-sm border border-gray-200 rounded focus:ring-1 focus:ring-[#136735]/20 focus:border-[#136735]" placeholder="Search actions...">
            </div>
            <div class="flex gap-2 mb-1">
                <button type="submit" class="px-5 py-1.5 text-xs font-semibold rounded hover:bg-[#0f522a] hover:border-[#0f522a] transition shadow-sm border" style="background:#136735!important;border-color:#136735!important;color:#fff!important;">Apply</button>
                <a href="{{ route('audit-trails.index') }}" class="px-5 py-1.5 text-xs font-semibold text-[#136735] bg-[#e6f4ec] border border-[#136735] rounded hover:bg-[#cbead6] hover:text-[#0f522a] hover:border-[#0f522a] transition">Reset</a>
            </div>
        </div>
    </form>
    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow table-responsive">
        <table class="table min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">When</th>
                    <th class="px-4 py-2 text-left">Who</th>
                    <th class="px-4 py-2 text-left">Module</th>
                    <th class="px-4 py-2 text-left">What</th>
                    <th class="px-4 py-2 text-left">Details</th>
                    <th class="px-4 py-2 text-left">From</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditTrails as $trail)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-600 timestamp whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($trail->timestamp ?? $trail->created_at)->format('m/d H:i') }}
                    </td>
                    <td class="px-4 py-2">{{ $trail->user->username ?? $trail->user->full_name ?? '-' }}</td>
                    <td class="px-4 py-2"><span class="module-badge bg-gray-100 px-2 py-0.5 rounded text-xs text-gray-700">{{ $trail->module ?? 'General' }}</span></td>
                    <td class="px-4 py-2 max-w-[150px] truncate">{{ $trail->action }}</td>
                    <td class="audit-details px-4 py-2 max-w-[250px] text-gray-600 cursor-pointer" title="{{ is_array($trail->details) ? json_encode($trail->details) : $trail->details }}">
                        @php
                            $details = is_array($trail->details) ? $trail->details : json_decode($trail->details, true);
                        @endphp
                        @if(is_array($details))
                            @switch($trail->module)
                                @case('Dashboard')
                                    @if(isset($details['stats']))
                                        Items: {{ number_format($details['stats']['total_items'] ?? 0) }} (Low: {{ number_format($details['stats']['low_stock'] ?? 0) }}) |
                                        Suppliers: {{ number_format($details['stats']['total_suppliers'] ?? 0) }} |
                                        Today's Moves: {{ number_format($details['stats']['today_movements'] ?? 0) }}
                                    @endif
                                    @break
                                @case('Inventory')
                                    Item: {{ $details['item_code'] ?? '' }} - {{ $details['item_name'] ?? '' }} |
                                    Qty: {{ $details['quantity'] ?? '' }}
                                    @if(isset($details['action_type'])) | Type: {{ ucfirst($details['action_type']) }} @endif
                                    @if(isset($details['defect_reason'])) | Reason: {{ $details['defect_reason'] }} @endif
                                    @break
                                @case('Sales')
                                    @if(isset($details['order_id']))
                                        Order #{{ $details['order_id'] }}
                                        @if(isset($details['customer_name'])) | Customer: {{ $details['customer_name'] }} @endif
                                        @if(isset($details['total_amount'])) | Amount: ₱{{ number_format($details['total_amount'], 2) }} @endif
                                        @if(isset($details['items_count'])) | Items: {{ $details['items_count'] }} @endif
                                        @if(isset($details['old_status']) && isset($details['new_status'])) | Status: {{ $details['old_status'] }} → {{ $details['new_status'] }} @endif
                                    @endif
                                    @break
                                @case('Auth')
                                    @if(isset($details['action_type']))
                                        {{ ucfirst($details['action_type']) }}
                                        @if(isset($details['username'])) | User: {{ $details['username'] }} @endif
                                        @if(isset($details['role'])) | Role: {{ ucfirst($details['role']) }} @endif
                                        @if(isset($details['reason'])) | Reason: {{ $details['reason'] }} @endif
                                    @endif
                                    @break
                                @case('Users')
                                    User: {{ $details['username'] ?? '' }}
                                    @if(isset($details['action_type'])) | Action: {{ ucfirst($details['action_type']) }} @endif
                                    @if(isset($details['old_status']) && isset($details['new_status'])) | Status: {{ ucfirst($details['old_status']) }} → {{ ucfirst($details['new_status']) }} @endif
                                    @if(isset($details['reason'])) | Reason: {{ $details['reason'] }} @endif
                                    @break
                                @case('Profile')
                                    @if(isset($details['action_type']) && $details['action_type'] === 'avatar_update')
                                        Updated profile picture
                                    @elseif(isset($details['changes']))
                                        @php $changes = []; @endphp
                                        @foreach($details['changes'] as $field => $new_value)
                                            @php $old_value = $details['old_data'][$field] ?? 'Not set'; @endphp
                                            @php $changes[] = ucwords(str_replace('_', ' ', $field)).": $old_value → ".($new_value ?: 'Not set'); @endphp
                                        @endforeach
                                        {{ implode(' | ', $changes) }}
                                    @endif
                                    @break
                                @default
                                    @php $formatted = ''; @endphp
                                    @foreach($details as $key => $value)
                                        @if(is_array($value))
                                            @foreach($value as $subKey => $subValue)
                                                @php $formatted .= ucwords(str_replace('_', ' ', $subKey)).": ".(is_array($subValue) ? json_encode($subValue) : $subValue)." | "; @endphp
                                            @endforeach
                                        @else
                                            @php $formatted .= ucwords(str_replace('_', ' ', $key)).": ".$value." | "; @endphp
                                        @endif
                                    @endforeach
                                    {{ rtrim($formatted, ' | ') }}
                            @endswitch
                        @else
                            {{ is_array($trail->details) ? json_encode($trail->details) : $trail->details }}
                        @endif
                    </td>
                    <td class="px-4 py-2 text-xs text-gray-600">{{ $trail->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">No audit trails found for the selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        @if ($auditTrails->hasPages())
            <nav class="inline-flex items-center gap-1" aria-label="Pagination">
                {{-- Previous Page Link --}}
                @if ($auditTrails->onFirstPage())
                    <span class="px-3 py-1.5 rounded border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">&laquo;</span>
                @else
                    <a href="{{ $auditTrails->previousPageUrl() }}" class="px-3 py-1.5 rounded border border-gray-200 bg-white text-[#136735] hover:bg-[#e6f4ec] transition">&laquo;</a>
                @endif
                {{-- Pagination Elements --}}
                @foreach ($auditTrails->links()->elements[0] as $page => $url)
                    @if ($page == $auditTrails->currentPage())
                        <span class="px-3 py-1.5 rounded border border-[#136735] bg-[#136735] text-white font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 rounded border border-gray-200 bg-white text-[#136735] hover:bg-[#e6f4ec] transition">{{ $page }}</a>
                    @endif
                @endforeach
                {{-- Next Page Link --}}
                @if ($auditTrails->hasMorePages())
                    <a href="{{ $auditTrails->nextPageUrl() }}" class="px-3 py-1.5 rounded border border-gray-200 bg-white text-[#136735] hover:bg-[#e6f4ec] transition">&raquo;</a>
                @else
                    <span class="px-3 py-1.5 rounded border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">&raquo;</span>
                @endif
            </nav>
        @endif
    </div>
</div>
@endsection
