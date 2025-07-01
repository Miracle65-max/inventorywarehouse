@extends('layouts.app')
{{-- @include('components.header') --}}

@section('content')
<div class="wp-container">
    <div class="wp-header">
        <div class="wp-header-content">
            <h1 class="wp-title">User Management</h1>
            <div class="wp-actions">
                <button onclick="toggleCreateForm()" class="wp-button wp-button-primary">
                    <span class="dashicons dashicons-plus-alt"></span>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="wp-notice wp-notice-success">
            <span class="dashicons dashicons-yes-alt"></span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="wp-notice wp-notice-error">
            <span class="dashicons dashicons-warning"></span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Create User Form -->
    <div id="createForm" class="wp-card" style="display: none; margin-bottom: 24px;">
        <div class="wp-card-header">
            <h2 class="wp-card-title">Create New User</h2>
            <p class="wp-card-description">Add a new user to the system</p>
        </div>
        <div class="wp-card-body">
            <form method="POST" action="{{ route('user-management.store') }}" class="wp-form">
                @csrf
                <div class="wp-form-grid">
                    <div class="wp-form-field">
                        <label class="wp-label">Full Name <span class="wp-required">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" 
                               class="wp-input" required maxlength="255">
                        @error('full_name')
                            <div class="wp-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="wp-form-field">
                        <label class="wp-label">Username <span class="wp-required">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" 
                               class="wp-input" required maxlength="50">
                        @error('username')
                            <div class="wp-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="wp-form-field">
                        <label class="wp-label">Email <span class="wp-required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="wp-input" required maxlength="255">
                        @error('email')
                            <div class="wp-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="wp-form-field">
                        <label class="wp-label">Role <span class="wp-required">*</span></label>
                        <select name="role" class="wp-select" required>
                            <option value="">Select Role</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            @if(auth()->check() && auth()->user()->role === 'super_admin')
                                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            @endif
                        </select>
                        @error('role')
                            <div class="wp-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="wp-form-field">
                        <label class="wp-label">Password <span class="wp-required">*</span></label>
                        <input type="password" name="password" class="wp-input" required minlength="8">
                        @error('password')
                            <div class="wp-field-error">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="wp-form-field">
                        <label class="wp-label">Confirm Password <span class="wp-required">*</span></label>
                        <input type="password" name="password_confirmation" class="wp-input" required minlength="8">
                    </div>
                </div>
                
                <div class="wp-form-actions">
                    <button type="submit" class="wp-button wp-button-primary">
                        <span class="dashicons dashicons-plus-alt"></span>
                        Create User
                    </button>
                    <button type="button" class="wp-button wp-button-secondary" onclick="toggleCreateForm()">
                        <span class="dashicons dashicons-no-alt"></span>
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- All Users -->
    <div class="wp-card">
        <div class="wp-card-header">
            <h2 class="wp-card-title">All Users ({{ $users->total() }})</h2>
            <p class="wp-card-description">Manage all system users</p>
        </div>
        <div class="wp-card-body">
            @if($users->isEmpty())
                <div class="wp-empty-state">
                    <span class="dashicons dashicons-groups"></span>
                    <h3>No users found</h3>
                    <p>Get started by creating your first user.</p>
                </div>
            @else
                <div class="wp-table-container">
                    <table class="wp-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Created</th>
                                @if(auth()->check() && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin'))
                                <th class="wp-text-center">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="wp-user-info">
                                        <x-user-avatar :user="$user" size="sm" class="wp-user-avatar" />
                                        <div style="display: flex; flex-direction: column; justify-content: center;">
                                            <span class="wp-user-name">{{ $user->full_name ?? $user->name }}</span>
                                            <span class="wp-user-meta">{{ $user->username ?? 'No username' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="wp-badge wp-badge-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'success') }}">
                                        {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="wp-badge wp-badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ strtoupper($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->last_login ? $user->last_login->format('M j, Y g:i A') : 'Never' }}</td>
                                <td>{{ $user->created_at->format('M j, Y') }}</td>
                                @if(auth()->check() && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin'))
                                <td class="wp-text-center">
                                    @if($user->id !== auth()->id())
                                    <div class="wp-action-buttons">
                                        <a href="{{ route('user-management.show', $user) }}" class="wp-button wp-button-secondary wp-button-small">
                                            <span class="dashicons dashicons-visibility"></span> View
                                        </a>
                                        @if((auth()->user()->role === 'super_admin') || ($user->role !== 'super_admin'))
                                        <a href="{{ route('user-management.edit', $user) }}" class="wp-button wp-button-primary wp-button-small">
                                            <span class="dashicons dashicons-edit"></span> Edit
                                        </a>
                                        @if($user->status === 'active')
                                        <form method="POST" action="{{ route('user-management.suspend', $user) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="wp-button wp-button-warning wp-button-small"
                                                    onclick="return confirm('Are you sure you want to suspend this user?')">
                                                <span class="dashicons dashicons-pause"></span> Suspend
                                            </button>
                                        </form>
                                        @else
                                        <form method="POST" action="{{ route('user-management.approve', $user) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="wp-button wp-button-success wp-button-small">
                                                <span class="dashicons dashicons-yes-alt"></span> Activate
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('user-management.destroy', $user) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="wp-button wp-button-danger wp-button-small">
                                                <span class="dashicons dashicons-trash"></span> Delete
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    @else
                                        <span class="wp-badge wp-badge-secondary">CURRENT USER</span>
                                    @endif
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="wp-pagination">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* WordPress-inspired Design System */
.wp-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    line-height: 1.6;
    color: #1d2327;
    background: #f0f0f1;
    min-height: 100vh;
}

/* Header */
.wp-header {
    background: #fff;
    border-bottom: 1px solid #c3c4c7;
    margin: 0 -20px 20px -20px;
    padding: 0 20px;
}

.wp-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.wp-title {
    font-size: 23px;
    font-weight: 400;
    margin: 0;
    color: #1d2327;
}

/* Notices */
.wp-notice {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 4px;
    border-left: 4px solid;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.wp-notice-success {
    background: #f0f6fc;
    border-left-color: #00a32a;
    color: #1d2327;
}

.wp-notice-error {
    background: #fcf0f1;
    border-left-color: #d63638;
    color: #1d2327;
}

/* Cards */
.wp-card {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    margin-bottom: 20px;
}

.wp-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #c3c4c7;
    background: #f6f7f7;
}

.wp-card-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: #1d2327;
}

.wp-card-description {
    margin: 0;
    color: #646970;
    font-size: 14px;
}

.wp-card-body {
    padding: 24px;
}

/* Form */
.wp-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.wp-form-field {
    margin-bottom: 16px;
}

.wp-label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #1d2327;
    font-size: 14px;
}

.wp-required {
    color: #d63638;
}

.wp-input, .wp-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    line-height: 1.4;
    transition: border-color 0.15s ease-in-out;
}

.wp-input:focus, .wp-select:focus {
    border-color: #2271b1;
    outline: none;
    box-shadow: 0 0 0 1px #2271b1;
}

.wp-field-error {
    color: #d63638;
    font-size: 12px;
    margin-top: 4px;
}

/* Buttons - Sharp WordPress Style */
.wp-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1px solid;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    line-height: 1.4;
    min-height: 30px;
}

