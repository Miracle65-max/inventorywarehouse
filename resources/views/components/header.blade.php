<style>
    body { padding-top: 70px !important; }
    /* Adjust 70px to your header height if needed */
</style>
<div class="header" style="z-index: 10000; position: fixed; top: 0; left: 0; width: 100%; pointer-events: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.07); background: #fff;">
    <div class="header-content" style="position: relative; z-index: 10001; pointer-events: auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
        <div class="header-left" style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ url('/') }}" class="logo" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: #333; font-weight: bold;">
                <img src="{{ asset('assets/images/sbt-logo.png') }}" alt="SBT Logo" style="height: 40px;">
                SBT Warehouse
            </a>
            <div id="phTime" class="ph-time" style="font-size: 14px; color: #666;"></div>
        </div>
        
        <div class="header-right" style="display: flex; align-items: center; gap: 20px;">
            <!-- Greetings moved to right -->
            @php
                $isGuest = !auth()->check();
                $user = $isGuest ? null : auth()->user();
                $name = $isGuest ? 'Guest' : ($user->full_name ?? $user->name ?? 'User');
                $role = $isGuest ? '' : ($user->role ?? '');
            @endphp
            <div class="user-greeting" style="font-size: 14px; color: #333;">
                Welcome, {{ $name }}
                @if($role)
                    <span style="color: #666;">({{ ucfirst(str_replace('_', ' ', $role)) }})</span>
                @endif
            </div>
            
            <!-- Notifications moved to right -->
            <div class="notification-dropdown" style="position: relative; z-index: 10003; pointer-events: auto;">
                <button onclick="toggleNotificationDropdown()" class="notification-bell" style="pointer-events: auto; background: none; border: none; cursor: pointer; position: relative; font-size: 18px;">
                    🔔
                    <span class="notification-badge" id="notificationBadge" style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; display: none;">0</span>
                </button>
                <div id="notificationDropdown" class="notification-dropdown-panel" style="z-index: 9999; position: absolute; right: 0; top: 40px; min-width: 340px; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,0.10); border: 1px solid #eee; border-radius: 0; display: none; pointer-events: auto;">
                    <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h4 style="margin: 0; font-size: 16px; color: #333;">Notifications</h4>
                            <div style="display: flex; gap: 10px;">
                                <button class="btn btn-sm btn-primary" style="font-size: 11px; padding: 4px 8px;" onclick="markAllNotificationsRead(event)">Mark All Read</button>
                                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-secondary" style="font-size: 11px; padding: 4px 8px; text-decoration: none;">View All</a>
                            </div>
                        </div>
                    </div>
                    <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
                        <div style="padding: 20px; text-align: center; color: #666;">
                            <span style="font-size: 24px;">🔔</span>
                            <p style="margin: 10px 0 0 0; font-size: 14px;">No notifications yet</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Avatar on the far right -->
            <x-user-avatar :user="$user" size="md" class="user-avatar" />
        </div>
    </div>
