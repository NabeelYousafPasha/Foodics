<?php

namespace App\Models;

use App\Traits\HasCodeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory, HasCodeTrait;

    protected $fillable = [
        'name',
        'code',
        'base_quantity',
        'standard_unit_id',
        'available_quantity',
        'threshold_level',
        'threshold_unit',
        'last_stock_renewed_at',
        'last_threshold_notified_at',
    ];

    protected $casts = [
        'last_stock_renewed_at' => 'datetime',
        'last_threshold_notified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     *
     * @return bool
     */
    public function hasIngredientThresholdLevelAchieved(): bool
    {
        return ($this->available_quantity <= $this->calculateThresholdValue());
    }

    /**
     *
     * @return float|int
     */
    public function calculateThresholdValue()
    {
        return $this->base_quantity * ($this->threshold_level / 100);
    }

    /**
     *
     * @return bool
     */
    public function hasThresholdAlreadyNotified()
    {
        return ! is_null($this->last_threshold_notified_at);
    }


    /**
     * |--------------------------------------------------------------------------
     * | RELATIONSHIPS
     * |--------------------------------------------------------------------------
     */

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_ingredients', 'ingredient_id', 'product_id')
            ->withPivot('ingredient_quantity', 'standard_unit_id');
    }


    /**
     * |--------------------------------------------------------------------------
     * | SCOPES
     * |--------------------------------------------------------------------------
     */

    /**
     * @param Builder $query
     * @param null $productId
     * @param null $ingredientId
     *
     * @return Builder
     */
    public function scopePorductIngredients(Builder $query, $productId = null, $ingredientId = null): Builder
    {
        $query = $query->join('product_ingredients', function ($join) use ($productId, $ingredientId) {
            $join->on('ingredients.id', '=', 'product_ingredients.ingredient_id');

            if (! is_null($productId)) {
                $join->where('product_ingredients.product_id', '=', $productId);
            }

            if (! is_null($ingredientId)) {
                $join->where('product_ingredients.ingredient_id', '=', $ingredientId);
            }
        });

        $query = $query->join('products', function ($join) use ($productId, $ingredientId) {
            $join->on('product_ingredients.product_id', '=', 'products.id');

            if (! is_null($productId)) {
                $join->where('products.id', '=', $productId);
            }
        });

        if (! is_null($productId)) {
            $query->where('product_ingredients.product_id', '=', $productId);
        }

        if (! is_null($ingredientId)) {
            $query->where('product_ingredients.ingredient_id', '=', $ingredientId);
        }

        return $query;
    }
}
