<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'stock_item_id',
        'name',
        'slug',
        'description',
        'photo',
        'price',
        'type', // racikan, jadi
        'has_hot',
        'has_ice',
        'ice_price_delta',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'ice_price_delta' => 'integer',
        'has_hot' => 'boolean',
        'has_ice' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function addOns()
    {
        return $this->belongsToMany(Product::class, 'product_add_ons', 'product_id', 'add_on_id');
    }

    public function isRacikan(): bool
    {
        return $this->type === 'racikan';
    }
}
