<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentCategories;

class ParentCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Men',
            'Women',
            'Kids',
            'Accessories',
            'Footwear'
        ];

        foreach ($categories as $name) {
            ParentCategories::firstOrCreate(['name' => $name]);
        }
    }
}