<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(QuizCategory::class, 'quiz_category_id');
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuizChoice::class)->orderBy('id');
    }

    public function correctChoice(): ?QuizChoice
    {
        return $this->choices->firstWhere('is_correct', true);
    }
}
