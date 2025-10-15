<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity_in_stock',
        'last_restock_date',
        'reorder_point',
        'reorder_quantity',
    ];

    /**
     * Inventory belongs to a Product.
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
}
