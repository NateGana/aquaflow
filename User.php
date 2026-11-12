<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const LEVELS = [
        'complete_beginner' => 'Complete Beginner',
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
    ];

    protected $fillable = ['name', 'email', 'password', 'role_id', 'swimming_level', 'avatar', 'points', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(SwimmingRecord::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ProgramEnrollment::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TrainingSchedule::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(ChallengeParticipant::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')->withPivot('awarded_at');
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function levelLabel(): string
    {
        return self::LEVELS[$this->swimming_level] ?? 'Beginner';
    }

    public function levelKey(): string
    {
        return $this->swimming_level === 'complete_beginner' ? 'beginner' : $this->swimming_level;
    }

    public function avatarUrl(): ?string
    {
        return $this->avatar ? Storage::disk('public')->url($this->avatar) : null;
    }

    public function initials(): string
    {
        return Str::of($this->name)->explode(' ')->filter()->take(2)->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    public function pushAlert(string $title, ?string $body = null, ?string $link = null): void
    {
        $this->alerts()->create(['title' => $title, 'body' => $body, 'link' => $link]);
    }
}
