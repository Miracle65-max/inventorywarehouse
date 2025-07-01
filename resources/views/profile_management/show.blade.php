@extends('layouts.app')

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin:0;">User Profile</h1>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('profile-management.index') }}" class="wp-sharp-btn wp-sharp-btn-secondary">Back to Profiles</a>
            <a href="{{ route('profile-management.edit', $user) }}" class="wp-sharp-btn wp-sharp-btn-primary">Edit Profile</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;border:1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fee2e2;color:#dc2626;padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;border:1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(400px,1fr));gap:1.5rem;">
        <!-- Profile Information Card -->
        <div class="wp-card">
            <div class="wp-card-header">
                <h3>Profile Information</h3>
            </div>
            <div class="wp-card-body">
                <div class="profile-header">
                    <x-user-avatar :user="$user" size="sm" class="wp-user-avatar" />
                    <div class="profile-info">
                        <h2>{{ $user->full_name ?? $user->name }}</h2>
                        <p>{{ $user->username ?? 'No username' }}</p>
                    </div>
                </div>
                
                <div class="profile-fields">
                    <div class="field-group">
                        <label>Email Address</label>
                        <div class="field-value">{{ $user->email }}</div>
                    </div>
                    
                    @if($user->phone)
                    <div class="field-group">
                        <label>Phone Number</label>
                        <div class="field-value">{{ $user->phone }}</div>
                    </div>
                    @endif
                    
                    @if($user->address)
                    <div class="field-group">
                        <label>Address</label>
                        <div class="field-value">{{ $user->address }}</div>
                    </div>
                    @endif
                    
                    @if($user->department)
                    <div class="field-group">
                        <label>Department</label>
                        <div class="field-value">{{ $user->department }}</div>
                    </div>
                    @endif
                    
                    @if($user->position)
                    <div class="field-group">
                        <label>Position</label>
                        <div class="field-value">{{ $user->position }}</div>
                    </div>
                    @endif
                    
                    @if($user->bio)
                    <div class="field-group">
                        <label>Bio</label>
                        <div class="field-value">{{ $user->bio }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Account Information Card -->
        <div class="wp-card">
            <div class="wp-card-header">
                <h3>Account Information</h3>
            </div>
            <div class="wp-card-body">
                <div class="profile-fields">
                    <div class="field-group">
                        <label>Role</label>
                        <div class="field-value">
                            <span class="wp-badge wp-badge-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'success') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="field-group">
                        <label>Status</label>
                        <div class="field-value">
                            <span class="wp-badge wp-badge-{{ $user->status === 'active' ? 'success' : ($user->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="field-group">
                        <label>Account Age</label>
                        <div class="field-value">{{ $stats['account_age'] }} days</div>
                    </div>
                    
                    <div class="field-group">
                        <label>Last Login</label>
                        <div class="field-value">{{ $stats['last_login'] }}</div>
                    </div>
                    
                    <div class="field-group">
                        <label>Profile Completeness</label>
                        <div class="field-value">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $stats['profile_completeness'] }}%;background:{{ $stats['profile_completeness'] >= 80 ? '#00a32a' : ($stats['profile_completeness'] >= 60 ? '#dba617' : '#d63638') }};"></div>
                                <span class="progress-text">{{ $stats['profile_completeness'] }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="field-group">
                        <label>Last Updated</label>
                        <div class="field-value">{{ $stats['last_updated'] }}</div>
                    </div>
                    
                    <div class="field-group">
                        <label>Created Date</label>
                        <div class="field-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    @if(auth()->check() && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin') && $user->id !== auth()->id())
    <div class="wp-card" style="margin-top:1.5rem;">
        <div class="wp-card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="wp-card-body">
            <div class="action-buttons">
                <a href="{{ route('user-management.show', $user) }}" class="wp-sharp-btn wp-sharp-btn-primary">View User Details</a>
                <a href="{{ route('user-management.edit', $user) }}" class="wp-sharp-btn wp-sharp-btn-success">Edit User Account</a>
                <a href="{{ route('profile-management.export', $user) }}" class="wp-sharp-btn wp-sharp-btn-warning">Export Profile</a>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* WordPress-inspired Card Styles */
.wp-card {
    background: white;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    overflow: hidden;
}

.wp-card-header {
    padding: 12px 16px;
    background: #f6f7f7;
    border-bottom: 1px solid #c3c4c7;
}

.wp-card-header h3 {
    margin: 0;
    color: #1d2327;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.4;
}

.wp-card-body {
    padding: 16px;
}

/* Profile Header */
.profile-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0f0f1;
}

.wp-user-avatar {
    width: 48px !important;
    height: 48px !important;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #c3c4c7;
}

.profile-info h2 {
    margin: 0;
    color: #1d2327;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.4;
}

.profile-info p {
    margin: 4px 0 0 0;
    color: #646970;
    font-size: 13px;
    line-height: 1.4;
}

/* Profile Fields */
.profile-fields {
    display: grid;
    gap: 16px;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field-group label {
    font-size: 13px;
    font-weight: 500;
    color: #1d2327;
    line-height: 1.4;
}

.field-value {
    padding: 8px 12px;
    background: #f6f7f7;
    border: 1px solid #c3c4c7;
    border-radius: 3px;
    color: #1d2327;
    font-size: 13px;
    line-height: 1.4;
    min-height: 20px;
}

/* WordPress Badges */
.wp-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 500;
    line-height: 1.4;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.wp-badge-success {
    background: #00a32a;
    color: #fff;
}

.wp-badge-warning {
    background: #dba617;
    color: #fff;
}

.wp-badge-danger {
    background: #d63638;
    color: #fff;
}

/* Progress Bar */
.progress-bar {
    display: flex;
    align-items: center;
    gap: 8px;
}

.progress-fill {
    flex: 1;
    height: 6px;
    background: #00a32a;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 11px;
    font-weight: 500;
    color: #646970;
    min-width: 30px;
    text-align: right;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

/* WordPress Sharp Buttons */
.wp-sharp-btn {
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

.wp-sharp-btn-primary {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
}

.wp-sharp-btn-primary:hover {
    background: #135e96;
    border-color: #135e96;
    color: #fff;
}

.wp-sharp-btn-secondary {
    background: #f6f7f7;
    border-color: #8c8f94;
    color: #1d2327;
}

.wp-sharp-btn-secondary:hover {
    background: #f0f0f1;
    border-color: #646970;
    color: #1d2327;
}

.wp-sharp-btn-danger {
    background: #d63638;
    border-color: #d63638;
    color: #fff;
}

.wp-sharp-btn-danger:hover {
    background: #b32d2e;
    border-color: #b32d2e;
    color: #fff;
}

.wp-sharp-btn-warning {
    background: #dba617;
    border-color: #dba617;
    color: #fff;
}

.wp-sharp-btn-warning:hover {
    background: #c08a00;
    border-color: #c08a00;
    color: #fff;
}

.wp-sharp-btn-success {
    background: #00a32a;
    border-color: #00a32a;
    color: #fff;
}

.wp-sharp-btn-success:hover {
    background: #008a20;
    border-color: #008a20;
    color: #fff;
}
</style>
@endsection 