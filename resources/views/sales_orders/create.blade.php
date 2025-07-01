@extends('layouts.app')
@section('content')
<style>
.wp-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-bottom: 24px;
    padding: 24px;
}
.wp-title {
    font-size: 2rem;
    font-weight: 400;
    color: #23282d;
    margin-bottom: 24px;
}
.wp-btn {
    display: inline-block;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid transparent;
    border-radius: 0;
    cursor: pointer;
    transition: all 0.15s;
    text-decoration: none;
    box-shadow: 0 1px 0 #ccc;
}
.wp-btn-primary {
    background: #136735;
    border-color: #136735;
    color: #fff;
}
.wp-btn-primary:hover {
    background: #0f4f28;
    border-color: #0f4f28;
    color: #fff;
}
.wp-btn-secondary {
    background: #3c343c;
    border-color: #3c343c;
    color: #fff;
}
.wp-btn-secondary:hover {
    background: #23282d;
    border-color: #23282d;
    color: #fff;
}
.wp-form-label {
    font-weight: 600;
    color: #23282d;
    font-size: 13px;
    margin-bottom: 4px;
    display: block;
}
.wp-form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 0;
    font-size: 14px;
    background: #fff;
    color: #23282d;
    margin-bottom: 12px;
    transition: border-color 0.15s;
}
.wp-form-control:focus {
    border-color: #136735;
    outline: none;
}
.wp-form-control.error {
    border-color: #dc3232;
}
.wp-error-message {
    color: #dc3232;
    font-size: 12px;
    margin-top: -8px;
    margin-bottom: 8px;
}
.wp-section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #23282d;
    margin: 18px 0 10px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #ddd;
}
.wp-order-items-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border: 1px solid #ddd;
}
.wp-order-items-row select,
.wp-order-items-row input {
    margin-bottom: 0;
}
.wp-field-caption {
    font-size: 11px;
    color: #666;
    margin-top: 2px;
    font-style: italic;
}
.wp-required {
    color: #dc3232;
}
</style>
<div class="main-content">
    <div class="wp-card">
        <h1 class="wp-title">Create New Sales Order</h1>
        <p style="color: #666; margin-bottom: 24px;">Fill in the customer information and select items to create a new sales order. All fields marked with <span class="wp-required">*</span> are required.</p>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4" style="border-radius:0;">
                <strong>Please correct the following errors:</strong>
                <ul style="margin: 8px 0 0 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('sales-orders.store') }}" id="createOrderForm">
            @csrf
            <div class="wp-section-title">Customer Information</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div>
                    <label class="wp-form-label">Customer Name <span class="wp-required">*</span></label>
                    <input type="text" name="customer_name" class="wp-form-control @error('customer_name') error @enderror" 
                           required maxlength="100" value="{{ old('customer_name') }}" 
                           placeholder="Enter customer's full name">
                    <div class="wp-field-caption">Enter the complete name of the customer (letters, spaces, hyphens, and apostrophes only)</div>
                    @error('customer_name')
                        <div class="wp-error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="wp-form-label">Email Address</label>
                    <input type="email" name="customer_email" class="wp-form-control @error('customer_email') error @enderror" 
                           maxlength="100" value="{{ old('customer_email') }}" 
                           placeholder="customer@example.com">
                    <div class="wp-field-caption">Optional: Customer's email address for order notifications</div>
                    @error('customer_email')
                        <div class="wp-error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="wp-form-label">Phone Number</label>
                    <input type="text" name="customer_phone" class="wp-form-control @error('customer_phone') error @enderror" 
                           maxlength="20" value="{{ old('customer_phone') }}" 
                           placeholder="+63 912 345 6789">
                    <div class="wp-field-caption">Optional: Customer's contact number</div>
                    @error('customer_phone')
                        <div class="wp-error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="wp-form-label">Pickup/Delivery Date <span class="wp-requ ired">*</span></label>
                    <input type="date" name="pickup_date" class="wp-form-control @error('pickup_date') error @enderror" 
                           required value="{{ old('pickup_date') }}" 
                           min="{{ date('Y-m-d') }}">
                    <div class="wp-field-caption">Select when the customer will pick up or when items should be delivered</div>
                    @error('pickup_date')
                        <div class="wp-error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="wp-form-label">Delivery Address</label>
                    <textarea name="customer_address" class="wp-form-control @error('customer_address') error @enderror" 
                              rows="2" maxlength="500" placeholder="Enter complete delivery address">{{ old('customer_address') }}</textarea>
                    <div class="wp-field-caption">Optional: Complete delivery address for shipping</div>
                    @error('customer_address')
                        <div class="wp-error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="wp-section-title">Order Items</div>
            <div style="margin-bottom: 12px;">
                <div style="display:grid;grid-template-columns: 2fr 1fr 1fr 1fr auto;gap:10px;padding:8px 12px;background:#f1f1f1;font-weight:600;font-size:12px;text-transform:uppercase;color:#23282d;">
                    <div>Item Selection</div>
                    <div>Quantity</div>
                    <div>Unit Price (₱)</div>
                    <div>Total (₱)</div>
                    <div>Action</div>
                </div>
            </div>
            <div id="orderItems">
                <div class="wp-order-items-row order-item-row">
                    <select name="items[0][item_id]" class="wp-form-control item-select @error('items.0.item_id') error @enderror" 
                            onchange="updateItemPrice(this, 0)" required>
                        <option value="">Select an item from inventory</option>
                        @foreach($items as $item)
                            <option value="{{ $item->item_id }}" data-price="{{ $item->cost_price }}" 
                                    data-stock="{{ $item->quantity }}" data-unit="{{ $item->unit }}" 
                                    data-name="{{ $item->item_name }}">
                                {{ $item->item_code }} - {{ $item->item_name }} (Stock: {{ $item->quantity }} {{ $item->unit }})
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][quantity]" class="wp-form-control quantity-input @error('items.0.quantity') error @enderror" 
                           min="1" max="999999" required onchange="calculateTotal(0)" oninput="validateStock(this, 0)" 
                           placeholder="Qty">
                    <input type="number" name="items[0][unit_price]" class="wp-form-control price-input @error('items.0.unit_price') error @enderror" 
                           step="0.01" min="0" max="999999.99" required onchange="calculateTotal(0)" 
                           placeholder="0.00">
                    <input type="text" class="wp-form-control item-total" readonly placeholder="₱0.00">
                    <button type="button" class="wp-btn" style="background:#dc3232;border-color:#dc3232;color:#fff;padding:6px 12px;font-size:13px;" 
                            onclick="removeOrderItem(this)">Remove</button>
                </div>
            </div>
            <button type="button" class="wp-btn wp-btn-secondary mb-4" onclick="addOrderItem()">+ Add Another Item</button>
            
            <div class="mb-4">
                <label class="wp-form-label">Order Notes</label>
                <textarea name="notes" class="wp-form-control @error('notes') error @enderror" 
                          rows="3" maxlength="1000" placeholder="Additional notes or special instructions for this order">{{ old('notes') }}</textarea>
                <div class="wp-field-caption">Optional: Any special instructions, delivery notes, or additional information</div>
                @error('notes')
                    <div class="wp-error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display:flex;justify-content:space-between;align-items:center;padding:16px;background:#f8f9fa;border:1px solid #ddd;">
                <div>
                    <strong style="font-size:16px;">Order Total: ₱<span id="orderTotal">0.00</span></strong>
                    <br><small id="itemCount" style="color:#666;">0 items selected</small>
                </div>
                <div>
                    <button type="submit" class="wp-btn wp-btn-primary" onclick="return validateForm()">Create Sales Order</button>
                    <a href="{{ route('sales-orders.index') }}" class="wp-btn wp-btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
