<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PermissionData = array( 
            //== model has role
            [
             'id'         => 15,
             'name'       => 'model_has_role_access',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 16,
             'name'       => 'model_has_role_create',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 17,
             'name'       => 'model_has_role_destroy',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 18,
             'name'       => 'model_has_role_show',
             'guard_name' => 'sanctum',
             'created_at' => now(),
             'updated_at' => now(),
         ],     
         );
         
       
         Permission::insert($PermissionData);
    }
}
