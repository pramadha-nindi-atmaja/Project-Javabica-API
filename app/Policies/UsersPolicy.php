<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
   
    public function __construct()
    {
       
    }
    public function destory(User $user) {
       
        if ($user->can('users_destroy')) {
            return true;
        }
        
    }
    public function create(User $user) {

        //everyone can create craete a user
        return true;
       
    }
    public function show(User $user) {

        if ($user->can('users_show')) {
            return true;
        } 

        return $user->id == $user->id;
    }
    public function update(User $user) {

        if ($user->can('users_update')) {
            return true;
        }

        return $user->id == $user->id;
        
    }
}
