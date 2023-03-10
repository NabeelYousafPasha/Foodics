<?php

namespace App\Models;

use App\Traits\HasCodeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatus extends Model
{
    use HasFactory, HasCodeTrait;

    const PENDING = 'pending';
    const IN_PROGRESS = 'in-progress';
    const DONE = 'done';
    const DISPATCHED = 'dispatched';
    const CANCELLED = 'cancelled';

    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return string[]
     */
    public static function getAllOrderStatusConstants(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::DONE,
            self::DISPATCHED,
            self::CANCELLED,
        ];
    }

    /**
     * |--------------------------------------------------------------------------
     * | RELATIONSHIPS
     * |--------------------------------------------------------------------------
     */

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
