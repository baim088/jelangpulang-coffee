<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'product_id',
        'stock_item_id',
        'variant', // hot, ice, all
        'qty_per_portion',
        'replaces_stock_item_id',
    ];

    protected $casts = [
        'qty_per_portion' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function replacedStockItem()
    {
        return $this->belongsTo(StockItem::class, 'replaces_stock_item_id');
    }
}
