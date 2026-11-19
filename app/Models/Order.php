<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'user_id',
        'customer_name',
        'table_no',
        'channel', // self_order, walk_in
        'status', // menunggu_verifikasi, diproses, selesai, dibatalkan
        'payment_method', // qris, cash, pay_at_counter
        'subtotal',
        'total',
        'cash_received',
        'change_amount',
        'payment_proof',
        'cancel_reason',
        'paid_at',
        'processed_by',
        'expired_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'total' => 'integer',
        'cash_received' => 'integer',
        'change_amount' => 'integer',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    public function isDiproses(): bool
    {
        return $this->status === 'diproses';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function isDibatalkan(): bool
    {
        return $this->status === 'dibatalkan';
    }
}
