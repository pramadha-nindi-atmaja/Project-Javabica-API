<?php

namespace App\Providers;

use App\Models\Model_has_permission;
use App\Models\Model_has_Role;
use App\Models\Taxo_list;
use App\Models\User;
use App\Policies\Model_has_permissionPolicy;
use App\Policies\Model_has_rolePolicy;
use App\Policies\TaxonomyPolicy;
use App\Policies\UsersPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
   
         Taxo_list::class                => TaxonomyPolicy::class,
         Model_has_permission::class     => Model_has_permissionPolicy::class,
         Model_has_Role::class           => Model_has_rolePolicy::class,
         User::class                     => UsersPolicy::class,
       
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
