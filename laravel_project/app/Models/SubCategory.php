<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['name', 'slug', 'parent_category_id'];

    // Subcategory belongs to a parent category
    public function parentCategory()
    {
        return $this->belongsTo(ParentCategory::class, 'parent_category_id');
    }

    // Subcategory has many products
    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }
}
