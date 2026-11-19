<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type', // bahan, kemasan, jadi
        'unit', // gram, ml, pcs
        'current_stock',
        'min_stock',
        'last_purchase_cost',
        'is_active',
    ];

    protected $casts = [
        'current_stock' => 'float',
        'min_stock' => 'float',
        'last_purchase_cost' => 'integer',
        'is_active' => 'boolean',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
