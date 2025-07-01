@extends('layouts.app')
@include('components.header')

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin:0;">User Details</h1>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('user-management.index') }}" 
               style="background:#6b7280;color:white;padding:0.75rem 1.5rem;text-decoration:none;border-radius:0.375rem;font-weight:500;transition:background-color 0.2s;">
                Back to Users
            </a>
            <a href="{{ route('user-management.edit', $user) }}" 
               style="background:#136735;color:white;padding:0.75rem 1.5rem;text-decoration:none;border-radius:0.375rem;font-weight:500;transition:background-color 0.2s;">
                Edit User
            </a>
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
        <!-- User Information Card -->
        <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">User Information</h3>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                <div style="display:grid;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Full Name</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $user->full_name ?? $user->name ?? 'Not provided' }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Username</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $user->username ?? 'Not provided' }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Email Address</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $user->email }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Role</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;">
                            <span style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                background:{{ $user->role === 'super_admin' ? '#fee2e2' : ($user->role === 'admin' ? '#fef3c7' : '#dcfce7') }};
                                color:{{ $user->role === 'super_admin' ? '#dc2626' : ($user->role === 'admin' ? '#92400e' : '#166534') }};">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Status</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;">
                            <span style="padding:0.375rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:500;
                                background:{{ $user->status === 'active' ? '#dcfce7' : ($user->status === 'pending' ? '#fef3c7' : '#fee2e2') }};
                                color:{{ $user->status === 'active' ? '#166534' : ($user->status === 'pending' ? '#92400e' : '#dc2626') }};">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics Card -->
        <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);overflow:hidden;">
            <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Account Statistics</h3>
            </div>
            <div class="card-body" style="padding:1.5rem;">
                <div style="display:grid;gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Account Age</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $stats['account_age'] }} days
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Last Login</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $stats['last_login'] }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Login Attempts</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $stats['login_attempts'] }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Created Date</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $user->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Last Updated</label>
                        <div style="padding:0.75rem;background:#f9fafb;border-radius:0.375rem;color:#111827;font-size:0.875rem;">
                            {{ $user->updated_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    @if(auth()->check() && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin') && $user->id !== auth()->id())
    <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);margin-top:1.5rem;overflow:hidden;">
        <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Quick Actions</h3>
        </div>
        <div class="card-body" style="padding:1.5rem;">
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                @if($user->status === 'active')
                <form method="POST" action="{{ route('user-management.suspend', $user) }}" style="display:inline;">
                    @csrf
                    <button type="submit" 
                            style="background:#f59e0b;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.375rem;font-weight:500;cursor:pointer;transition:background-color 0.2s;"
                            onclick="return confirm('Are you sure you want to suspend this user?')">
                        Suspend User
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('user-management.approve', $user) }}" style="display:inline;">
                    @csrf
                    <button type="submit" 
                            style="background:#10b981;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.375rem;font-weight:500;cursor:pointer;transition:background-color 0.2s;">
                        Activate User
                    </button>
                </form>
                @endif
                
                <form method="POST" action="{{ route('user-management.reset-login-attempts', $user) }}" style="display:inline;">
                    @csrf
                    <button type="submit" 
                            style="background:#3b82f6;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.375rem;font-weight:500;cursor:pointer;transition:background-color 0.2s;">
                        Reset Login Attempts
                    </button>
                </form>
                
                <form method="POST" action="{{ route('user-management.destroy', $user) }}" style="display:inline;" 
                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            style="background:#dc2626;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.375rem;font-weight:500;cursor:pointer;transition:background-color 0.2s;">
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 