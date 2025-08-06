<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PermissionData = array( 
            [
                'id'         => 1,
                'name'       => 'users_access',
                'guard_name' => 'sanctum',
                 'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'users_create',
                'guard_name' => 'sanctum',
                 'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'name'       => 'users_update',
                'guard_name' => 'sanctum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'name'       => 'users_destroy',
                'guard_name' => 'sanctum',
                 'created_at' => now(),
                 'updated_at' => now(),
            ],
            [
                'id'         => 5,
                'name'       => 'users_show',
                'guard_name' => 'sanctum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
        
        Permission::insert($PermissionData);
    }
}
