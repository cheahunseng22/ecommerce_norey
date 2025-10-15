<?php

// File: database/seeders/ParentCategoriesSeeder.php
namespace Database\Factories;

use App\Models\ParentCategories;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParentCategoriesFactory extends Factory
{
    protected $model = ParentCategories::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}