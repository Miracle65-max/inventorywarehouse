<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    public function viewAny(User $authUser)
    {
        return in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function view(User $authUser, User $user)
    {
        Log::debug('UserPolicy@view', [
            'authUser_id' => $authUser->id,
            'authUser_role' => $authUser->role,
            'user_id' => $user->id,
        ]);
        return $authUser->id === $user->id || in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function create(User $authUser)
    {
        return in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function update(User $authUser, User $user)
    {
        return $authUser->id === $user->id || in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function delete(User $authUser, User $user)
    {
        // Users cannot delete themselves
        if ($authUser->id === $user->id) {
            return false;
        }
        
        // Only super_admin and admin can delete users
        return in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function restore(User $authUser, User $user)
    {
        return in_array($authUser->role, ['super_admin', 'admin']);
    }

    public function forceDelete(User $authUser, User $user)
    {
        // Users cannot delete themselves
        if ($authUser->id === $user->id) {
            return false;
        }
        
        // Only super_admin can force delete users
        return $authUser->role === 'super_admin';
    }
}
