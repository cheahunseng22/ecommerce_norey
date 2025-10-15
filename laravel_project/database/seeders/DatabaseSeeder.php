<?php

namespace Database\Seeders;
// use Database\Factories\ParentCategoryFactory;

// use App\Models\Brand;

// use App\Models\ParentCategory;
// use App\Models\Product;
// use App\Models\ParentCategories;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      UserSeeder::class,
     ParentCategoriesSeeder::class,
            CategorySeeder::class,
      BrandSeeder::class,
  
      ProductSeeder::class,
    ]);
  }
}
