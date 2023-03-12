<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_status_id',
        'dispatched_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * |--------------------------------------------------------------------------
     * | RELATIONSHIPS
     * |--------------------------------------------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function orderStatus(): HasOne
    {
        return $this->hasOne(OrderStatus::class);
    }
}
