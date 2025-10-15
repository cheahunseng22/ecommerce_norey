<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                // 'description' => 'Sportswear and shoes brand.',
                'image' => 'nike.png',
            ],
            [
                'name' => 'Adidas',
                // 'description' => 'Performance sportswear and accessories.',
                'image' => 'adidas.png',
            ],
            [
                'name' => 'h&M',
                // 'description' => 'Athletic and casual footwear, apparel and accessories.',
                'image' => 'h&M.png',
            ],
            [
                'name' => 'Zara',
                // 'description' => 'Fashion clothing and accessories.',
                'image' => 'zara.png',
            ],
            [
                'name' => 'Chanel',
                // 'description' => 'Affordable fashion for men, women, and kids.',
                'image' => 'chanel.png',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['name' => $brand['name']], // check unique column
                $brand // insert if not exists
            );
        }
    }
}
