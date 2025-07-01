<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::orderBy('full_name')->paginate(15);
        return view('profile_management.index', compact('users'));
    }

    public function show(User $user)
    {
        // Calculate profile stats
        $stats = [
            'account_age' => $user->created_at ? $user->created_at->diffInDays(now()) : 0,
            'last_login' => $user->last_login ? $user->last_login->diffForHumans() : 'Never',
            'profile_completeness' => $this->calculateProfileCompleteness($user),
            'last_updated' => $user->updated_at ? $user->updated_at->diffForHumans() : 'Never',
        ];
        
        return view('profile_management.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('profile_management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Comprehensive validation
        $validated = $request->validate([
            'full_name' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s\-\.\']+$/',
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)]{7,20}$/',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:500',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date|before:-16 years|after:-100 years',
            'user_role' => 'required|in:user,admin,super_admin',
            'status' => 'required|in:active,inactive,pending,suspended',
        ], [
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Full name must be at least 2 characters long.',
            'full_name.max' => 'Full name cannot exceed 100 characters.',
            'full_name.regex' => 'Full name contains invalid characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 100 characters.',
            'email.unique' => 'Email address is already taken by another user.',
            'phone.regex' => 'Please enter a valid phone number (7-20 characters, numbers, spaces, hyphens, plus, parentheses only).',
            'address.max' => 'Address cannot exceed 500 characters.',
            'bio.max' => 'Bio cannot exceed 500 characters.',
            'department.max' => 'Department cannot exceed 100 characters.',
            'position.max' => 'Position cannot exceed 100 characters.',
            'date_of_birth.before' => 'Age must be at least 16 years.',
            'date_of_birth.after' => 'Age must not exceed 100 years.',
            'user_role.required' => 'User role is required.',
            'user_role.in' => 'Invalid user role selected.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
        ]);

        // Prevent user from demoting themselves if they're the only super admin
        if ($user->id == Auth::id() && $validated['user_role'] !== 'super_admin') {
            $superAdminCount = User::where('role', 'super_admin')->count();
            if ($superAdminCount <= 1) {
                return back()->withErrors(['user_role' => 'Cannot change your role as you are the only Super Admin.']);
            }
        }

        try {
            DB::beginTransaction();

            // Get current user data for audit trail
            $oldUserData = $user->toArray();

            // Update user
            $user->update([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'bio' => $validated['bio'],
                'department' => $validated['department'],
                'position' => $validated['position'],
                'date_of_birth' => $validated['date_of_birth'],
                'role' => $validated['user_role'],
                'status' => $validated['status'],
            ]);

            // Find which fields were actually changed
            $changes = array_diff_assoc($user->fresh()->toArray(), $oldUserData);
            
            if (!empty($changes)) {
                $auditDetails = [
                    'user_id' => $user->id,
                    'full_name' => $validated['full_name'],
                    'changes' => $changes,
                    'old_data' => $oldUserData,
                    'new_data' => $user->fresh()->toArray()
                ];
                
                $this->logAuditTrail('Updated user profile information', 'Profile', $auditDetails);
            }

            // Update session if user updated their own profile
            if ($user->id == Auth::id()) {
                Auth::user()->update([
                    'full_name' => $validated['full_name'],
                    'role' => $validated['user_role'],
                ]);
            }

            DB::commit();
            return redirect()->route('profile-management.show', $user)->with('success', 'Profile updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error updating profile: ' . $e->getMessage()]);
        }
    }

    public function updateAvatar(Request $request, User $user)
    {
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

            // Log audit trail
            $auditDetails = [
                'user_id' => $user->id,
                'full_name' => $user->full_name,
                'action_type' => 'avatar_update',
                'old_avatar' => $oldAvatar ?? 'none',
                'new_avatar' => $avatarPath
            ];
            
            $this->logAuditTrail('Updated user avatar', 'Profile', $auditDetails);

            return redirect()->route('profile-management.edit', $user)->with('success', 'Avatar uploaded successfully!');

        } catch (Exception $e) {
            return back()->withErrors(['avatar' => 'Failed to upload avatar: ' . $e->getMessage()]);
        }
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password.different' => 'New password must be different from current password.',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        
        return redirect()->route('profile-management.show', $user)->with('success', 'Password changed successfully!');
    }

    public function deleteAvatar(User $user)
    {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->update(['avatar' => null]);

        return redirect()->route('profile-management.show', $user)->with('success', 'Avatar removed successfully!');
    }

    public function exportProfile(User $user)
    {
        $profileData = [
            'full_name' => $user->full_name ?? $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'bio' => $user->bio ?? 'Not provided',
            'phone' => $user->phone ?? 'Not provided',
            'address' => $user->address ?? 'Not provided',
            'department' => $user->department ?? 'Not provided',
            'position' => $user->position ?? 'Not provided',
            'date_of_birth' => $user->date_of_birth ?? 'Not provided',
            'created_at' => $user->created_at->format('F j, Y \a\t g:i A'),
            'last_login' => $user->last_login ? $user->last_login->format('F j, Y \a\t g:i A') : 'Never',
        ];

        return response()->json($profileData);
    }

    private function calculateProfileCompleteness(User $user)
    {
        $fields = [
            'full_name' => $user->full_name ?? $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'phone' => $user->phone,
            'address' => $user->address,
            'department' => $user->department,
            'position' => $user->position,
            'avatar' => $user->avatar,
        ];

        $filledFields = 0;
        $totalFields = count($fields);

        foreach ($fields as $field => $value) {
            if (!empty($value)) {
                $filledFields++;
            }
        }

        return round(($filledFields / $totalFields) * 100);
    }

    private function logAuditTrail($action, $module, $details)
    {
        try {
            AuditTrail::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => $module,
                'details' => json_encode($details),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Exception $e) {
            // Log error but don't break the main functionality
            \Log::error('Failed to log audit trail: ' . $e->getMessage());
        }
    }
} 