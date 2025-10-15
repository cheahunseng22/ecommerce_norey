<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 50),
            'rating' => $this->faker->randomFloat(1, 0, 5),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'quantity_in_stock' => $this->faker->numberBetween(0, 100),
            'image' => $this->faker->imageUrl(400, 400, 'fashion', true),
            'images' => json_encode([
                $this->faker->imageUrl(400, 400, 'fashion'),
                $this->faker->imageUrl(400, 400, 'clothes')
            ]),
            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }
}
