<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionModelHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PermissionData = array( 
            //== model has permission
            [
              'id'         => 11,
              'name'       => 'model_has_permission_access',
              'guard_name' => 'sanctum',
               'created_at' => now(),
               'updated_at' => now(),
          ],
          [
              'id'         => 12,
              'name'       => 'model_has_permission_create',
              'guard_name' => 'sanctum',
               'created_at' => now(),
               'updated_at' => now(),
          ],
          [
              'id'         => 13,
              'name'       => 'model_has_permission_destroy',
              'guard_name' => 'sanctum',
               'created_at' => now(),
               'updated_at' => now(),
          ],
          [
              'id'         => 14,
              'name'       => 'model_has_permission_show',
              'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
          ],
  
          );
          
        
          Permission::insert($PermissionData);
    }
}
