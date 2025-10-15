<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_order';
    protected $fillable = [
        'customer_id','order_code','status','total_amount','shipping_fee',
        'payment_method','shipping_address','ordered_at','delivery_at'
    ];

    protected $dates = ['ordered_at','delivery_at'];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
