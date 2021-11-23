<?php

namespace App\Policies;

use App\Models\Privilege;
use App\Models\StoreItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreItemPolicy
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
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreItem  $storeItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StoreItem $storeItem)
    {
        // return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
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
     * @param  \App\Models\StoreItem  $storeItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StoreItem $storeItem)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StoreItem  $storeItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StoreItem $storeItem)
    {
        return in_array($user->privilege_id, [Privilege::IS_ADMIN, Privilege::IS_COORDINATOR]);
    }
}
