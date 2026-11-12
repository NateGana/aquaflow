<?php

namespace App\Http\Controllers;

use App\Models\SwimmingLesson;
use App\Models\User;
use App\Support\Level;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $days = collect(range(6, 0))->map(fn ($offset) => now()->subDays($offset)->startOfDay());
        $weekRecords = $user->records()->whereBetween('swam_on', [$days->first()->toDateString(), now()->toDateString()])->get()
            ->groupBy(fn ($record) => $record->swam_on->toDateString());

        return view('dashboard', [
            'stats' => [
                'lessons' => $user->lessonProgress()->whereNotNull('completed_at')->count(),
                'sessions' => $user->records()->count(),
                'distance' => (int) $user->records()->sum('distance_m'),
                'level' => $user->levelLabel(),
                'points' => $user->points,
            ],
            'continue' => $this->continueLesson($user),
            'weekly' => [
                'labels' => $days->map(fn ($day) => $day->format('D'))->all(),
                'values' => $days->map(fn ($day) => (int) ($weekRecords[$day->toDateString()] ?? collect())->sum('distance_m'))->all(),
            ],
            'skills' => $this->skills($user),
            'upcoming' => $user->schedules()->whereNull('completed_at')->whereDate('scheduled_on', '>=', today())
                ->orderBy('scheduled_on')->orderBy('start_time')->limit(5)->get(),
            'achievements' => $user->achievements()->orderByDesc('user_achievements.awarded_at')->limit(4)->get(),
        ]);
    }

    private function continueLesson(User $user): ?SwimmingLesson
    {
        $started = $user->lessonProgress()->whereNull('completed_at')->with('lesson')->latest('updated_at')->first()?->lesson;

        if ($started) {
            return $started;
        }

        $remaining = SwimmingLesson::published()->with('category')
            ->whereDoesntHave('progress', fn ($q) => $q->where('user_id', $user->id)->whereNotNull('completed_at'));

        return (clone $remaining)->where('level', $user->levelKey())->orderBy('sort_order')->first()
            ?? $remaining->ordered()->first();
    }

    private function skills(User $user): array
    {
        $completed = $user->lessonProgress()->whereNotNull('completed_at')->pluck('lesson_id');
        $totals = SwimmingLesson::published()->get(['id', 'skill'])->groupBy('skill');

        return collect(Level::SKILLS)->map(function ($label, $skill) use ($totals, $completed) {
            $lessons = $totals[$skill] ?? collect();
            $done = $lessons->whereIn('id', $completed)->count();

            return ['label' => $label, 'done' => $done, 'total' => $lessons->count(), 'percent' => $lessons->count() ? (int) round($done / $lessons->count() * 100) : 0];
        })->values()->all();
    }
}
