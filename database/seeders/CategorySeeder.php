<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Pain Relief', 'description' => 'Pain management and relief medications'],
            ['name' => 'Vitamins & Supplements', 'description' => 'Essential vitamins and dietary supplements'],
            ['name' => 'First Aid', 'description' => 'First aid supplies and wound care'],
            ['name' => 'Cold & Flu', 'description' => 'Cold and flu treatment products'],
            ['name' => 'Diabetes Care', 'description' => 'Diabetes monitoring and care products'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
