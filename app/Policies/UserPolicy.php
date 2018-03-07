<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization, AllowAdmin;

    /**
     * Determine whether the user can view the user.
     *
     * @param  User $user
     * @param User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->id == $model->id;
    }
}
