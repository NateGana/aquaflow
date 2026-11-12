<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait OrdersByLevel
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByRaw("case level when 'beginner' then 1 when 'intermediate' then 2 else 3 end")
            ->orderBy('id');
    }
}
