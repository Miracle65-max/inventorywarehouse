@extends('layouts.app')
{{-- @include('components.header') --}}

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin:0;">Profile Management</h1>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('user-management.index') }}" class="wp-sharp-btn wp-sharp-btn-secondary">User Management</a>
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

    <!-- Profile Management Overview -->
    <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);margin-bottom:1.5rem;overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Profile Management Overview</h3>
        </div>
        <div class="card-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;">
                <div style="text-align:center;padding:1rem;background:#f9fafb;border-radius:0.5rem;">
                    <div style="font-size:2rem;margin-bottom:0.5rem;">👥</div>
                    <div style="font-size:1.5rem;font-weight:600;color:#111827;">{{ $users->total() }}</div>
                    <div style="color:#6b7280;font-size:0.875rem;">Total Users</div>
                </div>
                <div style="text-align:center;padding:1rem;background:#f9fafb;border-radius:0.5rem;">
                    <div style="font-size:2rem;margin-bottom:0.5rem;">✅</div>
                    <div style="font-size:1.5rem;font-weight:600;color:#111827;">{{ $users->where('status', 'active')->count() }}</div>
                    <div style="color:#6b7280;font-size:0.875rem;">Active Users</div>
                </div>
                <div style="text-align:center;padding:1rem;background:#f9fafb;border-radius:0.5rem;">
                    <div style="font-size:2rem;margin-bottom:0.5rem;">📅</div>
                    <div style="font-size:1.5rem;font-weight:600;color:#111827;">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
                    <div style="color:#6b7280;font-size:0.875rem;">New This Month</div>
                </div>
                <div style="text-align:center;padding:1rem;background:#f9fafb;border-radius:0.5rem;">
                    <div style="font-size:2rem;margin-bottom:0.5rem;">🔄</div>
                    <div style="font-size:1.5rem;font-weight:600;color:#111827;">{{ $users->where('updated_at', '>=', now()->subDays(7))->count() }}</div>
                    <div style="color:#6b7280;font-size:0.875rem;">Updated This Week</div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Profiles -->
    <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">All User Profiles ({{ $users->total() }})</h3>
        </div>
        <div class="card-body" style="padding:1.5rem;">
            @if($users->isEmpty())
                <div style="text-align:center;color:#6b7280;padding:2rem;">
                    <span style="font-size:3rem;">👥</span>
                    <p style="margin:1rem 0 0 0;font-size:1.125rem;">No user profiles found</p>
                </div>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">User</th>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Contact Info</th>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Role & Status</th>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Profile Info</th>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:left;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Last Activity</th>
                                <th style="background:#f9fafb;padding:0.75rem 1rem;text-align:center;font-size:0.875rem;font-weight:500;color:#374151;border-bottom:1px solid #e5e7eb;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                    <div style="display:flex;align-items:center;gap:0.75rem;">
                                        <x-user-avatar :user="$user" size="sm" class="wp-user-avatar" />
                                        <div>
                                            <div style="font-weight:500;color:#111827;">{{ $user->full_name ?? $user->name }}</div>
                                            <div style="font-size:0.75rem;color:#6b7280;">{{ $user->username ?? 'No username' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                    <div>{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div style="font-size:0.75rem;color:#6b7280;">📞 {{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                    <div style="margin-bottom:0.25rem;">
                                        <span style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                            background:{{ $user->role === 'super_admin' ? '#fee2e2' : ($user->role === 'admin' ? '#fef3c7' : '#dcfce7') }};
                                            color:{{ $user->role === 'super_admin' ? '#dc2626' : ($user->role === 'admin' ? '#92400e' : '#166534') }};">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                            background:{{ $user->status === 'active' ? '#dcfce7' : ($user->status === 'pending' ? '#fef3c7' : '#fee2e2') }};
                                            color:{{ $user->status === 'active' ? '#166534' : ($user->status === 'pending' ? '#92400e' : '#dc2626') }};">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                    @if($user->department)
                                        <div style="margin-bottom:0.25rem;">🏢 {{ $user->department }}</div>
                                    @endif
                                    @if($user->position)
                                        <div style="font-size:0.75rem;color:#6b7280;">💼 {{ $user->position }}</div>
                                    @endif
                                    @if($user->address)
                                        <div style="font-size:0.75rem;color:#6b7280;">📍 {{ Str::limit($user->address, 30) }}</div>
                                    @endif
                                </td>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;">
                                    <div style="margin-bottom:0.25rem;">{{ $user->last_login ? $user->last_login->format('M j, Y g:i A') : 'Never' }}</div>
                                    <div style="font-size:0.75rem;color:#6b7280;">Updated {{ $user->updated_at->diffForHumans() }}</div>
                                </td>
                                <td style="padding:1rem;font-size:0.875rem;color:#6b7280;border-bottom:1px solid #e5e7eb;text-align:center;">
                                    <div style="display:flex;gap:0.5rem;justify-content:center;flex-wrap:wrap;">
                                        <a href="{{ route('profile-management.show', $user) }}" class="wp-sharp-btn wp-sharp-btn-primary wp-sharp-btn-small">View</a>
                                        <a href="{{ route('profile-management.edit', $user) }}" class="wp-sharp-btn wp-sharp-btn-success wp-sharp-btn-small">Edit</a>
                                        <a href="{{ route('profile-management.export', $user) }}" class="wp-sharp-btn wp-sharp-btn-warning wp-sharp-btn-small">Export</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-top:1.5rem;">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* WordPress-inspired Button Styles */
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

.wp-sharp-btn-small {
    padding: 6px 12px;
    font-size: 11px;
    min-height: 24px;
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

/* Avatar Styles */
.wp-user-avatar {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #c3c4c7;
}
</style>
@endsection