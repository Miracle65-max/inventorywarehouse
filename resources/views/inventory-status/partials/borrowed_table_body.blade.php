@forelse($borrowedItems as $item)
<tr>
    <td>{{ $item->item ? $item->item->item_name : 'Unknown Item' }}</td>
    <td>{{ $item->quantity }}</td>
    <td>{{ $item->borrowed_date->format('M j, Y') }}</td>
    <td>{{ $item->expected_return_date->format('M j, Y') }}</td>
    <td>
        <span class="wp-badge wp-badge-{{ $item->status === 'borrowed' ? 'warning' : 'success' }}">
            {{ ucfirst($item->status) }}
        </span>
    </td>
    @can('return', $item)
    @if($item->status === 'borrowed')
    <td>
        <form method="POST" action="{{ route('inventory-status.return-item') }}" class="return-form">
            @csrf
            <input type="hidden" name="borrowed_id" value="{{ $item->borrowed_id }}">
            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
            <input type="hidden" name="quantity" value="{{ $item->quantity }}">
            <button type="submit" class="wp-btn wp-btn-primary wp-btn-sm" style="padding:6px 14px;font-size:13px;" onclick="return confirm('Are you sure you want to return this item?')">Return Item</button>
        </form>
    </td>
    @endif
    @endcan
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No borrowed items found</td>
</tr>
@endforelse