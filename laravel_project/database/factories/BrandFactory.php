<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            // 'description' => $this->faker->sentence(10),
            'image' => $this->faker (640, 480, 'business', true, 'brand'),
            // 'image' => $this->faker->image('public/storage/brands', 640, 480, 'business', false),
            // 'image'
        ];
    }
}
