<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleHasPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
               
                'permission_id' => 1,
                'role_id' =>1,
           
            ],
        );

        DB::table('role_has_permissions')->insert($data);
    }
}
