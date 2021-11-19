<?php

namespace App\Policies;

use App\Models\InventoryService;
use App\Models\Privilege;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryServicePolicy
{
    use HandlesAuthorization;

        /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
    }
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, InventoryService $inventoryService)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, InventoryService $inventoryService)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN]);
    }
}
