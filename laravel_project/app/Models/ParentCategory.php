<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    // A parent category has many subcategories
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'parent_category_id');
    }

    // Get all products through subcategories
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,       // Final model
            SubCategory::class,   // Intermediate model
            'parent_category_id', // Foreign key on SubCategory table
            'subcategory_id',     // Foreign key on Product table
            'id',                 // Local key on ParentCategory table
            'id'                  // Local key on SubCategory table
        );
    }
}
