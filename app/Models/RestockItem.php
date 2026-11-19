<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestockItem extends Model
{
    protected $fillable = [
        'restock_id',
        'stock_item_id',
        'purchase_unit',
        'purchase_qty',
        'conversion_factor',
        'qty_in_base_unit',
        'unit_cost',
        'subtotal_cost',
    ];

    protected $casts = [
        'purchase_qty' => 'float',
        'conversion_factor' => 'float',
        'qty_in_base_unit' => 'float',
        'unit_cost' => 'integer',
        'subtotal_cost' => 'integer',
    ];

    public function restock()
    {
        return $this->belongsTo(Restock::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
