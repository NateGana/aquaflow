<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramEnrollment extends Model
{
    protected $fillable = ['user_id', 'program_id', 'completed_exercises', 'completed_at'];

    protected function casts(): array
    {
        return ['completed_exercises' => 'array', 'completed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class, 'program_id');
    }

    public function completedIds(): array
    {
        return array_map('intval', $this->completed_exercises ?? []);
    }
}
