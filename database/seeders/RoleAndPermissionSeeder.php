<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $RoleData = array(
            [
                'id'         => 1,
                'name'       => 'admin',
                'guard_name' => 'sanctum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'users',
                'guard_name' => 'sanctum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

      
        Role::insert($RoleData);
      
    }
}
