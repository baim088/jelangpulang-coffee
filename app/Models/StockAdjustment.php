<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'stock_item_id',
        'system_qty',
        'physical_qty',
        'diff_qty',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'system_qty' => 'float',
        'physical_qty' => 'float',
        'diff_qty' => 'float',
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
