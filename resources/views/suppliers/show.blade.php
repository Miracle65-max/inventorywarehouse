@extends('layouts.app')
@section('title', 'Supplier Details')
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh; padding: 20px;">
    <div class="justify-between mb-4 d-flex align-center">
        <h1 class="mb-0" style="font-size: 2rem; letter-spacing: 0.5px; color: #1d2327;">Supplier Details</h1>
        <div>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary" style="background-color: #3c343c; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; margin-right: 8px; border: 1px solid #3c343c; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(60,52,60,0.2);">← Back to Suppliers</a>
            @auth
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2);">Edit Supplier</a>
                @endif
            @endauth
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 alert alert-success" style="box-shadow: 0 1px 4px rgba(70,180,80,0.08); background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 12px 16px; border-radius: 3px; border: 1px solid;">{{ session('success') }}</div>
    @endif

    <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
        <!-- Supplier Information Card -->
        <div class="col-md-6" style="flex: 1;">
            <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white;">
                <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
                    <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Supplier Information</h3>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3" style="margin-bottom: 20px;">
                                <strong style="color: #1d2327; font-size: 1.3rem; font-weight: 600;">{{ $supplier->supplier_name }}</strong>
                                <span class="badge {{ $supplier->status == 'active' ? 'badge-success' : 'badge-danger' }}" style="margin-left: 10px; font-weight:600;text-transform:uppercase;letter-spacing:0.5px;box-shadow:0 1px 3px #e2e3e5; padding: 4px 8px; border-radius: 3px; font-size: 10px; {{ $supplier->status == 'active' ? 'background-color: #28a745; color: white;' : 'background-color: #dc3545; color: white;' }}">
                                    {{ strtoupper($supplier->status) }}
                                </span>
                            </div>
                            
                            @if($supplier->contact_person)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Contact Person</strong>
                                    <span style="color: #1d2327; font-size: 14px;">{{ $supplier->contact_person }}</span>
                                </div>
                            @endif
                            
                            @if($supplier->contact_number)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Contact Number</strong>
                                    <span style="color:#8c8c8c;font-weight:500;display:inline-flex;align-items:center;gap:3px; font-size: 14px;">
                                        <svg width="16" height="16" fill="#8c8c8c" style="margin-right:4px;" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 1 .58-.326h2.016c.24 0 .462.13.58.326l1.516 2.63c.13.225.13.5 0 .725l-1.516 2.63a.678.678 0 0 1-.58.326H4.234a.678.678 0 0 1-.58-.326l-1.516-2.63a.678.678 0 0 1 0-.725l1.516-2.63z"/><path d="M11.5 2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-1 0v-11a.5.5 0 0 1 .5-.5z"/></svg>
                                        {{ $supplier->contact_number }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($supplier->email)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Email Address</strong>
                                    <span style="color:#8c8c8c;font-weight:500;display:inline-flex;align-items:center;gap:3px; font-size: 14px;">
                                        <svg width="16" height="16" fill="#8c8c8c" style="margin-right:4px;" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.383V5.383zm-.034 7.434-5.857-3.515L8 9.583l.891-.548 5.857 3.515A1 1 0 0 1 15 13H1a1 1 0 0 1-.748-1.731l5.857-3.515L8 9.583l.891-.548 5.857 3.515A1 1 0 0 1 15 13z"/></svg>
                                        {{ $supplier->email }}
                                    </span>
                                </div>
                            @endif
                            
                            @if($supplier->address)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Address</strong>
                                    <span style="color: #1d2327; font-size: 14px; line-height: 1.4;">{{ $supplier->address }}</span>
                                </div>
                            @endif
                            
                            @if($supplier->notes)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Notes</strong>
                                    <span style="color: #646970; font-size: 14px; line-height: 1.4; font-style: italic;">{{ $supplier->notes }}</span>
                                </div>
                            @endif
                            
                            <div class="mb-2" style="margin-bottom: 16px;">
                                <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Created</strong>
                                <span style="color: #1d2327; font-size: 14px;">{{ $supplier->created_at ? $supplier->created_at->format('F j, Y g:i A') : 'N/A' }}</span>
                            </div>
                            
                            @if($supplier->updated_at)
                                <div class="mb-2" style="margin-bottom: 16px;">
                                    <strong style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Last Updated</strong>
                                    <span style="color: #1d2327; font-size: 14px;">{{ $supplier->updated_at->format('F j, Y g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supplier Statistics Card -->
        <div class="col-md-6" style="flex: 1;">
            <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white;">
                <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
                    <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Statistics</h3>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div class="row" style="display: flex; gap: 20px;">
                        <div class="col-6" style="flex: 1;">
                            <div class="text-center" style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 3px; border: 1px solid #e1e5e9;">
                                <div style="font-size: 2.5rem; font-weight: bold; color: #136735; margin-bottom: 8px;">{{ $supplier->items_count ?? 0 }}</div>
                                <div style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Total Items</div>
                            </div>
                        </div>
                        <div class="col-6" style="flex: 1;">
                            <div class="text-center" style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 3px; border: 1px solid #e1e5e9;">
                                <div style="font-size: 2.5rem; font-weight: bold; color: #136735; margin-bottom: 8px;">₱{{ number_format($supplier->items->sum(function($item) { return $item->quantity * $item->cost_price; }), 2) }}</div>
                                <div style="color: #646970; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Total Value</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items from this Supplier -->
    <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white;">
        <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
            <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Items from this Supplier ({{ $supplier->items->count() }})</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container" style="padding: 20px;">
                @if($supplier->items->count() > 0)
                    <table class="table" style="border-radius: 3px; overflow: hidden; width: 100%; border-collapse: collapse;">
                        <thead style="position: sticky; top: 0; background: #f1f1f1; z-index: 1;">
                            <tr>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Item Name</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Item Code</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Category</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Quantity</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Unit Price</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Total Value</th>
                                <th style="background: #f1f1f1; padding: 12px 16px; text-align: left; font-weight: 600; color: #1d2327; border-bottom: 2px solid #e1e5e9; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($supplier->items as $item)
                                <tr style="transition: background 0.2s; border-bottom: 1px solid #f0f0f0;">
                                    <td style="padding: 12px 16px;"><strong style="color: #1d2327; font-size: 14px;">{{ $item->item_name }}</strong></td>
                                    <td style="padding: 12px 16px; color: #646970; font-size: 14px;">{{ $item->item_code }}</td>
                                    <td style="padding: 12px 16px;">
                                        <span class="badge badge-secondary" style="font-weight:600;text-transform:uppercase;letter-spacing:0.5px;box-shadow:0 1px 3px #e2e3e5; background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 3px; font-size: 10px;">{{ ucfirst($item->category) }}</span>
                                    </td>
                                    <td style="padding: 12px 16px; color: #1d2327; font-size: 14px;">{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                                    <td style="padding: 12px 16px; color: #1d2327; font-size: 14px;">₱{{ number_format($item->cost_price, 2) }}</td>
                                    <td style="padding: 12px 16px; color: #1d2327; font-size: 14px; font-weight: 600;">₱{{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                                    <td style="padding: 12px 16px; color: #646970; font-size: 14px;">{{ $item->location ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center" style="text-align: center; color: #646970; margin: 40px 0; padding: 40px;">
                        <span style="font-size: 48px; display: block; margin-bottom: 16px;">📦</span>
                        <p style="margin: 0; font-size: 14px; color: #646970;">No items found for this supplier.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.table tbody tr:hover { background: #f6f8fa !important; }
.btn:hover { filter: brightness(0.95); box-shadow: 0 2px 8px rgba(44,62,80,0.15); transform: translateY(-1px); }
.badge { font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 1px 3px #e2e3e5; }
@media (max-width: 768px) {
    .row { flex-direction: column; }
    .col-md-6 { flex: none; }
    .gap-1 { gap: 8px !important; }
    .btn { min-width: auto !important; }
}
</style>
@endsection 