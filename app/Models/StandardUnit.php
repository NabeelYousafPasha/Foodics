<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * |--------------------------------------------------------------------------
     * | SCOPES
     * |--------------------------------------------------------------------------
     */

    /**
     * @param Builder $query
     * @param string $code
     *
     * @return Builder
     */
    public function scopeOfCode(Builder $query, string $code): Builder
    {
        return $query->where('code', '=', $code);
    }
}
