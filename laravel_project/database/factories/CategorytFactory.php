<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ParentCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'image' => null,
            'description' => $this->faker->sentence(),
            'parent_category_id' => ParentCategories::inRandomOrder()->first()?->id,
        ];
    }
}