let itemCounter = 1;

function addOrderItem() {
    const container = document.getElementById('orderItems');
    const newItem = container.querySelector('.order-item-row').cloneNode(true);
    newItem.querySelectorAll('select, input').forEach(element => {
        if (element.name) {
            element.name = element.name.replace('[0]', '[' + itemCounter + ']');
        }
        if (element.type !== 'button') {
            element.value = '';
        }
    });
    newItem.querySelector('.item-total').value = '';
    newItem.querySelector('.item-select').setAttribute('onchange', `updateItemPrice(this, ${itemCounter})`);
    newItem.querySelector('.quantity-input').setAttribute('onchange', `calculateTotal(${itemCounter})`);
    newItem.querySelector('.quantity-input').setAttribute('oninput', `validateStock(this, ${itemCounter})`);
    newItem.querySelector('.price-input').setAttribute('onchange', `calculateTotal(${itemCounter})`);
    container.appendChild(newItem);
    itemCounter++;
}

function removeOrderItem(button) {
    const rows = document.querySelectorAll('.order-item-row');
    if (rows.length > 1) {
        button.closest('.order-item-row').remove();
        calculateOrderTotal();
    } else {
        alert('At least one item is required for the order.');
    }
}

function updateItemPrice(select, index) {
    const option = select.options[select.selectedIndex];
    const priceInput = document.getElementsByName(`items[${index}][unit_price]`)[0];
    const quantityInput = document.getElementsByName(`items[${index}][quantity]`)[0];
    if (option.value) {
        priceInput.value = parseFloat(option.getAttribute('data-price')).toFixed(2);
        quantityInput.setAttribute('max', option.getAttribute('data-stock'));
        calculateTotal(index);
        validateStock(quantityInput, index);
    } else {
        priceInput.value = '';
        quantityInput.removeAttribute('max');
        calculateTotal(index);
    }
}

