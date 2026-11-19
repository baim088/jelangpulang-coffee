<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'stock_item_id',
        'direction', // IN, OUT
        'source_type', // restock, order, adjustment, void
        'source_id',
        'qty',
        'balance_after',
        'occurred_at',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'float',
        'balance_after' => 'float',
        'occurred_at' => 'datetime',
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
