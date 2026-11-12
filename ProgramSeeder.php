<?php

namespace Database\Seeders;

use App\Models\TrainingProgram;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Beginner Program',
                'level' => 'beginner',
                'duration_weeks' => 4,
                'sessions_per_week' => 3,
                'summary' => 'Build water confidence, learn to float, breathe and kick, and swim your first short distances.',
                'objectives' => ['Build water confidence', 'Practice floating', 'Learn breathing exercises', 'Master basic kicking', 'Swim short distances'],
                'equipment' => ['Swimming goggles', 'Swim cap', 'Kickboard'],
                'exercises' => [
                    [1, 1, 'Water confidence games', 'Walk, splash and blow bubbles in the shallow end for 15 minutes.', 0],
                    [1, 3, 'Front float practice', 'Practice five front floats of five seconds each with support if needed.', 0],
                    [1, 5, 'Bubbles and breathing', 'Ten rhythmic bubble-and-breathe cycles holding the wall.', 0],
                    [2, 1, 'Back float practice', 'Five back floats with a coach or a partner nearby.', 0],
                    [2, 3, 'Kickboard kicking', 'Six lengths of the shallow end with a kickboard.', 100],
                    [2, 5, 'Breathing and gliding', 'Push off the wall, glide, and stand up. Repeat ten times.', 50],
                    [3, 1, 'Arm pull drills', 'Practice arm pulls in shallow water, ten each side.', 0],
                    [3, 3, 'Kick and glide', 'Kick with a kickboard for 25 meters, resting after each length.', 100],
                    [3, 5, 'Float to kick', 'Front float, then kick for 10 meters, repeat six times.', 60],
                    [4, 1, 'Short freestyle attempt', 'Swim 10 meters with kick, arms and breathing; rest and repeat.', 100],
                    [4, 3, 'Continuous swim', 'Swim 25 meters without stopping, using any comfortable stroke.', 100],
                    [4, 5, 'Confidence review', 'Repeat your favorite drills and swim 50 meters in total.', 50],
                ],
            ],
            [
                'title' => 'Intermediate Program',
                'level' => 'intermediate',
                'duration_weeks' => 6,
                'sessions_per_week' => 2,
                'summary' => 'Improve freestyle, coordinate strokes, develop endurance and reach your distance goals.',
                'objectives' => ['Freestyle improvement', 'Stroke coordination', 'Endurance development', 'Swimming distance goals'],
                'equipment' => ['Goggles', 'Swim cap', 'Pull buoy', 'Kickboard'],
                'exercises' => [
                    [1, 2, 'Freestyle technique set', '8 x 25 meters freestyle focusing on long strokes, 20 seconds rest.', 200],
                    [1, 5, 'Kick set', '6 x 50 meters kick with a kickboard.', 300],
                    [2, 2, 'Catch-up drill', '8 x 25 meters catch-up freestyle.', 200],
                    [2, 5, 'Breathing patterns', '6 x 50 meters breathing every three strokes.', 300],
                    [3, 2, 'Pull buoy set', '6 x 50 meters pulling with a buoy and high elbows.', 300],
                    [3, 5, 'Endurance 400', 'Swim 400 meters continuous at a comfortable pace.', 400],
                    [4, 2, 'Pace work', '6 x 50 meters at a steady strong pace with 20 seconds rest.', 300],
                    [4, 5, 'Mixed strokes', '4 x 100 meters, one each of freestyle, backstroke, breaststroke, choice.', 400],
                    [5, 2, 'Stroke count set', '8 x 50 meters counting strokes and trying to lower the count.', 400],
                    [5, 5, 'Endurance 600', 'Swim 600 meters continuous with even pacing.', 600],
                    [6, 2, 'Test set', '4 x 100 meters at a hard but controlled pace.', 400],
                    [6, 5, 'Distance goal', 'Swim 800 meters continuous, any stroke.', 800],
                ],
            ],
            [
                'title' => 'Advanced Program',
                'level' => 'advanced',
                'duration_weeks' => 4,
                'sessions_per_week' => 3,
                'summary' => 'Develop speed, sprint intervals, refined strokes and long endurance sets.',
                'objectives' => ['Speed development', 'Sprint intervals', 'Advanced stroke techniques', 'Endurance challenges'],
                'equipment' => ['Goggles', 'Swim cap', 'Fins', 'Paddles', 'Pull buoy'],
                'exercises' => [
                    [1, 1, 'Technique and drills', '600 meters of drills including fist drill and single-arm.', 600],
                    [1, 3, 'Sprint intervals', '8 x 25 meters fast with 45 seconds rest.', 200],
                    [1, 5, 'Endurance base', '3 x 400 meters at an even pace, 45 seconds rest.', 1200],
                    [2, 1, 'Flip turn practice', '10 x 50 meters with flip turns at each wall.', 500],
                    [2, 3, 'Speed set', '6 x 50 meters at race pace with 30 seconds rest.', 300],
                    [2, 5, 'Butterfly focus', '8 x 25 meters butterfly with full rest.', 200],
                    [3, 1, 'Breathing control', '8 x 50 meters bilateral breathing, then every five strokes.', 400],
                    [3, 3, 'Sprint and recover', '10 x 25 meters sprint with 60 seconds rest.', 250],
                    [3, 5, 'Endurance 1500', 'Swim 1,500 meters continuous at a steady pace.', 1500],
                    [4, 1, 'Stroke mechanics', '8 x 100 meters focusing on efficiency and stroke count.', 800],
                    [4, 3, 'Pyramid set', '50-100-150-100-50 meters with 20 seconds rest.', 450],
                    [4, 5, 'Final test', 'Swim 1,000 meters for time and compare against week 1.', 1000],
                ],
            ],
        ];

        foreach ($programs as $data) {
            $exercises = $data['exercises'];
            unset($data['exercises']);

            $program = TrainingProgram::updateOrCreate(['title' => $data['title']], $data);
            $program->exercises()->delete();

            foreach ($exercises as [$week, $day, $title, $description, $distance]) {
                $program->exercises()->create([
                    'week' => $week,
                    'day' => $day,
                    'title' => $title,
                    'description' => $description,
                    'distance_m' => $distance ?: null,
                ]);
            }
        }
    }
}
