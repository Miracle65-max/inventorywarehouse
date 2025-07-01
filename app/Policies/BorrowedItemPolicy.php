<?php

namespace App\Policies;

use App\Models\BorrowedItem;
use App\Models\User;

class BorrowedItemPolicy
{
    public function return(User $user, BorrowedItem $borrowedItem = null): bool
    {
        return $user->hasRole(['admin', 'super_admin']);
    }
}
