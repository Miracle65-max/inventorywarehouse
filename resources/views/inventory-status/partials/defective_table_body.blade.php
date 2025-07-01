@foreach($defectiveItems as $item)
<tr>
    <td>{{ $item->item ? $item->item->item_code : 'Unknown Item' }} - {{ $item->item ? $item->item->item_name : '' }}</td>
    <td>
        @if($item->item && $item->item->supplier)
            <strong>{{ $item->item->supplier->supplier_name }}</strong>
            @if($item->item->supplier->contact_person || $item->item->supplier->contact_number)
                <small>
                    @if($item->item->supplier->contact_person)
                        Contact: {{ $item->item->supplier->contact_person }}
                    @endif
                    @if($item->item->supplier->contact_number)
                        {{ $item->item->supplier->contact_person ? ' - ' : '' }}
                        📞 {{ $item->item->supplier->contact_number }}
                    @endif
                </small>
            @endif
        @else
            <em>No supplier info</em>
        @endif
    </td>
    <td>{{ $item->quantity_defective }}</td>
    <td>{{ $item->defect_date->format('M j, Y') }}</td>
    <td>{{ $item->defect_reason }}</td>
    <td>
        <span class="wp-badge wp-badge-{{ 
            $item->status === 'pending' ? 'warning' : 
            ($item->status === 'repaired' ? 'success' : 'danger') 
        }}">
            {{ ucfirst($item->status) }}
        </span>
    </td>
    <td>
        <span class="wp-badge wp-badge-{{ 
            $item->days_outstanding > 30 ? 'danger' : 
            ($item->days_outstanding > 14 ? 'warning' : 'info') 
        }}">
            {{ $item->days_outstanding }} days
        </span>
    </td>
    <td class="action-buttons">
        @if($item->status === 'pending')
            <div style="display:flex;gap:8px;">
                <form method="POST" action="{{ route('inventory-status.repair-item') }}">
                    @csrf
                    <input type="hidden" name="defect_id" value="{{ $item->defect_id }}">
                    <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                    <input type="hidden" name="quantity" value="{{ $item->quantity_defective }}">
                    <button type="submit" class="wp-btn wp-btn-primary wp-btn-sm" style="padding:6px 14px;font-size:13px;" onclick="return confirm('Are you sure you want to mark this item as repaired?')">Mark as Repaired</button>
                </form>
                <form method="POST" action="{{ route('inventory-status.dispose-item') }}">
                    @csrf
                    <input type="hidden" name="defect_id" value="{{ $item->defect_id }}">
                    <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                    <input type="hidden" name="quantity" value="{{ $item->quantity_defective }}">
                    <button type="submit" class="wp-btn wp-btn-danger wp-btn-sm" style="background:#dc3232;border-color:#dc3232;color:#fff;padding:6px 14px;font-size:13px;" onclick="return confirm('Are you sure you want to dispose of this item? This action cannot be undone.')">Dispose</button>
                </form>
            </div>
        @else
            <span class="wp-badge wp-badge-secondary">
                {{ strtoupper($item->status) }}
            </span>
        @endif
    </td>
</tr>
@endforeach
@if($defectiveItems->isEmpty())
<tr>
    <td colspan="8" class="text-center">No defective items found</td>
</tr>
@endif 