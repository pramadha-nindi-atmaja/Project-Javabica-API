<?php

namespace Database\Seeders;


use App\Models\User_app;
use Illuminate\Database\Seeder;

class User_appsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User_app::factory(4)->create();
      
    }
}
