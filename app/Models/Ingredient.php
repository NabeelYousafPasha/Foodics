<?php

namespace App\Models;

use App\Traits\HasCodeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory, HasCodeTrait;

    protected $fillable = [
        'name',
        'code',
        'base_quantity',
        'standard_unit_id',
        'available_quantity',
        'danger_value',
        'danger_value_unit',
        'last_stock_renewed_at',
        'last_danger_notified_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
