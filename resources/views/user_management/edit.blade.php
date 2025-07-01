@extends('layouts.app')
{{-- @include('components.header') --}}

@section('content')
<div class="main-content" style="padding:2rem;background:#f3f4f6;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
        <h1 style="color:#111827;font-size:1.875rem;font-weight:600;margin:0;">Edit User</h1>
        <a href="{{ route('user-management.index') }}" 
           style="background:#6b7280;color:white;padding:0.75rem 1.5rem;text-decoration:none;border-radius:0.375rem;font-weight:500;transition:background-color 0.2s;">
            Back to Users
        </a>
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

    <div class="card" style="background:white;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1),0 2px 4px -1px rgba(0,0,0,0.06);overflow:hidden;max-width:800px;margin:0 auto;">
        <div class="card-header" style="padding:1.25rem 1.5rem;background:white;border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0;color:#111827;font-size:1.125rem;font-weight:600;">Edit User: {{ $user->full_name ?? $user->name ?? $user->username ?? $user->email }}</h3>
        </div>
        <div class="card-body" style="padding:1.5rem;">
            <form method="POST" action="{{ route('user-management.update', $user) }}" style="display:grid;gap:1.5rem;">
                @csrf
                @method('PUT')
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name ?? $user->name) }}" 
                               style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;"
                               placeholder="Enter full name" required>
                        @error('full_name')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Username *</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                               style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;"
                               placeholder="Enter username" required>
                        @error('username')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;"
                               placeholder="Enter email address" required>
                        @error('email')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Role *</label>
                        <select name="role" 
                                style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;" required>
                            <option value="">Select Role</option>
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Warehouse Staff)</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Manager)</option>
                            @if(auth()->check() && auth()->user()->role === 'super_admin')
                                <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin (Owner)</option>
                            @endif
                        </select>
                        @error('role')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1rem;">
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Status *</label>
                        <select name="status" 
                                style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" 
                               style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;"
                               placeholder="Enter new password" minlength="8">
                        @error('password')
                            <div style="color:#dc2626;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label style="display:block;font-size:0.875rem;font-weight:500;color:#374151;margin-bottom:0.5rem;">Confirm New Password</label>
                    <input type="password" name="password_confirmation" 
                           style="width:100%;padding:0.75rem;border:1px solid #d1d5db;border-radius:0.375rem;font-size:0.875rem;transition:border-color 0.2s;"
                           placeholder="Confirm new password" minlength="8">
                </div>
                
                <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1rem;">
                    <a href="{{ route('user-management.index') }}" 
                       style="background:#6b7280;color:white;padding:0.75rem 1.5rem;text-decoration:none;border-radius:0.375rem;font-weight:500;transition:background-color 0.2s;">
                        Cancel
                    </a>
                    <button type="submit" 
                            style="background:#136735;color:white;padding:0.75rem 1.5rem;border:none;border-radius:0.375rem;font-weight:500;cursor:pointer;transition:background-color 0.2s;">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
