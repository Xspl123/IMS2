<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductBrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            'GSM',
            'Tower',
            'Blade',
            'Rack',
            'Desktop',
            'Laptop',
            'OTHER'
        ];

        foreach ($brands as $brand) {
            DB::table('product_brands')->insert([
                'brand_name' => $brand,
            ]);
        }
    }
}
