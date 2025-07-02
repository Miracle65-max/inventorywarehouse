@extends('layouts.app')
@section('title', 'Add New Item')
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh;">
    <div class="justify-between mb-4 d-flex align-center">
        <h1 class="mb-0" style="font-size: 2rem; letter-spacing: 0.5px; color: #1d2327;">Add New Item</h1>
        <div>
            <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2);">← Back to Items</a>
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
            <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Item Information</h3>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form method="POST" action="{{ route('items.store') }}" style="margin: 0;">
                @csrf
                
                <div class="row" style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div class="col-md-6" style="flex: 1;">
                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="item_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Item Name *</label>
                            <input type="text" 
                                   class="form-control @error('item_name') is-invalid @enderror" 
                                   id="item_name" 
                                   name="item_name" 
                                   value="{{ old('item_name') }}" 
                                   required 
                                   maxlength="255"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('item_name')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="category" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Category *</label>
                            <select class="form-control @error('category') is-invalid @enderror" 
                                    id="category" 
                                    name="category" 
                                    required 
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                                <option value="">Select Category</option>
                                <option value="electrical" {{ old('category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="plumbing" {{ old('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                <option value="hardware" {{ old('category') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="tools" {{ old('category') == 'tools' ? 'selected' : '' }}>Tools</option>
                                <option value="materials" {{ old('category') == 'materials' ? 'selected' : '' }}>Materials</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="supplier_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Supplier</label>
                            <select class="form-control @error('supplier_id') is-invalid @enderror" 
                                    id="supplier_id" 
                                    name="supplier_id" 
                                    style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6" style="flex: 1;">
                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="quantity" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Quantity *</label>
                            <input type="number" 
                                   class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity') }}" 
                                   required 
                                   min="0"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('quantity')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="unit" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Unit *</label>
                            <input type="text" 
                                   class="form-control @error('unit') is-invalid @enderror" 
                                   id="unit" 
                                   name="unit" 
                                   value="{{ old('unit') }}" 
                                   required 
                                   maxlength="50"
                                   placeholder="e.g., pieces, boxes, kg"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('unit')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="cost_price" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Unit Price (₱) *</label>
                            <input type="number" 
                                   class="form-control @error('cost_price') is-invalid @enderror" 
                                   id="cost_price" 
                                   name="cost_price" 
                                   value="{{ old('cost_price') }}" 
                                   required 
                                   min="0"
                                   step="0.01"
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('cost_price')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" style="margin-bottom: 20px;">
                            <label for="date_received" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Date Received *</label>
                            <input type="date" 
                                   class="form-control @error('date_received') is-invalid @enderror" 
                                   id="date_received" 
                                   name="date_received" 
                                   value="{{ old('date_received') }}" 
                                   required 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                            @error('date_received')
                                <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3" style="margin-bottom: 20px;">
                    <label for="description" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              maxlength="500"
                              placeholder="Brief description of the item..."
                              style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white; resize: vertical;">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" style="margin-bottom: 20px;">
                    <label for="location" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Location</label>
                    <input type="text" 
                           class="form-control @error('location') is-invalid @enderror" 
                           id="location" 
                           name="location" 
                           value="{{ old('location') }}" 
                           maxlength="255"
                           placeholder="e.g., Warehouse A, Shelf 3"
                           style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                    @error('location')
                        <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions" style="border-top: 1px solid #e1e5e9; padding-top: 20px; margin-top: 20px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span style="color: #646970; font-size: 12px; font-style: italic;">* Required fields</span>
                        </div>
                        <div class="gap-1" style="display: flex; gap: 8px;">
                            <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2);">Cancel</a>
                            <button type="submit" class="btn btn-primary" style="background-color: #136735; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); cursor: pointer;">Add Item</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const itemCodeInput = document.getElementById('item_code');
    const codePreview = document.getElementById('code-preview');
    const previewCode = document.getElementById('preview-code');
    
    function updateCodePreview() {
        const category = categorySelect.value;
        const currentCode = itemCodeInput.value.trim();
        
        if (!currentCode && category) {
            // Generate preview code
            const prefix = category.substring(0, 3).toUpperCase();
            const year = new Date().getFullYear();
            const month = String(new Date().getMonth() + 1).padStart(2, '0');
            const preview = prefix + '-' + year + month + '-0001';
            
            previewCode.textContent = preview;
            codePreview.style.display = 'block';
        } else {
            codePreview.style.display = 'none';
        }
    }
    
    categorySelect.addEventListener('change', updateCodePreview);
    itemCodeInput.addEventListener('input', updateCodePreview);
    
    // Initial preview
    updateCodePreview();
});
</script>
@endsection