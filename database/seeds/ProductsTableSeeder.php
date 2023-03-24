<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class,15)->create();
        product::create([
            'company_id' => 1,
            'product_name' => 'コーラ',
            'price' => 150,
            'stock' => 100, 
            'comment' => '美味しい',
            'img_path' => '',
        ]);
    }
}