</div>
<script>
    // Philippine Time Display
    function updatePhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        const timeElement = document.getElementById('phTime');
        if (timeElement) {
            const now = new Date();
            timeElement.textContent = now.toLocaleString('en-PH', options);
        }
    }
    updatePhilippineTime();
    setInterval(updatePhilippineTime, 1000);

    // Notification dropdown
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            fetchNotifications();
        } else {
            dropdown.style.display = 'none';
        }
    }
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const bell = document.querySelector('.notification-bell');
        if (dropdown && bell && !dropdown.contains(event.target) && !bell.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Fetch notifications via AJAX
    function fetchNotifications() {
        fetch('/notifications/list')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('notificationList');
                const badge = document.getElementById('notificationBadge');
                if (data.notifications && data.notifications.length > 0) {
                    let html = '';
                    data.notifications.forEach(n => {
                        html += `<div class=\"notification-item${n.is_read ? '' : ' unread'}\" style=\"padding: 12px 16px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: flex-start; gap: 10px; background: ${n.is_read ? '#fff' : '#f6faff'}; cursor: pointer;\" onclick=\"handleNotificationClick(event, ${n.id}, ${n.is_read})\">
                            <div style=\"font-size: 18px; margin-top: 2px;\">${getNotificationIcon(n.type)}</div>
                            <div style=\"flex:1;\">
                                <div style=\"font-weight: 600; color: #333;\">${n.title}</div>
                                <div style=\"font-size: 13px; color: #666;\">${n.message}</div>
                                <div style=\"font-size: 11px; color: #aaa; margin-top: 2px;\">${n.created_at_human}</div>
                            </div>
                            ${!n.is_read ? `<button class=\"btn btn-xs btn-link\" style=\"font-size:11px; color:#007bff;\" onclick=\"event.stopPropagation(); markNotificationRead(event, ${n.id})\">Mark as read</button>` : ''}
                        </div>`;
                    });
                    list.innerHTML = html;
                    badge.textContent = data.unread_count || 0;
                    badge.style.display = (data.unread_count && data.unread_count > 0) ? 'flex' : 'none';
                } else {
                    list.innerHTML = `<div style=\"padding: 20px; text-align: center; color: #666;\"><span style=\"font-size: 24px;\">🔔</span><p style=\"margin: 10px 0 0 0; font-size: 14px;\">No notifications yet</p></div>`;
                    badge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
            });
    }
    
    function getNotificationIcon(type) {
        switch(type) {
            case 'info': return 'ℹ️';
            case 'success': return '✅';
            case 'warning': return '⚠️';
            case 'error': return '❌';
            default: return '🔔';
        }
    }
    
    function handleNotificationClick(event, id, isRead) {
        // If notification is already read, just redirect to notifications page
        if (isRead) {
            window.location.href = '{{ route("notifications.index") }}';
            return;
        }
        
        // If notification is unread, mark it as read first, then redirect
        event.stopPropagation();
        
        // Immediately update the UI for better user experience
        const notificationItem = event.currentTarget;
        const badge = document.getElementById('notificationBadge');
        
        // Update the notification item to show as read
        notificationItem.style.background = '#fff';
        notificationItem.style.cursor = 'pointer';
        
        // Decrease badge count immediately
        const currentCount = parseInt(badge.textContent) || 0;
        if (currentCount > 0) {
            badge.textContent = currentCount - 1;
            if (currentCount - 1 === 0) {
                badge.style.display = 'none';
            }
        }
        
        // Mark as read on server
        fetch(`/notifications/${id}/mark-read`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to notifications page after marking as read
                setTimeout(() => {
                    window.location.href = '{{ route("notifications.index") }}';
                }, 100);
            } else {
                // If failed, revert the UI changes
                notificationItem.style.background = '#f6faff';
                if (currentCount > 0) {
                    badge.textContent = currentCount;
                    badge.style.display = 'flex';
                }
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            // Revert UI changes on error
            notificationItem.style.background = '#f6faff';
            if (currentCount > 0) {
                badge.textContent = currentCount;
                badge.style.display = 'flex';
            }
        });
    }
    
    function markNotificationRead(event, id) {
        event.stopPropagation();
        
        // Immediately update the UI for better user experience
        const button = event.target;
        const notificationItem = button.closest('.notification-item');
        const badge = document.getElementById('notificationBadge');
        
        // Update the notification item to show as read
        if (notificationItem) {
            notificationItem.style.background = '#fff';
            button.style.display = 'none';
        }
        
        // Decrease badge count immediately
        const currentCount = parseInt(badge.textContent) || 0;
        if (currentCount > 0) {
            badge.textContent = currentCount - 1;
            if (currentCount - 1 === 0) {
                badge.style.display = 'none';
            }
        }
        
        fetch(`/notifications/${id}/mark-read`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the full list to ensure consistency
                fetchNotifications();
            } else {
                // If failed, revert the UI changes
                if (notificationItem) {
                    notificationItem.style.background = '#f6faff';
                    button.style.display = 'inline-block';
                }
                if (currentCount > 0) {
                    badge.textContent = currentCount;
                    badge.style.display = 'flex';
                }
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            // Revert UI changes on error
            if (notificationItem) {
                notificationItem.style.background = '#f6faff';
                button.style.display = 'inline-block';
            }
            if (currentCount > 0) {
                badge.textContent = currentCount;
                badge.style.display = 'flex';
            }
        });
    }
    
    function markAllNotificationsRead(event) {
        event.stopPropagation();
        
        // Immediately update the UI for better user experience
        const badge = document.getElementById('notificationBadge');
        const notificationItems = document.querySelectorAll('.notification-item');
        const markReadButtons = document.querySelectorAll('.notification-item .btn-link');
        
        // Hide badge immediately
        badge.style.display = 'none';
        badge.textContent = '0';
        
        // Update all notification items to show as read
        notificationItems.forEach(item => {
            item.style.background = '#fff';
        });
        
        // Hide all "Mark as read" buttons
        markReadButtons.forEach(button => {
            button.style.display = 'none';
        });
        
        fetch('/notifications/mark-all-read', { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the full list to ensure consistency
                fetchNotifications();
            } else {
                // If failed, revert the UI changes
                badge.style.display = 'flex';
                notificationItems.forEach(item => {
                    item.style.background = '#f6faff';
                });
                markReadButtons.forEach(button => {
                    button.style.display = 'inline-block';
                });
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
            // Revert UI changes on error
            badge.style.display = 'flex';
            notificationItems.forEach(item => {
                item.style.background = '#f6faff';
            });
            markReadButtons.forEach(button => {
                button.style.display = 'inline-block';
            });
        });
    }
    
    // Load notifications on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotifications();
    });
    
    // Auto-refresh badge every 30s
    setInterval(fetchNotifications, 30000);
</script>
