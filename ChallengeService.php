<?php

namespace App\Services;

use App\Models\ChallengeParticipant;
use App\Models\SwimmingRecord;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function progress(ChallengeParticipant $participant): int
    {
        $challenge = $participant->challenge;
        $from = $participant->joined_on->greaterThan($challenge->starts_on) ? $participant->joined_on : $challenge->starts_on;

        $records = SwimmingRecord::where('user_id', $participant->user_id)
            ->whereBetween('swam_on', [$from->toDateString(), $challenge->ends_on->toDateString()]);

        return match ($challenge->type) {
            'distance' => (int) $records->sum('distance_m'),
            'style_distance' => (int) $records->where('style', $challenge->style)->sum('distance_m'),
            'sessions' => $records->count(),
            'single_distance' => (int) $records->max('distance_m'),
            'streak' => $this->longestStreak($records->pluck('swam_on')->map(fn ($date) => Carbon::parse($date)->toDateString())->unique()->sort()->values()->all()),
            default => 0,
        };
    }

    public function percent(ChallengeParticipant $participant): int
    {
        $target = max(1, $participant->challenge->target);

        return min(100, (int) floor($this->progress($participant) / $target * 100));
    }

    public function evaluate(User $user): void
    {
        $user->participations()->whereNull('completed_at')->with('challenge')->get()->each(function (ChallengeParticipant $participant) use ($user) {
            if ($this->progress($participant) < $participant->challenge->target) {
                return;
            }

            DB::transaction(function () use ($participant, $user) {
                $points = $participant->challenge->points;
                $participant->update(['completed_at' => now(), 'points_awarded' => $points]);
                $user->increment('points', $points);
                $user->pushAlert('Challenge completed: '.$participant->challenge->title, "You earned {$points} points.", route('challenges.index'));
            });
        });
    }

    private function longestStreak(array $days): int
    {
        $best = 0;
        $run = 0;
        $previous = null;

        foreach ($days as $day) {
            $current = Carbon::parse($day);
            $run = $previous && $previous->copy()->addDay()->isSameDay($current) ? $run + 1 : 1;
            $best = max($best, $run);
            $previous = $current;
        }

        return $best;
    }
}
