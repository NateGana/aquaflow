<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SafetyGuide extends Model
{
    use HasSlug;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['points' => 'array'];
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%")->orWhere('content', 'like', "%{$term}%")));
    }
}
