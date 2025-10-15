<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\ParentCategories;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Men' => ['Shirts', 'T-Shirts', 'Pants', 'Jackets'],
            'Women' => ['Dresses', 'Tops', 'Skirts', 'Handbags'],
            'Kids' => ['Toys', 'Clothes', 'School Bags'],
            'Accessories' => ['Watches', 'Belts', 'Hats'],
            'Footwear' => ['Sneakers', 'Sandals', 'Boots'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = ParentCategories::where('name', $parentName)->first();

            if ($parent) {
                foreach ($children as $childName) {
                    Category::create([
                        'name' => $childName, // Use the actual child name from array
                        'parent_id' => $parent->id, // Use parent_category_id and actual parent ID
                    ]);
                }
            }
        }
    }
}