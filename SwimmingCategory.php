<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwimmingCategory extends Model
{
    use HasSlug;

    protected string $slugFrom = 'name';

    protected $guarded = [];

    public function lessons(): HasMany
    {
        return $this->hasMany(SwimmingLesson::class, 'category_id');
    }
}
