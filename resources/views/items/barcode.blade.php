@extends('layouts.app')
@section('title', 'Barcode - ' . $item->item_code)
@section('content')
<div class="main-content" style="background: #f7f8fa; min-height: 100vh;">
    <div class="justify-between mb-4 d-flex align-center">
        <h1 class="mb-0" style="font-size: 2rem; letter-spacing: 0.5px; color: #1d2327;">Item Barcode</h1>
        <div>
            <a href="{{ route('items.index') }}" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 8px 16px; border-radius: 3px; text-decoration: none; font-size: 13px; font-weight: 500; border: 1px solid #6c757d; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(108,117,125,0.2);">← Back to Items</a>
        </div>
    </div>

    <div class="card" style="box-shadow: 0 4px 16px rgba(44,62,80,0.07); border-radius: 3px; border: 1px solid #e1e5e9; background: white; max-width: 600px; margin: 0 auto;">
        <div class="card-header" style="background: #f8fafc; border-radius: 3px 3px 0 0; border-bottom: 1px solid #e1e5e9; padding: 16px 20px;">
            <h3 class="card-title" style="font-size: 1.2rem; letter-spacing: 0.2px; margin: 0; color: #1d2327; font-weight: 600;">Barcode for {{ $item->item_name }}</h3>
        </div>
        <div class="card-body" style="padding: 30px; text-align: center;">
            <div class="item-info" style="margin-bottom: 30px;">
                <h4 style="color: #1d2327; margin-bottom: 10px; font-size: 18px;">{{ $item->item_name }}</h4>
                <p style="color: #646970; margin-bottom: 5px; font-size: 14px;">Code: <strong>{{ $item->item_code }}</strong></p>
                <p style="color: #646970; margin-bottom: 5px; font-size: 14px;">Category: <strong>{{ ucfirst($item->category) }}</strong></p>
                @if($item->supplier)
                    <p style="color: #646970; margin-bottom: 5px; font-size: 14px;">Supplier: <strong>{{ $item->supplier->supplier_name }}</strong></p>
                @endif
            </div>
            
            <div class="barcode-container" style="margin: 20px 0; padding: 20px; border: 2px solid #e1e5e9; border-radius: 8px; background: white;">
                <img src="{{ route('barcode.generate', $item->item_code) }}" 
                     alt="Barcode for {{ $item->item_code }}" 
                     style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                <div style="margin-top: 10px; font-family: monospace; font-size: 16px; font-weight: 600; color: #1d2327;">
                    {{ $item->item_code }}
                </div>
            </div>
            
            <div class="actions" style="margin-top: 30px;">
                <button onclick="window.print()" class="btn btn-primary" style="background-color: #136735; color: white; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-size: 14px; font-weight: 500; border: 1px solid #136735; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(19,103,53,0.2); cursor: pointer; margin-right: 10px;">Print Barcode</button>
                <a href="{{ route('items.show', $item) }}" class="btn btn-info" style="background-color: #17a2b8; color: white; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-size: 14px; font-weight: 500; border: 1px solid #17a2b8; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(23,162,184,0.2);">View Item Details</a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .main-content { background: white !important; }
    .card { box-shadow: none !important; border: 1px solid #000 !important; }
    .actions { display: none !important; }
    .justify-between { display: none !important; }
    .barcode-container { border: 1px solid #000 !important; }
}
</style>
@endsection 