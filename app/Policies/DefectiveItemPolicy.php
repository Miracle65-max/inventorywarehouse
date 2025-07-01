<?php

namespace App\Policies;

use App\Models\DefectiveItem;
use App\Models\User;

class DefectiveItemPolicy
{
    public function repair(User $user, DefectiveItem $defectiveItem): bool
    {
        return $user->hasRole(['super_admin']);
    }

    public function dispose(User $user, DefectiveItem $defectiveItem): bool
    {
        return $user->hasRole(['super_admin']);
    }
}
