<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\SwimmingChallenge;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['First Splash', 'Complete your first lesson.', 'droplet', 'lessons_completed', 1, null, 50],
            ['Water Explorer', 'Complete five lessons.', 'compass', 'lessons_completed', 5, null, 100],
            ['Distance Starter', 'Swim 100 meters in total.', 'flag', 'distance_total', 100, null, 75],
            ['Endurance Master', 'Reach 1,000 meters of total swimming.', 'bolt', 'distance_total', 1000, null, 200],
            ['Safety Champion', 'Complete the Water Safety quiz.', 'shield', 'quiz_category', 1, 'water-safety', 100],
            ['Dedicated Swimmer', 'Log five swimming sessions.', 'fire', 'sessions_total', 5, null, 120],
            ['Aqua Expert', 'Complete an advanced training program.', 'award', 'advanced_program', 1, null, 300],
        ];

        foreach ($badges as [$name, $description, $icon, $rule, $value, $meta, $points]) {
            Achievement::updateOrCreate(['name' => $name], [
                'description' => $description,
                'icon' => $icon,
                'rule' => $rule,
                'rule_value' => $value,
                'rule_meta' => $meta,
                'points' => $points,
            ]);
        }

        $window = [now()->subDays(30)->toDateString(), now()->addDays(60)->toDateString()];

        $challenges = [
            ['First 100 Meters', 'Swim a total of 100 meters.', 'distance', null, 100, $window, 50],
            ['Swim 500 Meters This Week', 'Reach 500 meters of total distance within this week.', 'distance', null, 500, [now()->toDateString(), now()->addDays(7)->toDateString()], 100],
            ['Complete 5 Swimming Sessions', 'Log five swimming sessions in the Progress Tracker.', 'sessions', null, 5, $window, 120],
            ['Seven-Day Swimming Challenge', 'Swim on seven days in a row.', 'streak', null, 7, $window, 250],
            ['Freestyle Improvement Challenge', 'Swim 1,000 meters of freestyle.', 'style_distance', 'freestyle', 1000, $window, 150],
            ['Endurance Builder', 'Swim 800 meters or more in a single session.', 'single_distance', null, 800, $window, 200],
        ];

        foreach ($challenges as [$title, $description, $type, $style, $target, [$start, $end], $points]) {
            SwimmingChallenge::updateOrCreate(['title' => $title], [
                'description' => $description,
                'type' => $type,
                'style' => $style,
                'target' => $target,
                'starts_on' => $start,
                'ends_on' => $end,
                'points' => $points,
                'is_active' => true,
            ]);
        }
    }
}
