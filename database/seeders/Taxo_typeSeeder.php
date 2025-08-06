<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Taxo_typeSeeder extends Seeder
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
                'id' => 1,
                'taxo_type_name' => 'product_collection',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
            [
                'id' => 2,
                'taxo_type_name' => 'product_category',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
            [
                'id' => 3,
                'taxo_type_name' => 'product_subcategory',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
            [
                'id' => 4,
                'taxo_type_name' => 'product_attribute',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
            [
                'id' => 5,
                'taxo_type_name' => 'asset_collection',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
            [
                'id' => 6,
                'taxo_type_name' => 'product_attribute_value',
                'taxo_type_description' =>'',
                'created_at' =>now(),
                'updated_at' =>now(),
            ],
        );
        
        DB::table('taxo_types')->insert($data);
    }
}
