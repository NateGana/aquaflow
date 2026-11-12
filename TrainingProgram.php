<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\OrdersByLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingProgram extends Model
{
    use HasImage, HasSlug, OrdersByLevel;

    protected $guarded = [];

    protected function casts(): array
    {
        return ['objectives' => 'array', 'equipment' => 'array'];
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(ProgramExercise::class, 'program_id')->orderBy('week')->orderBy('day')->orderBy('id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ProgramEnrollment::class, 'program_id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%")))
            ->when($filters['level'] ?? null, fn ($q, $level) => $q->where('level', $level))
            ->when($filters['duration'] ?? null, fn ($q, $range) => match ($range) {
                'short' => $q->where('duration_weeks', '<=', 4),
                'medium' => $q->whereBetween('duration_weeks', [5, 8]),
                'long' => $q->where('duration_weeks', '>', 8),
                default => $q,
            });
    }
}
