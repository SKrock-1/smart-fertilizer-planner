<?php

namespace App\Policies;

use App\Models\FertilizerPlan;
use App\Models\User;

class FertilizerPlanPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'farmer'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'farmer'], true);
    }

    public function view(User $user, FertilizerPlan $fertilizerPlan): bool
    {
        return $user->role === 'admin' || $fertilizerPlan->landParcel->user_id === $user->id;
    }

    public function update(User $user, FertilizerPlan $fertilizerPlan): bool
    {
        return $user->role === 'admin' || $fertilizerPlan->landParcel->user_id === $user->id;
    }

    public function delete(User $user, FertilizerPlan $fertilizerPlan): bool
    {
        return $user->role === 'admin' || $fertilizerPlan->landParcel->user_id === $user->id;
    }
}
