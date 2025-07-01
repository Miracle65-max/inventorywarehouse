@extends('layouts.app')
@section('title', 'Profile')
@section('content')
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

/* Content */
.wp-content {
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.wp-card {
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    background: #fff;
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

.wp-card-body {
    padding: 24px;
}

/* Layout */
.wp-profile-layout {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 32px;
    margin-top: 20px;
}

/* Avatar Section */
.wp-avatar-section {
    text-align: center;
}

.wp-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #c3c4c7;
    margin: 0 auto 16px auto;
}

.wp-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f6f7f7;
    border: 2px solid #c3c4c7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px auto;
    font-size: 48px;
    color: #646970;
}

.wp-profile-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 4px;
    color: #1d2327;
}

.wp-profile-username {
    color: #646970;
    margin-bottom: 16px;
    font-size: 14px;
}

.wp-profile-username.no-username {
    color: #d63638;
}

/* Badges */
.wp-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
    margin-right: 8px;
    margin-bottom: 8px;
}

.wp-badge-danger {
    background: #fcf0f1;
    color: #d63638;
    border: 1px solid #fccfd0;
}

.wp-badge-warning {
    background: #fef9c3;
    color: #b45309;
    border: 1px solid #fde68a;
}

.wp-badge-success {
    background: #f0f6fc;
    color: #00a32a;
    border: 1px solid #b8e6bf;
}

/* Bio Section */
.wp-profile-bio {
    margin-top: 20px;
    padding: 16px;
    background: #f6f7f7;
    border-radius: 4px;
    text-align: left;
    border: 1px solid #c3c4c7;
}

.wp-profile-bio h4 {
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 600;
    color: #1d2327;
}

.wp-profile-bio p {
    margin: 0;
    font-style: italic;
    color: #646970;
    font-size: 14px;
    line-height: 1.5;
}

/* Stats */
.wp-profile-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 20px;
}

.wp-stat-card {
    background: #f6f7f7;
    border-radius: 4px;
    padding: 16px;
    text-align: center;
    border: 1px solid #c3c4c7;
}

.wp-stat-number {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1d2327;
    margin-bottom: 4px;
}

.wp-stat-label {
    color: #646970;
    font-size: 12px;
    font-weight: 500;
}

/* Info Grid */
.wp-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.wp-info-field {
    margin-bottom: 16px;
}

.wp-info-field strong {
    display: block;
    font-weight: 600;
    color: #1d2327;
    font-size: 14px;
    margin-bottom: 4px;
}

.wp-info-field a {
    color: #2271b1;
    text-decoration: none;
}

