<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'parent_id',
        'product_name',
        'variant',
        'options',
        'qty',
        'unit_price',
        'subtotal',
        'note',
    ];

    protected $casts = [
        'options' => 'array',
        'qty' => 'integer',
        'unit_price' => 'integer',
        'subtotal' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(OrderItem::class, 'parent_id');
    }

    public function addOns()
    {
        return $this->hasMany(OrderItem::class, 'parent_id');
    }
}
