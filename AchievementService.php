<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    public function sync(User $user): void
    {
        $owned = $user->achievements()->pluck('achievements.id')->all();

        Achievement::whereNotIn('id', $owned)->get()->each(function (Achievement $achievement) use ($user) {
            if ($this->qualifies($user, $achievement)) {
                $this->award($user, $achievement);
            }
        });
    }

    private function qualifies(User $user, Achievement $achievement): bool
    {
        return match ($achievement->rule) {
            'lessons_completed' => $user->lessonProgress()->whereNotNull('completed_at')->count() >= $achievement->rule_value,
            'distance_total' => (int) $user->records()->sum('distance_m') >= $achievement->rule_value,
            'sessions_total' => $user->records()->count() >= $achievement->rule_value,
            'quiz_category' => $user->quizAttempts()->whereNotNull('completed_at')
                ->whereHas('category', fn ($query) => $query->where('slug', $achievement->rule_meta))->exists(),
            'advanced_program' => $user->enrollments()->whereNotNull('completed_at')
                ->whereHas('program', fn ($query) => $query->where('level', 'advanced'))->exists(),
            default => false,
        };
    }

    private function award(User $user, Achievement $achievement): void
    {
        DB::transaction(function () use ($user, $achievement) {
            $user->achievements()->syncWithoutDetaching([$achievement->id => ['awarded_at' => now()]]);
            $user->increment('points', $achievement->points);
            $user->pushAlert('Badge earned: '.$achievement->name, $achievement->description, route('achievements.index'));
        });
    }
}
