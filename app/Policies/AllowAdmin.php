<?php

namespace App\Policies;


use App\Models\User;

trait AllowAdmin
{
    /**
     * Allow admins to perform every action on the model.
     *
     * @param User $user
     * @param $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->isAn('admin')) {
            return true;
        }
    }
}