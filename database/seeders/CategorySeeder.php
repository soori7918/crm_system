<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'id' => 1,
            'name' => 'لوازم ساختمانی',
            'parent_id' => null,
            'order' => 0,
        ]);
        DB::table('products')->insert([
            'id' => 1,
            'name' => 'لوازم ساختمانی',
            'sale_price' => 10000,
            'rent_price' => 20000,
            'amount' => 20,
            'created_by' => 1,
        ]);
        DB::table('category_product')->insert([
            'product_id' => 1,
            'category_id' => 1,
        ]);
    }
}
