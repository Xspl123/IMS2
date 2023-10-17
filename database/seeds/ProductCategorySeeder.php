<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        // Add example category names
        $categories = [
            'Dell',
            'Lenovo',
            'HP',
            'Hcl',
            'Other'
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->insert([
                'cat_name' => $category,
            ]);
        }
    }
}
