@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh; padding: 20px;">
    <div class="justify-between mb-4 d-flex align-center">
        <h1 class="mb-0" style="font-size: 2rem; letter-spacing: 0.5px; color: #1d2327;">Edit Supplier</h1>
        <div>
            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary" style="background-color: #3c343c; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; margin-right: 8px; border: 1px solid #3c343c; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(60,52,60,0.2);">← Back to Supplier</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2);">All Suppliers</a>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 alert alert-danger" style="box-shadow: 0 1px 4px rgba(220,53,69,0.08); background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 12px 16px; border-radius: 3px; border: 1px solid;">
            <strong style="font-weight: 600;">Please fix the following errors:</strong>
            <ul style="margin: 8px 0 0 20px; padding: 0;">
                @foreach($errors->all() as $error)
                    <li style="margin-bottom: 4px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white;">
        <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
            <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Edit Supplier Information</h3>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}" style="margin: 0;">
                @csrf
                @method('PUT')
                
                <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="col-md-6" style="flex: 1;">
                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="supplier_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Supplier Name *</label>
                            <input type="text" 
                                   class="form-control @error('supplier_name') is-invalid @enderror" 
                                   id="supplier_name" 
                                   name="supplier_name" 
                                   value="{{ old('supplier_name', $supplier->supplier_name) }}" 
                                   required 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('supplier_name')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="contact_person" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Contact Person</label>
                            <input type="text" 
                                   class="form-control @error('contact_person') is-invalid @enderror" 
                                   id="contact_person" 
                                   name="contact_person" 
                                   value="{{ old('contact_person', $supplier->contact_person) }}" 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('contact_person')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="contact_number" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Contact Number</label>
                            <input type="text" 
                                   class="form-control @error('contact_number') is-invalid @enderror" 
                                   id="contact_number" 
                                   name="contact_number" 
                                   value="{{ old('contact_number', $supplier->contact_number) }}" 
                                   maxlength="20" 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('contact_number')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $supplier->email) }}" 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('email')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6" style="flex: 1;">
                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="address" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white; resize: vertical;">{{ old('address', $supplier->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Status *</label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required 
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                                <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="notes" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="4" 
                                      style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white; resize: vertical;">{{ old('notes', $supplier->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="border-top: 1px solid #e1e5e9; padding-top: 20px; margin-top: 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span style="color: #646970; font-size: 12px; font-style: italic;">* Required fields</span>
                        </div>
                        <div class="gap-1" style="display: flex; gap: 8px;">
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2);">Cancel</a>
                            <button type="submit" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); cursor: pointer;">Update Supplier</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control:focus { 
    outline: none; 
    border-color: #136735; 
    box-shadow: 0 0 0 2px rgba(19,103,53,0.2); 
}
.form-control.is-invalid { 
    border-color: #dc3545; 
    box-shadow: 0 0 0 2px rgba(220,53,69,0.2); 
}
.btn:hover { 
    filter: brightness(0.95); 
    box-shadow: 0 2px 8px rgba(44,62,80,0.15); 
    transform: translateY(-1px); 
}
@media (max-width: 768px) {
    .row { flex-direction: column; }
    .col-md-6 { flex: none; }
    .gap-1 { gap: 8px !important; }
    .btn { min-width: auto !important; }
    .form-actions .d-flex { flex-direction: column; gap: 16px; align-items: stretch; }
}
</style>
@endsection 