<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSchedule extends Model
{
    protected $fillable = ['user_id', 'activity', 'scheduled_on', 'start_time', 'notes', 'completed_at'];

    protected function casts(): array
    {
        return ['scheduled_on' => 'date', 'completed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeLabel(): string
    {
        return substr((string) $this->start_time, 0, 5);
    }
}
