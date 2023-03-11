<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'mailable',
        'is_dispatched_successfully',
    ];

    protected $casts = [
        'created_at',
        'updated_at',
    ];
}
