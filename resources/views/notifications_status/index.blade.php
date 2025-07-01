@extends('layouts.app')

@section('content')
<div class="container">
    <div class="main-content">
        <!-- WordPress-style Page Header -->
        <div class="page-header" style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e1e1e1;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #1d2327;">Notification Status</h1>
                    <p style="margin: 8px 0 0 0; color: #646970; font-size: 14px;">Monitor notification system status and statistics</p>
                </div>
                <div style="display: flex; gap: 12px;">
                    <a href="{{ route('notifications.index') }}" class="wp-button wp-button-secondary" style="background: #f6f7f7; border: 1px solid #2271b1; color: #2271b1; padding: 8px 16px; border-radius: 3px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        <span style="font-size: 14px;">📋</span>
                        View All Notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards - WordPress Style -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- Total Notifications -->
            <div class="wp-stat-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; padding: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="background: #2271b1; color: white; width: 48px; height: 48px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        📊
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 600; color: #1d2327; margin-bottom: 4px;">
                            {{ $totalNotifications ?? 0 }}
                        </div>
                        <div style="font-size: 14px; color: #646970;">Total Notifications</div>
                    </div>
                </div>
            </div>

            <!-- Unread Notifications -->
            <div class="wp-stat-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; padding: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="background: #d63638; color: white; width: 48px; height: 48px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        🔔
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 600; color: #1d2327; margin-bottom: 4px;">
                            {{ $unreadNotifications ?? 0 }}
                        </div>
                        <div style="font-size: 14px; color: #646970;">Unread Notifications</div>
                    </div>
                </div>
            </div>

            <!-- Today's Notifications -->
            <div class="wp-stat-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; padding: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="background: #00a32a; color: white; width: 48px; height: 48px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        📅
                    </div>
                    <div>
                        <div style="font-size: 24px; font-weight: 600; color: #1d2327; margin-bottom: 4px;">
                            {{ $todayNotifications ?? 0 }}
                        </div>
                        <div style="font-size: 14px; color: #646970;">Today's Notifications</div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="wp-stat-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; padding: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="background: #00a32a; color: white; width: 48px; height: 48px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        ✅
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 600; color: #1d2327; margin-bottom: 4px;">
                            Operational
                        </div>
                        <div style="font-size: 14px; color: #646970;">System Status</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Types Breakdown -->
        <div class="wp-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-bottom: 30px;">
            <div class="wp-card-header" style="padding: 16px 20px; border-bottom: 1px solid #c3c4c7; background: #f6f7f7;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1d2327;">Notification Types Breakdown</h3>
            </div>
            <div class="wp-card-body" style="padding: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <!-- Info Notifications -->
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f6f7f7; border-radius: 3px;">
                        <div style="background: #2271b1; color: white; width: 32px; height: 32px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            ℹ️
                        </div>
                        <div>
                            <div style="font-size: 18px; font-weight: 600; color: #1d2327;">{{ $infoCount ?? 0 }}</div>
                            <div style="font-size: 12px; color: #646970;">Info</div>
                        </div>
                    </div>

                    <!-- Success Notifications -->
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f6f7f7; border-radius: 3px;">
                        <div style="background: #00a32a; color: white; width: 32px; height: 32px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            ✅
                        </div>
                        <div>
                            <div style="font-size: 18px; font-weight: 600; color: #1d2327;">{{ $successCount ?? 0 }}</div>
                            <div style="font-size: 12px; color: #646970;">Success</div>
                        </div>
                    </div>

                    <!-- Warning Notifications -->
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f6f7f7; border-radius: 3px;">
                        <div style="background: #dba617; color: white; width: 32px; height: 32px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            ⚠️
                        </div>
                        <div>
                            <div style="font-size: 18px; font-weight: 600; color: #1d2327;">{{ $warningCount ?? 0 }}</div>
                            <div style="font-size: 12px; color: #646970;">Warning</div>
                        </div>
                    </div>

                    <!-- Error Notifications -->
                    <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: #f6f7f7; border-radius: 3px;">
                        <div style="background: #d63638; color: white; width: 32px; height: 32px; border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                            ❌
                        </div>
                        <div>
                            <div style="font-size: 18px; font-weight: 600; color: #1d2327;">{{ $errorCount ?? 0 }}</div>
                            <div style="font-size: 12px; color: #646970;">Error</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="wp-card" style="background: white; border: 1px solid #c3c4c7; border-radius: 3px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
            <div class="wp-card-header" style="padding: 16px 20px; border-bottom: 1px solid #c3c4c7; background: #f6f7f7;">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600; color: #1d2327;">Recent Activity</h3>
            </div>
            <div class="wp-card-body" style="padding: 0;">
                <div style="text-align: center; padding: 40px 20px; color: #646970;">
                    <div style="font-size: 48px; margin-bottom: 16px;">📈</div>
                    <p style="margin: 0; font-size: 16px; color: #646970;">Notification activity monitoring</p>
                    <p style="margin: 8px 0 0 0; font-size: 14px; color: #8c8f94;">Real-time statistics and system health</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* WordPress-style hover effects */
    .wp-stat-card:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .wp-button:hover {
        opacity: 0.9;
    }
</style>
@endsection
