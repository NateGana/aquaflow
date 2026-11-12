<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EquipmentGuide extends Model
{
    use HasImage, HasSlug;

    protected string $slugFrom = 'name';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['usage_tips' => 'array'];
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%")->orWhere('purpose', 'like', "%{$term}%")));
    }
}
