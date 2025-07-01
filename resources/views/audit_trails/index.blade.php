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
    <div style="max-height: 520px; overflow-y: auto;" class="bg-white rounded shadow table-responsive">
        <table class="table min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100 sticky top-0 z-10" style="position: sticky; top: 0;">
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
                <tr class="border-t hover:bg-gray-50" style="height: 36px;">
                    <td class="px-2 py-1 text-gray-600 timestamp whitespace-nowrap overflow-hidden text-ellipsis" style="max-width: 80px;">
                        {{ \Carbon\Carbon::parse($trail->timestamp ?? $trail->created_at)->format('m/d H:i') }}
                    </td>
                    <td class="px-2 py-1 overflow-hidden text-ellipsis whitespace-nowrap" style="max-width: 160px;">
                        @if($trail->user)
                            {{ $trail->user->full_name }}
                            @if($trail->user->username)
                                <span class="text-xs text-gray-500">({{ $trail->user->username }})</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-2 py-1"><span class="module-badge bg-gray-100 px-2 py-0.5 rounded text-xs text-gray-700">{{ $trail->module ?? 'General' }}</span></td>
                    <td class="px-2 py-1 truncate" style="max-width: 150px;">{{ $trail->action }}</td>
                    <td class="audit-details px-2 py-1 max-w-[220px] text-gray-600 cursor-pointer overflow-hidden text-ellipsis details-tooltip-container" style="max-width: 220px; position: relative;">
                        @php
                            $details = is_array($trail->details) ? $trail->details : json_decode($trail->details, true);
                            $preview = '';
                            $tooltipLines = [];
                            if(is_array($details) && count($details)) {
                                foreach($details as $key => $value) {
                                    $line = ucwords(str_replace('_', ' ', $key)).': '.(is_array($value) ? json_encode($value) : $value);
                                    $preview .= $line.'; ';
                                    $tooltipLines[] = $line;
                                }
                            }
                        @endphp
                        @if($preview)
                            <span class="details-preview" style="display:inline-block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:middle;">
                                {{ rtrim($preview, '; ') }}
                            </span>
                            <div class="details-tooltip">
                                @foreach($tooltipLines as $line)
                                    <div>{{ $line }}</div>
                                @endforeach
                            </div>
                        @else
                            <span class="italic text-gray-400">No details</span>
                        @endif
                    </td>
                    <td class="px-2 py-1 text-xs text-gray-600" style="max-width: 120px; overflow: hidden; text-overflow: ellipsis;">{{ $trail->ip_address }}</td>
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
<style>
.details-tooltip-container {
    position: relative;
}
.details-tooltip {
    display: none;
    position: absolute;
    left: 0;
    top: 100%;
    z-index: 20;
    min-width: 220px;
    max-width: 350px;
    background: #fff;
    color: #222;
    border: 1px solid #d1d5db;
    box-shadow: 0 4px 16px 0 rgba(0,0,0,0.10);
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.95em;
    white-space: pre-line;
    word-break: break-word;
    margin-top: 0.25rem;
}
.details-tooltip-container:hover .details-tooltip,
.details-tooltip-container:focus-within .details-tooltip {
    display: block;
}
</style>
@endsection
