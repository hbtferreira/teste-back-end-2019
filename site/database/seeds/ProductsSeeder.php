<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();
        for ($i = 1; $i <= 5; $i++) {
            Product::create([
                'name' => 'Produto'.' '.strval($i),
                'price' => $i,
                'weight' => $i
            ]);
        }
    }
}
