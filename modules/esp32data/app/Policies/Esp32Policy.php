<?php

declare(strict_types=1);

namespace Modules\Esp32data\Policies;

use App\Models\User;
use App\Policies\BasePolicy;
use Modules\Esp32data\Models\Esp32;

class Esp32Policy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermission($user, 'esp32.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Esp32 $esp32): bool
    {
        return $this->checkPermission($user, 'esp32.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->checkPermission($user, 'esp32.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Esp32 $esp32): bool
    {
        return $this->checkPermission($user, 'esp32.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Esp32 $esp32): bool
    {
        return $this->checkPermission($user, 'esp32.delete');
    }

    /**
     * Determine whether the user can bulk delete models.
     */
    public function bulkDelete(User $user): bool
    {
        return $this->checkPermission($user, 'esp32.delete');
    }
}
