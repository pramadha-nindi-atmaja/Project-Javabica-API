<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxonomyPolicy
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

    public function show(User $user) {
     
        if ($user->can('taxonomy_show')) {
            
            return true;
        }

    }
    public function create(User $user) {

        if ($user->can('taxonomy_create')) {
            return true;
        }

    }
    public function update(User $user) {

        if ($user->can('taxonomy_update')) {
            return true;
        }
        
    }
    public function destroy(User $user) {

        if ($user->can('taxonomy_destroy')) {
            return true;
        }
        
    }
   
}
