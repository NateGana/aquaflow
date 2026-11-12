<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeParticipant extends Model
{
    protected $fillable = ['user_id', 'challenge_id', 'joined_on', 'completed_at', 'points_awarded'];

    protected function casts(): array
    {
        return ['joined_on' => 'date', 'completed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(SwimmingChallenge::class, 'challenge_id');
    }
}
