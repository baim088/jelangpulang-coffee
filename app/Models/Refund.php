<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'reason',
        'method', // tunai, transfer
        'status', // diajukan, disetujui, selesai, ditolak
        'stok_action', // void, waste, none
        'requested_by',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