.wp-button-primary {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
}

.wp-button-primary:hover {
    background: #135e96;
    border-color: #135e96;
    color: #fff;
}

.wp-button-secondary {
    background: #f6f7f7;
    border-color: #8c8f94;
    color: #1d2327;
}

.wp-button-secondary:hover {
    background: #f0f0f1;
    border-color: #646970;
    color: #1d2327;
}

.wp-button-success {
    background: #00a32a;
    border-color: #00a32a;
    color: #fff;
}

.wp-button-success:hover {
    background: #008a20;
    border-color: #008a20;
    color: #fff;
}

.wp-button-warning {
    background: #dba617;
    border-color: #dba617;
    color: #fff;
}

.wp-button-warning:hover {
    background: #c08a00;
    border-color: #c08a00;
    color: #fff;
}

.wp-button-danger {
    background: #d63638;
    border-color: #d63638;
    color: #fff;
}

.wp-button-danger:hover {
    background: #b32d2e;
    border-color: #b32d2e;
    color: #fff;
}

.wp-button-small {
    padding: 6px 12px;
    font-size: 12px;
    min-height: 26px;
}

.wp-form-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid #c3c4c7;
}

/* Table */
.wp-table-container {
    overflow-x: auto;
}

.wp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.wp-table th {
    background: #f6f7f7;
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #1d2327;
    border-bottom: 1px solid #c3c4c7;
    font-size: 13px;
}

.wp-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #c3c4c7;
    color: #1d2327;
    vertical-align: top;
}

.wp-table tr:hover {
    background: #f6f7f7;
}

.wp-text-center {
    text-align: center;
}

/* User Info */
.wp-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-height: 48px;
}

.wp-user-avatar {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50%;
    object-fit: cover;
}

.wp-user-name {
    font-weight: 600;
    color: #1d2327;
    margin-bottom: 2px;
}

.wp-user-meta {
    font-size: 12px;
    color: #646970;
}

/* Badges */
.wp-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.wp-badge-success {
    background: #f0f6fc;
    color: #00a32a;
    border: 1px solid #b8e6bf;
}

.wp-badge-warning {
    background: #fef9c3;
    color: #b45309;
    border: 1px solid #fde68a;
}

.wp-badge-danger {
    background: #fcf0f1;
    color: #d63638;
    border: 1px solid #fccfd0;
}

.wp-badge-secondary {
    background: #f6f7f7;
    color: #646970;
    border: 1px solid #c3c4c7;
}

/* Action Buttons */
.wp-action-buttons {
    display: flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

/* Empty State */
.wp-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #646970;
}

.wp-empty-state .dashicons {
    font-size: 48px;
    margin-bottom: 16px;
    display: block;
}

.wp-empty-state h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
    color: #1d2327;
}

.wp-empty-state p {
    margin: 0;
    font-size: 14px;
}

/* Pagination */
.wp-pagination {
    margin-top: 24px;
    text-align: center;
}

/* Icons */
.dashicons {
    font-family: dashicons;
    font-size: 16px;
    line-height: 1;
}

.dashicons-plus-alt:before { content: "➕"; }
.dashicons-yes-alt:before { content: "✓"; }
.dashicons-warning:before { content: "⚠"; }
.dashicons-no-alt:before { content: "✕"; }
.dashicons-visibility:before { content: "👁"; }
.dashicons-edit:before { content: "✏"; }
.dashicons-pause:before { content: "⏸"; }
.dashicons-trash:before { content: "🗑"; }
.dashicons-groups:before { content: "👥"; }

/* Responsive */
@media (max-width: 768px) {
    .wp-form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .wp-header-content {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .wp-action-buttons {
        flex-direction: column;
        gap: 4px;
    }
    
    .wp-table th,
    .wp-table td {
        padding: 8px 12px;
        font-size: 13px;
    }
}
</style>

<script>
function toggleCreateForm() {
    const form = document.getElementById('createForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}
</script>
@endsection
