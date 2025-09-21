<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['name' => 'Aspirin 100mg', 'description' => 'Pain relief tablets', 'price' => 5.99, 'stock_quantity' => 100, 'category_id' => 1],
            ['name' => 'Vitamin C 1000mg', 'description' => 'Immune system support', 'price' => 12.50, 'stock_quantity' => 75, 'category_id' => 2],
            ['name' => 'Bandages Pack', 'description' => 'Sterile adhesive bandages', 'price' => 8.25, 'stock_quantity' => 50, 'category_id' => 3],
            ['name' => 'Cough Syrup', 'description' => 'Effective cough relief', 'price' => 15.75, 'stock_quantity' => 30, 'category_id' => 4],
            ['name' => 'Blood Glucose Test Strips', 'description' => 'Diabetes monitoring strips', 'price' => 25.00, 'stock_quantity' => 40, 'category_id' => 5],
            ['name' => 'Ibuprofen 200mg', 'description' => 'Anti-inflammatory tablets', 'price' => 7.99, 'stock_quantity' => 80, 'category_id' => 1],
            ['name' => 'Multivitamin', 'description' => 'Daily vitamin supplement', 'price' => 18.99, 'stock_quantity' => 60, 'category_id' => 2],
            ['name' => 'Thermometer', 'description' => 'Digital fever thermometer', 'price' => 22.50, 'stock_quantity' => 25, 'category_id' => 3],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
