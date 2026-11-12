<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwimmingChallenge extends Model
{
    public const TYPES = [
        'distance' => 'Total distance (m)',
        'style_distance' => 'Distance in one style (m)',
        'sessions' => 'Number of sessions',
        'streak' => 'Consecutive days',
        'single_distance' => 'Single-session distance (m)',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return ['starts_on' => 'date', 'ends_on' => 'date', 'is_active' => 'boolean'];
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class, 'challenge_id');
    }

    public function hasEnded(): bool
    {
        return $this->ends_on->copy()->endOfDay()->isPast();
    }

    public function isJoinable(): bool
    {
        return $this->is_active && ! $this->hasEnded();
    }

    public function unit(): string
    {
        return match ($this->type) {
            'sessions' => 'sessions',
            'streak' => 'days',
            default => 'm',
        };
    }
}
