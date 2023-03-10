<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCodeTrait
{
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
