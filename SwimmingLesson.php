<?php

namespace App\Models;

use App\Models\Concerns\HasImage;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\OrdersByLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwimmingLesson extends Model
{
    use HasImage, HasSlug, OrdersByLevel;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'steps' => 'array',
            'mistakes' => 'array',
            'tips' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SwimmingCategory::class, 'category_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where(fn ($w) => $w->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%")))
            ->when($filters['level'] ?? null, fn ($q, $level) => $q->where('level', $level))
            ->when($filters['category'] ?? null, fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($filters['duration'] ?? null, fn ($q, $range) => match ($range) {
                'short' => $q->where('duration_minutes', '<=', 15),
                'medium' => $q->whereBetween('duration_minutes', [16, 30]),
                'long' => $q->where('duration_minutes', '>', 30),
                default => $q,
            });
    }

    public function related()
    {
        return static::published()
            ->whereKeyNot($this->id)
            ->orderByRaw('category_id = ? desc', [$this->category_id])
            ->orderByRaw('level = ? desc', [$this->level])
            ->orderBy('sort_order')
            ->limit(3)
            ->get();
    }

    public function embedUrl(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        $parts = parse_url($this->video_url);
        $host = strtolower(preg_replace('/^www\./', '', $parts['host'] ?? ''));
        $path = trim($parts['path'] ?? '', '/');
        parse_str($parts['query'] ?? '', $query);
        $clean = fn ($value) => preg_replace('/[^\w-]/', '', (string) $value);

        return match (true) {
            in_array($host, ['youtube.com', 'm.youtube.com']) && $path === 'watch' && ! empty($query['v']) => 'https://www.youtube.com/embed/'.$clean($query['v']),
            in_array($host, ['youtube.com', 'm.youtube.com']) && str_starts_with($path, 'embed/') => 'https://www.youtube.com/embed/'.$clean(substr($path, 6)),
            $host === 'youtu.be' && $path !== '' => 'https://www.youtube.com/embed/'.$clean($path),
            $host === 'vimeo.com' && ctype_digit($path) => 'https://player.vimeo.com/video/'.$path,
            $host === 'player.vimeo.com' && str_starts_with($path, 'video/') => 'https://player.vimeo.com/video/'.$clean(substr($path, 6)),
            default => null,
        };
    }
}
