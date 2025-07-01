<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(['super_admin']);
    }

    public function delete(User $user, Notification $notification): bool
    {
        return $user->hasRole(['super_admin']);
    }
}
