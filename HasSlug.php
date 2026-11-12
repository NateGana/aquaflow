<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->slugSource();

            if ($model->slug && ! $model->isDirty($source)) {
                return;
            }

            $base = Str::slug((string) $model->{$source}) ?: 'item';
            $slug = $base;
            $suffix = 2;

            while (static::where('slug', $slug)
                ->when($model->exists, fn ($query) => $query->whereKeyNot($model->getKey()))
                ->exists()) {
                $slug = $base.'-'.$suffix++;
            }

            $model->slug = $slug;
        });
    }

    protected function slugSource(): string
    {
        return property_exists($this, 'slugFrom') ? $this->slugFrom : 'title';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
