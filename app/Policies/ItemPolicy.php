<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function restore(User $user, Item $item)
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function forceDelete(User $user, Item $item)
    {
        return $user->role === 'super_admin';
    }
} 