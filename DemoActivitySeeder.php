<?php

namespace Database\Seeders;

use App\Models\LessonProgress;
use App\Models\ProgramEnrollment;
use App\Models\SwimmingChallenge;
use App\Models\SwimmingLesson;
use App\Models\TrainingProgram;
use App\Models\User;
use App\Services\AchievementService;
use App\Services\ChallengeService;
use Illuminate\Database\Seeder;

class DemoActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'swimmer@aquaflow.test')->first();

        if (! $user || $user->records()->exists()) {
            return;
        }

        $sessions = [
            [18, 'freestyle', 100, 14, 4], [15, 'freestyle', 150, 20, 6], [13, 'breaststroke', 100, 18, 4],
            [11, 'backstroke', 125, 20, 5], [9, 'freestyle', 200, 26, 8], [6, 'freestyle', 250, 30, 10],
            [4, 'mixed', 200, 28, 8], [2, 'freestyle', 300, 34, 12], [1, 'drills', 150, 25, 6],
        ];

        foreach ($sessions as [$daysAgo, $style, $distance, $minutes, $laps]) {
            $user->records()->create([
                'swam_on' => now()->subDays($daysAgo)->toDateString(),
                'style' => $style,
                'distance_m' => $distance,
                'duration_min' => $minutes,
                'laps' => $laps,
                'notes' => null,
            ]);
        }

        SwimmingLesson::published()->orderBy('sort_order')->limit(3)->get()->each(fn ($lesson) => LessonProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'started_at' => now()->subDays(10),
            'completed_at' => now()->subDays(9),
        ]));

        $nextLesson = SwimmingLesson::published()->orderBy('sort_order')->skip(3)->first();

        if ($nextLesson) {
            LessonProgress::create(['user_id' => $user->id, 'lesson_id' => $nextLesson->id, 'started_at' => now()->subDay()]);
        }

        $program = TrainingProgram::where('level', 'beginner')->first();

        if ($program) {
            ProgramEnrollment::create([
                'user_id' => $user->id,
                'program_id' => $program->id,
                'completed_exercises' => $program->exercises()->limit(4)->pluck('id')->all(),
            ]);
        }

        $user->schedules()->createMany([
            ['activity' => 'Freestyle practice', 'scheduled_on' => now()->addDay()->toDateString(), 'start_time' => '07:30', 'notes' => 'Focus on breathing every three strokes.'],
            ['activity' => 'Endurance swim', 'scheduled_on' => now()->addDays(3)->toDateString(), 'start_time' => '18:00', 'notes' => null],
            ['activity' => 'Technique drills', 'scheduled_on' => now()->subDays(3)->toDateString(), 'start_time' => '17:30', 'notes' => 'Kickboard sets.', 'completed_at' => now()->subDays(3)],
        ]);

        foreach (SwimmingChallenge::whereIn('title', ['First 100 Meters', 'Complete 5 Swimming Sessions', 'Endurance Builder'])->get() as $challenge) {
            $user->participations()->create(['challenge_id' => $challenge->id, 'joined_on' => now()->subDays(20)->toDateString()]);
        }

        app(ChallengeService::class)->evaluate($user);
        app(AchievementService::class)->sync($user);
    }
}
