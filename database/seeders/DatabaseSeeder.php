<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            //start seeder permission
            PermissionUserSeeder::class,                 //1
            PermissionTaxonomySeeder::class,             //2
            PermissionModelHasPermissionSeeder::class,   //3
            PermissionModelHasRoleSeeder::class,         //4
            RoleHasPermission::class,
            ModelHasPermissionSeeder::class,
            ModelHasRoleSeeder::class,
            //seeder permission end
            Taxo_typeSeeder::class,
            UsersSeeder::class,
            User_appsSeeder::class,
            TaxonomySeeder::class,
            CategoryBlogSeeder::class
         
          
        ]);
       
    }
}
