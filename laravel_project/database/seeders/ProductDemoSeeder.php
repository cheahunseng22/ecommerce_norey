<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentCategory;
use App\Models\Category;
use App\Models\Product;

class ProductDemoSeeder extends Seeder
{
    public function run()
    {
        // Create parent category
        $parent = ParentCategory::firstOrCreate(
            ['slug' => 'women'],
            [
                'name' => 'Women',
                'description' => "Women's products",
            ]
        );

        // Subcategories
        $subcategories = [
            ['name' => 'Dresses', 'slug' => 'dresses'],
            ['name' => 'Tops', 'slug' => 'tops'],
            ['name' => 'Shoes', 'slug' => 'shoes'],
        ];

        foreach ($subcategories as $subcatData) {
            $subcat = Category::firstOrCreate(
                ['slug' => $subcatData['slug']],
                [
                    'name' => $subcatData['name'],
                    'parent_id' => $parent->id,
                ]
            );

            // Create sample products
            for ($i = 1; $i <= 3; $i++) {
                Product::firstOrCreate(
                    ['slug' => strtolower($subcatData['slug'] . "-product-$i")],
                    [
                        'category_id' => $subcat->id,
                        'name' => $subcatData['name'] . " Product $i",
                        'price' => rand(10, 100),
                        'image' => '/images/products/sample.jpg',
                    ]
                );
            }
        }
    }
}
