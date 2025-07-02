@extends('layouts.app')

@section('content')
<style>
.wp-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 0;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-bottom: 24px;
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
.wp-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    font-size: 14px;
}
.wp-table th {
    background: #f8f9fa;
    border-bottom: 1px solid #ddd;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #23282d;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.wp-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f1;
    vertical-align: middle;
    color: #23282d;
}
.wp-table tbody tr:hover {
    background: #f6f7f7;
}
.wp-badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.wp-badge-success { background: #46b450; color: #fff; }
.wp-badge-danger { background: #dc3232; color: #fff; }
.wp-badge-warning { background: #ffb900; color: #23282d; }
.wp-badge-secondary { background: #e2e3e5; color: #23282d; }
.wp-badge-info { background: #2271b1; color: #fff; }
.wp-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 24px;
    border-bottom: 1px solid #ddd;
}
.wp-tab {
    padding: 10px 24px;
    background: #f8f9fa;
    color: #23282d;
    font-weight: 600;
    border: none;
    border-radius: 0 0 0 0;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    margin-right: 2px;
}
.wp-tab.active, .wp-tab:hover {
    background: #fff;
    color: #136735;
    border-bottom: 2px solid #136735;
}
.alert {
    padding: 12px 18px;
    margin-bottom: 18px;
    border: 1px solid transparent;
}
.alert-success {
    background: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
}
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
}
</style>
<div class="main-content">
    {{-- Show error and success messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="wp-title">Inventory Status</div>
    <!-- Navigation Tabs -->
    <div class="wp-tabs">
        <a href="{{ route('inventory-status.index', ['tab' => 'borrowed']) }}" class="wp-tab{{ $tab === 'borrowed' ? ' active' : '' }}">Borrowed Items</a>
        <a href="{{ route('inventory-status.index', ['tab' => 'defective']) }}" class="wp-tab{{ $tab === 'defective' ? ' active' : '' }}">Defective Items</a>
    </div>
    @if($tab === 'borrowed')
        <div class="wp-card">
            <div style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 16px 20px;">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0; color: #23282d;">Borrowed Items Status</h3>
            </div>
            <div style="padding: 20px;">
                <div style="overflow-x:auto;">
                    <table class="wp-table" id="borrowed-items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Borrowed Date</th>
                                <th>Expected Return</th>
                                <th>Status</th>
                                @can('return', App\Models\BorrowedItem::class)
                                <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody id="borrowed-tbody">
                            @include('inventory-status.partials.borrowed_table_body', ['borrowedItems' => $borrowedItems])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="wp-card">
            <div style="background: #f8f9fa; border-bottom: 1px solid #ddd; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; font-weight: 600; margin: 0; color: #23282d;">Defective Items Status</h3>
                <form method="GET" action="" style="margin:0;display:flex;gap:8px;align-items:center;">
                    <input type="hidden" name="tab" value="defective">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search item, supplier, status..." class="form-control" style="max-width:220px;">
                    <button type="submit" class="wp-btn wp-btn-primary wp-btn-sm">Filter</button>
                </form>
            </div>
            <div style="padding: 20px;">
                <div style="overflow-x:auto;">
                    <table class="wp-table" id="defective-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Supplier</th>
                                <th>Defective Quantity</th>
                                <th>Defect Date</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Days Outstanding</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="defective-tbody">
                            @include('inventory-status.partials.defective_table_body', ['defectiveItems' => $defectiveItems])
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:18px;display:flex;justify-content:flex-end;">
                    {{ $defectiveItems->withQueryString()->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX for search
    const searchInput = document.querySelector('input[name="search"]');
    const tbody = document.getElementById('defective-tbody');
    let timer = null;
    if (searchInput && tbody) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                fetch(`/inventory-status/ajax-search?search=${encodeURIComponent(searchInput.value)}`)
                    .then(res => res.json())
                    .then(data => {
                        tbody.innerHTML = data.html;
                    })
                    .catch(error => console.error('Search error:', error));
            }, 300);
        });
    }

    // AJAX for repair and dispose forms
    document.querySelectorAll('.repair-form, .dispose-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = this.action;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Processing...';

            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.textContent = originalText;
                if (data.success) {
                    // Update table body
                    document.getElementById('defective-tbody').innerHTML = data.html;
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.textContent = data.success;
                    document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                    setTimeout(() => alert.remove(), 3000);
                } else {
                    // Show error message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger';
                    alert.textContent = data.error || 'An error occurred.';
                    document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                    setTimeout(() => alert.remove(), 3000);
                }
            })
            .catch(error => {
                button.disabled = false;
                button.textContent = originalText;
                console.error('Error:', error);
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.textContent = 'Network error occurred.';
                document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                setTimeout(() => alert.remove(), 3000);
            });
        });
    });

    // AJAX for return item form
    document.querySelectorAll('.return-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const action = this.action;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Processing...';

            fetch(action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.textContent = originalText;
                if (data.success) {
                    // Update table body
                    document.getElementById('borrowed-tbody').innerHTML = data.html;
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.textContent = data.success;
                    document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                    setTimeout(() => alert.remove(), 3000);
                } else {
                    // Show error message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger';
                    alert.textContent = data.error || 'An error occurred.';
                    document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                    setTimeout(() => alert.remove(), 3000);
                }
            })
            .catch(error => {
                button.disabled = false;
                button.textContent = originalText;
                console.error('Error:', error);
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.textContent = 'Network error occurred.';
                document.querySelector('.main-content').insertBefore(alert, document.querySelector('.wp-card'));
                setTimeout(() => alert.remove(), 3000);
            });
        });
    });
});
</script>
@endsection