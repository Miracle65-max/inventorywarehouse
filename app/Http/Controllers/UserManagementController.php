<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->paginate(15);
        
        // Get pending users for super admin
        $pendingUsers = collect();
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            $pendingUsers = User::where('status', 'pending')->orderBy('created_at')->get();
        }
        
        return view('user_management.index', compact('users', 'pendingUsers'));
    }

    public function create()
    {
        return view('user_management.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,super_admin',
        ], [
            'full_name.required' => 'Full name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'Username already exists.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Role is required.',
        ]);

        User::create([
            'full_name' => $validated['full_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        return redirect()->route('user-management.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        // Calculate user stats
        $stats = [
            'account_age' => $user->created_at ? $user->created_at->diffInDays(now()) : 0,
            'last_login' => $user->last_login ? $user->last_login->diffForHumans() : 'Never',
            'login_attempts' => $user->login_attempts ?? 0,
        ];
        
        return view('user_management.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $currentUser = auth()->check() ? auth()->user() : null;
        if ($user->role === 'super_admin' && (!$currentUser || $currentUser->role !== 'super_admin')) {
            return redirect()->route('user-management.index')->with('error', 'You are not allowed to edit a Super Admin account.');
        }
        $this->authorize('update', $user);
        return view('user_management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->check() ? auth()->user() : null;
        if ($user->role === 'super_admin' && (!$currentUser || $currentUser->role !== 'super_admin')) {
            return redirect()->route('user-management.index')->with('error', 'You are not allowed to update a Super Admin account.');
        }
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:user,admin,super_admin',
            'status' => 'required|in:active,inactive,suspended',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'full_name.required' => 'Full name is required.',
            'username.required' => 'Username is required.',
            'username.unique' => 'Username already exists.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'role.required' => 'Role is required.',
            'status.required' => 'Status is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $data = [
            'full_name' => $validated['full_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('user-management.index')->with('success', 'User updated successfully!');
    }

    public function suspend(User $user)
    {
        $currentUser = auth()->check() ? auth()->user() : null;
        if ($user->role === 'super_admin' && (!$currentUser || $currentUser->role !== 'super_admin')) {
            return redirect()->back()->with('error', 'You are not allowed to suspend a Super Admin account.');
        }
        if ($currentUser && $user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot suspend your own account.');
        }
        $this->authorize('update', $user);
        
        $user->update(['status' => 'suspended']);
        return redirect()->back()->with('success', 'User suspended successfully!');
    }

    public function approve(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['status' => 'active']);
        return redirect()->back()->with('success', 'User approved/activated successfully!');
    }

    public function changePassword(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'Password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user->update(['password' => Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    public function resetLoginAttempts(User $user)
    {
        $this->authorize('update', $user);
        
        $user->update(['login_attempts' => 0]);
        return redirect()->back()->with('success', 'Login attempts reset successfully!');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->check() ? auth()->user() : null;
        if ($user->role === 'super_admin' && (!$currentUser || $currentUser->role !== 'super_admin')) {
            return redirect()->back()->with('error', 'You are not allowed to delete a Super Admin account.');
        }
        if ($currentUser && $user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        $this->authorize('delete', $user);
        
        $user->delete();
        return redirect()->route('user-management.index')->with('success', 'User deleted successfully!');
    }
}
