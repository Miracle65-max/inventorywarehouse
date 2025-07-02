@extends('layouts.app')

@section('title', 'Edit Item')

@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh; padding: 32px 0 0 0;">
    <div style="max-width: 700px; margin: 40px auto;">
        <div class="card" style="box-shadow: 0 2px 12px rgba(44,62,80,0.07); border-radius: 8px; border: 1px solid #e1e5e9; background: #fff;">
            <div class="d-flex justify-content-between align-items-center" style="padding: 24px 24px 0 24px;">
                <h2 style="font-size: 2rem; color: #1d2327; font-weight: 700; margin: 0;">Edit Item</h2>
                <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background: #3c343c; color: #fff; padding: 8px 18px; border-radius: 4px; font-size: 14px; font-weight: 500; text-decoration: none; border: none; box-shadow: 0 1px 3px rgba(60,52,60,0.12); transition: background 0.2s;">← Back to Items</a>
            </div>
            <div style="padding: 0 24px 24px 24px;">
                @if(session('success'))
                    <div class="alert alert-success mb-3" style="background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; padding: 12px 18px; font-size: 1rem;">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mb-3" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; padding: 12px 18px; font-size: 1rem;">
                        <ul style="margin: 0; padding-left: 18px;">
                            @foreach($errors->all() as $error)
                                <li style="font-size: 14px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('items.update', $item) }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row" style="display: flex; gap: 18px; margin-bottom: 18px;">
                        <div style="flex: 1;">
                            <div class="mb-3" style="margin-bottom: 20px;">
                                <label for="item_name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1d2327; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Item Name *</label>
                                <input type="text" 
                                       class="form-control @error('item_name') is-invalid @enderror" 
                                       id="item_name" 
                                       name="item_name" 
                                       value="{{ old('item_name', $item->item_name) }}" 
                                       required 
                                       maxlength="255"
                                       style="width: 100%; padding: 8px 12px; border: 1px solid #ddd; border-radius: 3px; font-size: 14px; transition: border-color 0.2s ease; box-sizing: border-box; background: white;">
                                @error('item_name')
                                    <div class="invalid-feedback" style="color: #dc3545; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: flex; gap: 18px; margin-bottom: 18px;">
                        <div style="flex: 1;">
                            <label for="category" style="font-weight: 600; color: #1d2327; font-size: 13px;">Category *</label>
                            <select name="category" id="category" required style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                                <option value="">Select Category</option>
                                <option value="electrical" {{ old('category', $item->category) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                                <option value="plumbing" {{ old('category', $item->category) == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                                <option value="hardware" {{ old('category', $item->category) == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                <option value="tools" {{ old('category', $item->category) == 'tools' ? 'selected' : '' }}>Tools</option>
                                <option value="materials" {{ old('category', $item->category) == 'materials' ? 'selected' : '' }}>Materials</option>
                                <option value="other" {{ old('category', $item->category) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                        <div style="flex: 1;">
                            <label for="supplier_id" style="font-weight: 600; color: #1d2327; font-size: 13px;">Supplier</label>
                            <select name="supplier_id" id="supplier_id" style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                                <option value="">Select Supplier (Optional)</option>
                                @foreach($suppliers ?? [] as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div style="margin-bottom: 18px;">
                        <label for="description" style="font-weight: 600; color: #1d2327; font-size: 13px;">Description</label>
                        <textarea name="description" id="description" rows="2" style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9; resize: vertical;">{{ old('description', $item->description) }}</textarea>
                        @error('description')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="row" style="display: flex; gap: 18px; margin-bottom: 18px;">
                        <div style="flex: 1;">
                            <label for="quantity" style="font-weight: 600; color: #1d2327; font-size: 13px;">Quantity *</label>
                            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $item->quantity) }}" required min="0" style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                            @error('quantity')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                        <div style="flex: 1;">
                            <label for="unit" style="font-weight: 600; color: #1d2327; font-size: 13px;">Unit *</label>
                            <input type="text" name="unit" id="unit" value="{{ old('unit', $item->unit) }}" required style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                            @error('unit')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                        <div style="flex: 1;">
                            <label for="cost_price" style="font-weight: 600; color: #1d2327; font-size: 13px;">Cost Price *</label>
                            <input type="number" name="cost_price" id="cost_price" value="{{ old('cost_price', $item->cost_price) }}" required step="0.01" min="0" style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                            @error('cost_price')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row" style="display: flex; gap: 18px; margin-bottom: 18px;">
                        <div style="flex: 1;">
                            <label for="location" style="font-weight: 600; color: #1d2327; font-size: 13px;">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $item->location) }}" style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                            @error('location')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                        <div style="flex: 1;">
                            <label for="date_received" style="font-weight: 600; color: #1d2327; font-size: 13px;">Date Received *</label>
                            <input type="date" name="date_received" id="date_received" value="{{ old('date_received', $item->date_received) }}" required style="width: 100%; margin-top: 4px; padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 15px; background: #f9f9f9;">
                            @error('date_received')<div style="color: #dc3545; font-size: 13px; margin-top: 2px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px;">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background: #3c343c; color: #fff; padding: 8px 18px; border-radius: 4px; font-size: 14px; font-weight: 500; text-decoration: none; border: none; box-shadow: 0 1px 3px rgba(60,52,60,0.12); transition: background 0.2s;">Cancel</a>
                        <button type="submit" class="btn btn-success" style="background: #136735; color: #fff; padding: 8px 18px; border-radius: 4px; font-size: 14px; font-weight: 500; border: none; box-shadow: 0 1px 3px rgba(19,103,53,0.12); transition: background 0.2s; cursor: pointer;">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 