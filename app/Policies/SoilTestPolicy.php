<?php

namespace App\Policies;

use App\Models\SoilTest;
use App\Models\User;

class SoilTestPolicy
{
    public function view(User $user, SoilTest $soilTest): bool
    {
        return $user->role === 'admin' || $soilTest->landParcel->user_id === $user->id;
    }

    public function delete(User $user, SoilTest $soilTest): bool
    {
        return $user->role === 'admin' || $soilTest->landParcel->user_id === $user->id;
    }
}
