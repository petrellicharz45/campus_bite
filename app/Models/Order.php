<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'fulfillment_type',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_provider_reference',
        'payment_channel',
        'phone',
        'location',
        'notes',
        'subtotal',
        'delivery_fee',
        'total',
        'placed_at',
        'paid_at',
        'payment_meta',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'placed_at' => 'datetime',
            'paid_at' => 'datetime',
            'payment_meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentActivities(): HasMany
    {
        return $this->hasMany(PaymentActivity::class)->latest('happened_at');
    }
}
