@extends('layouts.app')

@section('content')
<div class="container">
    <div class="main-content">
        <!-- WordPress-style Page Header -->
        <div class="page-header" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e1e1e1;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #1d2327;">Notifications</h1>
                    <p style="margin: 8px 0 0 0; color: #646970; font-size: 14px;">Manage your system notifications and alerts</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    @can('create', App\Models\Notification::class)
                        <button type="button" class="wp-button wp-button-primary" onclick="toggleCreateForm()" style="background: #2271b1; border: 1px solid #2271b1; color: white; padding: 8px 16px; border-radius: 3px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">+</span>
                            Create Notification
                        </button>
                    @endcan
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="wp-button wp-button-secondary" style="background: #f6f7f7; border: 1px solid #2271b1; color: #2271b1; padding: 8px 16px; border-radius: 3px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                <span style="font-size: 14px;">✓</span>
                                Mark All Read ({{ $unreadCount }})
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- WordPress-style Success/Error Messages -->
        @if(session('success'))
            <div class="wp-notice wp-notice-success" style="background: #d1e7dd; border-left: 4px solid #0f5132; color: #0f5132; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 3px 3px 0;">
                <span style="font-weight: 500;">✓</span> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="wp-notice wp-notice-error" style="background: #f8d7da; border-left: 4px solid #721c24; color: #721c24; padding: 12px 16px; margin-bottom: 20px; border-radius: 0 3px 3px 0;">
                <span style="font-weight: 500;">✕</span> {{ session('error') }}
            </div>
        @endif
        
        <!-- Create Notification Form - WordPress Style -->
        @can('create', App\Models\Notification::class)
        <div class="wp-card" id="createForm" style="display: none; margin-bottom: 30px; background: white; border: 1px solid #c3c4c7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <div class="wp-card-header" style="padding: 16px 20px; border-bottom: 1px solid #c3c4c7; background: #f6f7f7;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1d2327;">Create New Notification</h3>
            </div>
            <div class="wp-card-body" style="padding: 20px;">
                <form method="POST" action="{{ route('notifications.store') }}">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="wp-form-group">
                            <label class="wp-form-label" style="display: block; margin-bottom: 6px; font-weight: 500; color: #1d2327;">Title *</label>
                            <input type="text" name="title" class="wp-form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 14px; box-sizing: border-box;" required>
                        </div>
                        <div class="wp-form-group">
                            <label class="wp-form-label" style="display: block; margin-bottom: 6px; font-weight: 500; color: #1d2327;">Type</label>
                            <select name="type" class="wp-form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 14px; box-sizing: border-box;">
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="wp-form-group" style="margin-bottom: 20px;">
                        <label class="wp-form-label" style="display: block; margin-bottom: 6px; font-weight: 500; color: #1d2327;">Target User (Leave empty for all users)</label>
                        <select name="user_id" class="wp-form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 14px; box-sizing: border-box;">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->full_name }} ({{ $user->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="wp-form-group" style="margin-bottom: 20px;">
                        <label class="wp-form-label" style="display: block; margin-bottom: 6px; font-weight: 500; color: #1d2327;">Message *</label>
                        <textarea name="message" class="wp-form-control" rows="4" style="width: 100%; padding: 8px 12px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 14px; box-sizing: border-box; resize: vertical;" required></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 12px;">
                        <button type="submit" class="wp-button wp-button-primary" style="background: #2271b1; border: 1px solid #2271b1; color: white; padding: 8px 16px; border-radius: 3px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none;">
                            Create Notification
                        </button>
                        <button type="button" class="wp-button wp-button-secondary" onclick="toggleCreateForm()" style="background: #f6f7f7; border: 1px solid #2271b1; color: #2271b1; padding: 8px 16px; border-radius: 3px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none;">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endcan
        
        <!-- Notifications List - WordPress Style -->
        <div class="wp-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <div class="wp-card-header" style="padding: 16px 20px; border-bottom: 1px solid #c3c4c7; background: #f6f7f7;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1d2327;">My Notifications ({{ $notifications->count() }})</h3>
            </div>
            <div class="wp-card-body" style="padding: 0;">
                @if($notifications->isEmpty())
                    <div style="text-align: center; padding: 40px 20px; color: #646970;">
                        <div style="font-size: 48px; margin-bottom: 16px;">🔔</div>
                        <p style="margin: 0; font-size: 16px; color: #646970;">No notifications found.</p>
                        <p style="margin: 8px 0 0 0; font-size: 14px; color: #8c8f94;">You're all caught up!</p>
                    </div>
                @else
                    <div class="notifications-list">
                        @foreach($notifications as $notification)
                            <div class="wp-notification-item {{ $notification->is_read ? 'read' : 'unread' }}" 
                                 style="border-bottom: 1px solid #f0f0f1; padding: 20px; 
                                        background: {{ $notification->is_read ? '#fff' : '#f6f7f7' }};
                                        border-left: 4px solid {{ 
                                            $notification->type == 'success' ? '#00a32a' : 
                                            ($notification->type == 'warning' ? '#dba617' : 
                                            ($notification->type == 'danger' ? '#d63638' : '#2271b1')); 
                                        }};
                                        transition: background-color 0.2s ease;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                                            <span class="wp-badge wp-badge-{{ $notification->type }}" style="
                                                background: {{ 
                                                    $notification->type == 'success' ? '#00a32a' : 
                                                    ($notification->type == 'warning' ? '#dba617' : 
                                                    ($notification->type == 'danger' ? '#d63638' : '#2271b1')); 
                                                }};
                                                color: white;
                                                padding: 4px 8px;
                                                border-radius: 3px;
                                                font-size: 11px;
                                                font-weight: 500;
                                                text-transform: uppercase;
                                                letter-spacing: 0.5px;">
                                                {{ ucfirst($notification->type) }}
                                            </span>
                                            @if(!$notification->is_read)
                                                <span class="wp-badge wp-badge-new" style="background: #d63638; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 500;">NEW</span>
                                            @endif
                                            <span style="color: #646970; font-size: 13px;">
                                                {{ $notification->created_at->format('M j, Y g:i A') }}
                                            </span>
                                        </div>
                                        <h4 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 600; color: #1d2327;">
                                            {{ $notification->title }}
                                        </h4>
                                        <p style="margin: 0 0 12px 0; color: #646970; line-height: 1.5; font-size: 14px;">
                                            {!! nl2br(e($notification->message)) !!}
                                        </p>
                                        <span style="color: #8c8f94; font-size: 12px;">
                                            {{ $notification->user_id ? 'Personal notification' : 'General notification' }}
                                        </span>
                                    </div>
                                    <div style="display: flex; gap: 8px; flex-shrink: 0; margin-left: 20px;">
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('notifications.mark-read') }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                                <button type="submit" class="wp-button wp-button-small wp-button-success" style="background: #00a32a; border: 1px solid #00a32a; color: white; padding: 4px 12px; border-radius: 3px; font-size: 12px; font-weight: 500; cursor: pointer;">
                                                    Mark Read
                                                </button>
                                            </form>
                                        @endif
                                        @can('delete', $notification)
                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" 
                                              style="display: inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="wp-button wp-button-small wp-button-danger" style="background: #d63638; border: 1px solid #d63638; color: white; padding: 4px 12px; border-radius: 3px; font-size: 12px; font-weight: 500; cursor: pointer;">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function toggleCreateForm() {
        const form = document.getElementById('createForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>

<style>
    /* WordPress-style hover effects */
    .wp-notification-item:hover {
        background-color: #f6f7f7 !important;
    }
    
    .wp-button:hover {
        opacity: 0.9;
    }
    
    .wp-form-control:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: none;
    }
</style>
@endsection
