<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryBlog;

class CategoryBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'News',
                'description' => null,
                'slug' => 'news',
                'status' => true,
            ],
            [
                'name' => 'Partnership',
                'description' => null,
                'slug' => 'partnership',
                'status' => true,
            ],
            [
                'name' => 'Product and Offer',
                'description' => null,
                'slug' => 'product-and-offer',
                'status' => true,
            ],
            [
                'name' => 'Human Resources',
                'description' => null,
                'slug' => 'human-resources',
                'status' => true,
            ],
            [
                'name' => 'Lifestyle',
                'description' => null,
                'slug' => 'lifestyle',
                'status' => true,
            ],
            [
                'name' => 'Updates',
                'description' => null,
                'slug' => 'updates',
                'status' => true,
            ],
            [
                'name' => 'Marketing',
                'description' => null,
                'slug' => 'marketing',
                'status' => true,
            ],
            [
                'name' => 'Limited Promotion and instore',
                'description' => null,
                'slug' => 'limited-promotion-and-instore',
                'status' => true,
            ],
        ];

        foreach ($data as $item) {
            CategoryBlog::create($item);
        }
    }
}