.wp-info-field a:hover {
    color: #135e96;
    text-decoration: underline;
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

/* Activity List */
.wp-activity-list {
    margin-top: 16px;
}

.wp-activity-item {
    border-bottom: 1px solid #c3c4c7;
    padding: 12px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.wp-activity-item:last-child {
    border-bottom: none;
}

.wp-activity-content {
    flex: 1;
}

.wp-activity-title {
    font-weight: 600;
    color: #1d2327;
    font-size: 14px;
    margin-bottom: 4px;
}

.wp-activity-description {
    color: #646970;
    font-size: 13px;
    line-height: 1.4;
}

.wp-activity-time {
    color: #8c8f94;
    font-size: 12px;
    white-space: nowrap;
    margin-left: 16px;
}

.wp-no-activities {
    text-align: center;
    color: #646970;
    margin: 20px 0;
    font-style: italic;
}

/* Responsive */
@media (max-width: 768px) {
    .wp-profile-layout {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .wp-info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .wp-header-content {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .wp-profile-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}
</style>

<div class="wp-container">
    <div class="wp-header">
        <div class="wp-header-content">
            <h1 class="wp-title">{{ $user->id == auth()->id() ? 'My Profile' : 'User Profile' }}</h1>
            <div>
                @if(auth()->user() && auth()->user()->hasRole('super_admin'))
                    <a href="{{ route('profile-management.edit', $user) }}" class="wp-button wp-button-primary">Edit Profile</a>
                    <a href="{{ route('profile-management.index') }}" class="wp-button wp-button-secondary">Back to Management</a>
                @elseif($user->id == auth()->id())
                    <a href="{{ route('user-profile.edit') }}" class="wp-button wp-button-primary">Edit Profile</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="wp-content">
        <div class="wp-profile-layout">
            <!-- Profile Card -->
            <div class="wp-card">
                <div class="wp-card-body">
                    <div class="wp-avatar-section">
                        <x-user-avatar :user="$user" size="profile" class="wp-avatar" />
                        
                        <h2 class="wp-profile-name">{{ $user->full_name ?? $user->name }}</h2>
                        @if(!empty($user->username))
                            <p class="wp-profile-username">{{ $user->username }}</p>
                        @else
                            <p class="wp-profile-username no-username">No username</p>
                        @endif
                        
                        <div class="wp-profile-badges">
                            <span class="wp-badge wp-badge-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin' ? 'warning' : 'success') }}">
                                {{ ucfirst(str_replace('_', ' ', $user->role ?? 'user')) }}
                            </span>
                            <span class="wp-badge wp-badge-{{ $user->status == 'active' ? 'success' : ($user->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                        </div>
                        
                        @if(!empty($user->bio))
                            <div class="wp-profile-bio">
                                <h4>Bio</h4>
                                <p>{!! nl2br(e($user->bio)) !!}</p>
                            </div>
                        @endif
                        
                        <div class="wp-profile-stats">
                            <div class="wp-stat-card">
                                <div class="wp-stat-number">{{ $stats['recent_orders'] ?? 0 }}</div>
                                <div class="wp-stat-label">Orders</div>
                            </div>
                            <div class="wp-stat-card">
                                <div class="wp-stat-number">{{ $stats['stock_movements'] ?? 0 }}</div>
                                <div class="wp-stat-label">Movements</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Profile Information -->
            <div>
                <!-- Contact Information -->
                <div class="wp-card">
                    <div class="wp-card-header">
                        <h3 class="wp-card-title">Contact Information</h3>
                    </div>
                    <div class="wp-card-body">
                        <div class="wp-info-grid">
                            <div>
                                <div class="wp-info-field">
                                    <strong>Email</strong>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </div>
                                @if(!empty($user->phone))
                                    <div class="wp-info-field">
                                        <strong>Phone</strong>
                                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if(!empty($user->address))
                                    <div class="wp-info-field">
                                        <strong>Address</strong>
                                        {!! nl2br(e($user->address)) !!}
                                    </div>
                                @endif
                                @if(!empty($user->date_of_birth))
                                    <div class="wp-info-field">
                                        <strong>Date of Birth</strong>
                                        {{ \Carbon\Carbon::parse($user->date_of_birth)->format('F j, Y') }}
                                        ({{ \Carbon\Carbon::parse($user->date_of_birth)->age }} years old)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Work Information -->
                <div class="wp-card">
                    <div class="wp-card-header">
                        <h3 class="wp-card-title">Work Information</h3>
                    </div>
                    <div class="wp-card-body">
                        <div class="wp-info-grid">
                            <div>
                                @if(!empty($user->department))
                                    <div class="wp-info-field">
                                        <strong>Department</strong>
                                        {{ $user->department }}
                                    </div>
                                @endif
                                @if(!empty($user->position))
                                    <div class="wp-info-field">
                                        <strong>Position</strong>
                                        {{ $user->position }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="wp-info-field">
                                    <strong>Member Since</strong>
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y') }}
                                </div>
                                <div class="wp-info-field">
                                    <strong>Account Age</strong>
                                    {{ $stats['account_age'] ?? 0 }} days
                                </div>
                                <div class="wp-info-field">
                                    <strong>Last Login</strong>
                                    {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('F j, Y g:i A') : 'Never' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="wp-card">
                    <div class="wp-card-header">
                        <h3 class="wp-card-title">Recent Activities</h3>
                    </div>
                    <div class="wp-card-body">
                        @if(empty($recentActivities) || $recentActivities->isEmpty())
                            <p class="wp-no-activities">No recent activities found.</p>
                        @else
                            <div class="wp-activity-list">
                                @foreach($recentActivities as $activity)
                                    <div class="wp-activity-item">
                                        <div class="wp-activity-content">
                                            <div class="wp-activity-title">{{ $activity['activity_type'] }}</div>
                                            <div class="wp-activity-description">{{ $activity['description'] }}</div>
                                        </div>
                                        <div class="wp-activity-time">
                                            {{ \Carbon\Carbon::parse($activity['activity_date'])->format('M j, Y g:i A') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
