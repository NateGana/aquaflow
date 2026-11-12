<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\OrdersByLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    use HasImage, HasSlug, OrdersByLevel;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['steps' => 'array', 'mistakes' => 'array', 'tips' => 'array'];
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%")))
            ->when($filters['level'] ?? null, fn ($q, $level) => $q->where('level', $level));
    }

    public function relatedLessons()
    {
        $keyword = $this->keyword ?: $this->title;

        return SwimmingLesson::published()
            ->where(fn ($q) => $q->where('title', 'like', "%{$keyword}%")->orWhere('summary', 'like', "%{$keyword}%"))
            ->ordered()
            ->limit(4)
            ->get();
    }
}
