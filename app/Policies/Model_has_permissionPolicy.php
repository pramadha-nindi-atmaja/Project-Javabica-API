<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Model_has_permissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function destory(User $user) {

        if ($user->can('model_has_permission_destroy')) {
            return true;
        }
        
    }
    public function create(User $user) {

        if ($user->can('model_has_permission_create')) {
            return true;
        }
        
    }
}
