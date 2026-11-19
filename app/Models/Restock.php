<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    protected $fillable = [
        'code',
        'restock_date',
        'supplier_note',
        'total_cost',
        'created_by',
    ];

    protected $casts = [
        'restock_date' => 'datetime',
        'total_cost' => 'integer',
    ];

    public function items()
    {
        return $this->hasMany(RestockItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
