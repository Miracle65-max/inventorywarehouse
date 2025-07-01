@extends('layouts.app')
@section('title', 'Supplier Management')
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh; padding: 32px 0 0 0;">
    <div style="max-width: 98%; margin: 0 auto;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="font-size: 2.2rem; font-weight: 600; color: #222; margin-bottom: 0;">Supplier Management</h1>
            @auth
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                    <button type="button" class="btn btn-success" style="background: #198754; border: none; color: #fff; font-weight: 500; font-size: 1.1rem; border-radius: 4px; box-shadow: 0 2px 6px rgba(25,135,84,0.08); padding: 10px 28px; transition: background 0.2s;" onclick="toggleAddForm()">+ Add New Supplier</button>
                @endif
            @endauth
        </div>
        @if(session('success'))
            <div class="alert alert-success mb-3" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 12px 18px; font-size: 1rem;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 12px 18px; font-size: 1rem;">{{ $errors->first() }}</div>
        @endif
        @auth
            @php $user = auth()->user(); @endphp
            @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                <div class="mb-4 card fade-in" id="addForm" style="display: none; max-width: 700px; margin: 0 auto 24px auto; border-radius: 8px; box-shadow: 0 4px 16px rgba(44,62,80,0.07); border: 1px solid #e1e5e9;">
                    <div class="card-header" style="background: #f8fafc; border-radius: 8px 8px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
                        <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327;">Add New Supplier</h3>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <form action="{{ route('suppliers.store') }}" method="POST" id="addSupplierForm" onsubmit="return validateSupplierForm('addSupplierForm')">
                            @csrf
                            <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                                <div class="form-col" style="flex: 1;">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Supplier Name *</label>
                                        <input type="text" name="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror" required maxlength="100" value="{{ old('supplier_name') }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">
                                        @error('supplier_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="form-col" style="flex: 1;">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Contact Person</label>
                                        <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" maxlength="100" value="{{ old('contact_person') }}" pattern="[a-zA-Z\s\'-\.]*" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">
                                        @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-row" style="display: flex; gap: 16px; margin-bottom: 16px;">
                                <div class="form-col" style="flex: 1;">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" maxlength="15" value="{{ old('contact_number') }}" oninput="formatContactNumber(this)" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">
                                        <small class="text-muted" style="color: #6c757d; font-size: 12px; margin-top: 4px; display: block;">Format: XXX-XXX-XXXX</small>
                                        @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="form-col" style="flex: 1;">
                                    <div class="form-group">
                                        <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" maxlength="100" value="{{ old('email') }}" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" maxlength="255" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="font-weight: 500; color: #1d2327; margin-bottom: 6px; display: block;">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" style="border: 1px solid #d1d5db; border-radius: 4px; padding: 10px 12px; font-size: 14px;">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex gap-2" style="gap: 12px;">
                                <button type="submit" class="btn btn-success" style="background: #198754; border: none; color: #fff; font-weight: 500; font-size: 1rem; border-radius: 4px; padding: 10px 24px;">Add Supplier</button>
                                <button type="button" class="btn btn-secondary" style="background: #6c757d; border: none; color: #fff; font-weight: 500; font-size: 1rem; border-radius: 4px; padding: 10px 24px;" onclick="toggleAddForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endauth
        <div class="card" style="box-shadow: 0 2px 12px rgba(44,62,80,0.07); border-radius: 8px; border: 1px solid #e1e5e9; background: #fff;">
            <div class="d-flex justify-content-between align-items-center" style="padding: 24px 24px 0 24px;">
                <h3 style="font-size: 1.15rem; font-weight: 500; color: #222; margin: 0;">Suppliers List ({{ $suppliers->total() }})</h3>
                <input type="text" id="supplierSearchInput" placeholder="Search suppliers..." class="form-control" style="max-width: 260px; border-radius: 4px; border: 1px solid #d1d5db; padding: 8px 12px; font-size: 15px;">
            </div>
            <div style="padding: 0 24px 24px 24px;">
                <table class="table" style="margin-top: 18px; width: 100%; border-collapse: separate; border-spacing: 0; background: #fff;">
                    <thead>
                        <tr style="background: #f5f6fa;">
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Supplier Name</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Contact Person</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Contact Info</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Address</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Items Count</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Status</th>
                            <th style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 15px; border-bottom: 2px solid #e1e5e9;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr style="border-bottom: 1px solid #e1e5e9;">
                                <td style="padding: 14px 16px; font-weight: 600; color: #222; font-size: 1rem;">{{ $supplier->supplier_name }}</td>
                                <td style="padding: 14px 16px; color: #555; font-size: 1rem;">{{ $supplier->contact_person ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px;">
                                    @if($supplier->contact_number)
                                        <span style="color:#dc3232;font-weight:500;display:inline-flex;align-items:center;gap:3px; margin-bottom: 2px; font-size: 1rem;">
                                            <svg width="16" height="16" fill="#dc3232" style="margin-right:2px;" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 1 .58-.326h2.016c.24 0 .462.13.58.326l1.516 2.63c.13.225.13.5 0 .725l-1.516 2.63a.678.678 0 0 1-.58.326H4.234a.678.678 0 0 1-.58-.326l-1.516-2.63a.678.678 0 0 1 0-.725l1.516-2.63z"/><path d="M11.5 2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-1 0v-11a.5.5 0 0 1 .5-.5z"/></svg>
                                            {{ $supplier->contact_number }}
                                        </span><br>
                                    @endif
                                    @if($supplier->email)
                                        <span style="color:#888;font-weight:500;display:inline-flex;align-items:center;gap:3px; font-size: 1rem;">
                                            <svg width="16" height="16" fill="#888" style="margin-right:2px;" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825L15 11.383V5.383zm-.034 7.434-5.857-3.515L8 9.583l.891-.548 5.857 3.515A1 1 0 0 1 15 13H1a1 1 0 0 1-.748-1.731l5.857-3.515L8 9.583l.891-.548 5.857 3.515A1 1 0 0 1 15 13z"/></svg>
                                            {{ $supplier->email }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 14px 16px; color: #444; font-size: 1rem;">{{ $supplier->address ?? 'N/A' }}</td>
                                <td style="padding: 14px 16px;">
                                    <span style="display: inline-block; background: #6c757d; color: #fff; font-weight: 700; font-size: 0.95rem; border-radius: 5px; padding: 4px 14px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $supplier->items_count ?? 0 }} {{ strtoupper(Str::plural('item', $supplier->items_count ?? 0)) }}</span>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <span style="display: inline-block; background: #198754; color: #fff; font-weight: 700; font-size: 0.95rem; border-radius: 5px; padding: 4px 14px; text-transform: uppercase; letter-spacing: 0.5px;">{{ strtoupper($supplier->status) }}</span>
                                </td>
                                <td style="padding: 14px 16px;">
                                    <div class="d-flex gap-2" style="gap: 8px;">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-success" style="background: #198754; border: none; color: #fff; font-weight: 500; font-size: 0.95rem; border-radius: 4px; padding: 6px 18px;">View</a>
                                        @auth
                                            @php $user = auth()->user(); @endphp
                                            @if($user && ($user->role === 'super_admin' || $user->role === 'admin'))
                                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning" style="background: #ffc107; border: none; color: #222; font-weight: 500; font-size: 0.95rem; border-radius: 4px; padding: 6px 18px;">Edit</a>
                                            @endif
                                            @if($user && $user->role === 'super_admin')
                                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" style="background: #dc3545; border: none; color: #fff; font-weight: 500; font-size: 0.95rem; border-radius: 4px; padding: 6px 18px;">Delete</button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 40px; color: #646970; text-align: center;">No suppliers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
.fade-in { animation: fadeInModal 0.4s; }
@keyframes fadeInModal { from { opacity: 0; transform: translateY(-16px);} to { opacity: 1; transform: none; } }
.table th, .table td { border: none !important; }
.table tr { border-bottom: 1px solid #e1e5e9; }
.table thead tr { background: #f5f6fa; }
.table thead th { font-weight: 600; color: #222; font-size: 15px; }
.btn:focus, .btn:active { outline: none !important; box-shadow: none !important; }
.btn-success:hover, .btn-success:focus { background: #157347 !important; }
.btn-warning:hover, .btn-warning:focus { background: #e0a800 !important; color: #222 !important; }
.btn-danger:hover, .btn-danger:focus { background: #bb2d3b !important; }
.form-control:focus { border-color: #198754; box-shadow: 0 0 0 0.15rem rgba(25,135,84,0.15); outline: none; }
@media (max-width: 900px) {
    .main-content { padding: 12px 0 0 0 !important; }
    .card { padding: 0 !important; }
    .table th, .table td { padding: 8px 6px !important; font-size: 13px !important; }
    .btn { font-size: 12px !important; padding: 6px 10px !important; }
}
</style>
<script>
function toggleAddForm() {
    const form = document.getElementById('addForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    if (form.style.display === 'block') {
        form.querySelector('form').reset();
    }
}
function formatContactNumber(input) {
    const cleaned = input.value.replace(/\D/g, '');
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
        input.value = `${match[1]}-${match[2]}-${match[3]}`;
    } else {
        input.value = cleaned;
    }
}
</script>
@endsection
