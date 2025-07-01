<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;

class UserProfileController extends Controller
{
    public function show(Request $request, $id = null)
    {
        // Get user ID from URL or use current user
        $userId = $id ?: Auth::id();
        $user = User::findOrFail($userId);
        // Use policy-based authorization
        $this->authorize('view', $user);
        
        // Calculate stats
        $stats = [
            'recent_orders' => 0, // You can implement actual order counting logic here
            'stock_movements' => 0, // You can implement actual movement counting logic here
            'account_age' => $user->created_at ? $user->created_at->diffInDays(now()) : 0,
        ];
        
        // Get recent activities (you can implement actual activity logic here)
        $recentActivities = collect([]);
        
        return view('profile.user-profile', compact('user', 'stats', 'recentActivities'));
    }

    public function edit()
    {
        $user = Auth::user();
        $this->authorize('update', $user);
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)]{7,20}$/',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Full name is required.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'Email address is already taken by another user.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'username.unique' => 'Username is already taken by another user.',
            'phone.regex' => 'Please enter a valid phone number (7-20 characters, numbers, spaces, hyphens, plus, parentheses only).',
            'address.max' => 'Address cannot exceed 500 characters.',
            'bio.max' => 'Bio cannot exceed 500 characters.',
            'department.max' => 'Department cannot exceed 100 characters.',
            'position.max' => 'Position cannot exceed 100 characters.',
        ]);
        
        $user->update([
            'full_name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'bio' => $request->bio,
            'department' => $request->department,
            'position' => $request->position,
        ]);
        
        return redirect()->route('user-profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();
        $this->authorize('update', $user);
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Please select an image file.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPEG, PNG, JPG, or GIF file.',
            'avatar.max' => 'Avatar size must not exceed 2MB.',
        ]);

        try {
            // Get user's current info for audit trail
            $oldAvatar = $user->avatar;

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);

            return redirect()->route('user-profile.edit')->with('success', 'Avatar uploaded successfully!');

        } catch (Exception $e) {
            return back()->withErrors(['avatar' => 'Failed to upload avatar: ' . $e->getMessage()]);
        }
    }
}
