<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PriorAuthRequest;
use App\Models\User;

class PriorAuthRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->canAccessAdmin() ?? false;
    }

    public function view(User $user, PriorAuthRequest $priorAuthRequest): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Manager,
            UserRole::Staff,
            UserRole::Demo,
        ], true);
    }

    public function update(User $user, PriorAuthRequest $priorAuthRequest): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, PriorAuthRequest $priorAuthRequest): bool
    {
        return in_array($user->role, [UserRole::SuperAdmin, UserRole::Admin, UserRole::Demo], true);
    }
}
