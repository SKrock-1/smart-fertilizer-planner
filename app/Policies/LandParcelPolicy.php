<?php

namespace App\Policies;

use App\Models\LandParcel;
use App\Models\User;

class LandParcelPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'farmer'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'farmer'], true);
    }

    public function view(User $user, LandParcel $landParcel): bool
    {
        return $user->role === 'admin' || $landParcel->user_id === $user->id;
    }

    public function update(User $user, LandParcel $landParcel): bool
    {
        return $user->role === 'admin' || $landParcel->user_id === $user->id;
    }

    public function delete(User $user, LandParcel $landParcel): bool
    {
        return $user->role === 'admin' || $landParcel->user_id === $user->id;
    }
}