function validateStock(quantityInput, index) {
    const itemSelect = document.getElementsByName(`items[${index}][item_id]`)[0];
    const option = itemSelect.options[itemSelect.selectedIndex];
    if (option.value && quantityInput.value) {
        const availableStock = parseInt(option.getAttribute('data-stock'));
        const requestedQuantity = parseInt(quantityInput.value);
        if (requestedQuantity > availableStock) {
            quantityInput.classList.add('error');
            quantityInput.title = `Only ${availableStock} units available in stock`;
        } else {
            quantityInput.classList.remove('error');
            quantityInput.title = '';
        }
    } else {
        quantityInput.classList.remove('error');
        quantityInput.title = '';
    }
}

function calculateTotal(index) {
    const quantity = document.getElementsByName(`items[${index}][quantity]`)[0].value;
    const price = document.getElementsByName(`items[${index}][unit_price]`)[0].value;
    const totalInput = document.getElementsByClassName('item-total')[index];
    const total = (parseFloat(quantity) * parseFloat(price)) || 0;
    totalInput.value = '₱' + total.toFixed(2);
    calculateOrderTotal();
}

function calculateOrderTotal() {
    let total = 0;
    let itemCount = 0;
    document.querySelectorAll('.item-total').forEach(input => {
        const value = input.value.replace('₱', '');
        const itemTotal = parseFloat(value) || 0;
        if (itemTotal > 0) {
            total += itemTotal;
            itemCount++;
        }
    });
    document.getElementById('orderTotal').textContent = total.toFixed(2);
    document.getElementById('itemCount').textContent = `${itemCount} item${itemCount !== 1 ? 's' : ''} selected`;
}

function validateForm() {
    const customerName = document.querySelector('input[name="customer_name"]').value.trim();
    if (!customerName) {
        alert('Please enter the customer name.');
        return false;
    }
    
    const pickupDate = document.querySelector('input[name="pickup_date"]').value;
    if (!pickupDate) {
        alert('Please select a pickup/delivery date.');
        return false;
    }
    
    const today = new Date().toISOString().split('T')[0];
    if (pickupDate < today) {
        alert('Pickup/delivery date must be today or a future date.');
        return false;
    }
    
    let hasValidItems = false;
    document.querySelectorAll('.item-select').forEach(select => {
        if (select.value) {
            hasValidItems = true;
        }
    });
    
    if (!hasValidItems) {
        alert('Please select at least one item for the order.');
        return false;
    }
    
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    calculateOrderTotal();
});
</script>
@endsection
