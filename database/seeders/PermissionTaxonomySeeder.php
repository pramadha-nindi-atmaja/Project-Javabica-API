<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $PermissionData = array( 
            //== taxonomy
            [
             'id'         => 6,
             'name'       => 'taxonomy_access',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 7,
             'name'       => 'taxonomy_create',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 8,
             'name'       => 'taxonomy_update',
             'guard_name' => 'sanctum',
             'created_at' => now(),
             'updated_at' => now(),
         ],
         [
             'id'         => 9,
             'name'       => 'taxonomy_destroy',
             'guard_name' => 'sanctum',
              'created_at' => now(),
              'updated_at' => now(),
         ],
         [
             'id'         => 10,
             'name'       => 'taxonomy_show',
             'guard_name' => 'sanctum',
             'created_at' => now(),
             'updated_at' => now(),
         ],
      
 
 
         );
         
       
         Permission::insert($PermissionData);
    }
}
