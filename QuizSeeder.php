<?php

namespace Database\Seeders;

use App\Models\QuizCategory;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = [
            ['Swimming Basics', 'Floating, breathing and first skills.', [
                ['Which body position helps a swimmer glide with the least resistance?', ['Head up with hips low', 'Flat and streamlined with the head in line with the spine', 'Vertical with legs bent', 'Curled in a ball'], 1, 'A flat, streamlined position reduces drag so you move further with each stroke.'],
                ['What should you do with your breath while your face is in the water?', ['Hold it as long as possible', 'Breathe in through the nose', 'Breathe out gently and steadily', 'Stop breathing for a moment'], 2, 'Exhaling underwater lets you take a quick breath in when you turn your head.'],
                ['Where should the power of a flutter kick mainly come from?', ['The knees', 'The hips', 'The toes', 'The shoulders'], 1, 'A kick that starts at the hips is smoother and more efficient than one from the knees.'],
                ['Why is relaxing important when you learn to float?', ['Tense muscles sink the legs', 'It makes you heavier', 'It uses more energy', 'It has no effect'], 0, 'Tension makes the legs drop. A relaxed body and full lungs help you float.'],
                ['What helps keep a swimmer buoyant when floating?', ['Empty lungs', 'Lungs filled with air', 'Curling up', 'Kicking hard'], 1, 'Air in your lungs adds buoyancy, so a calm deep breath helps you float.'],
                ['Where is the best place for a beginner to start practicing?', ['The deep end', 'Open water', 'The shallow end where they can stand', 'Near a diving board'], 2, 'Shallow water lets you stand any time, which builds confidence and keeps you safe.'],
                ['What does a streamline position look like?', ['Arms by the sides', 'Arms extended overhead, body tight and long', 'Knees pulled to the chest', 'Head lifted forward'], 1, 'Streamlining means a long, tight body with arms stretched overhead and head between them.'],
            ]],
            ['Swimming Techniques', 'Strokes, timing and body position.', [
                ['In freestyle, which part of the hand should enter the water first?', ['The palm', 'The fingertips', 'The thumb', 'The wrist'], 1, 'Entering fingertips first, in line with the shoulder, reduces splash and drag.'],
                ['Which stroke uses a frog kick with simultaneous arm movements?', ['Freestyle', 'Backstroke', 'Breaststroke', 'Butterfly'], 2, 'Breaststroke combines a symmetrical arm pull with a frog kick.'],
                ['In backstroke, where should your eyes look?', ['At the pool floor', 'Straight ahead', 'At your feet', 'Up toward the ceiling'], 3, 'Looking up keeps the head still and the body flat.'],
                ['What is the leg movement in butterfly called?', ['Frog kick', 'Scissor kick', 'Dolphin kick', 'Eggbeater kick'], 2, 'Butterfly uses a dolphin kick with the legs together.'],
                ['What is bilateral breathing in freestyle?', ['Breathing on both sides, such as every three strokes', 'Breathing only on the left', 'Breathing twice per stroke', 'Holding the breath'], 0, 'Breathing to both sides balances the stroke and helps keep a straight line.'],
                ['Which leg movement is commonly used to tread water?', ['Dolphin kick', 'Eggbeater kick', 'Flutter kick only', 'Pointed toes'], 1, 'The eggbeater kick keeps you upright with steady support while freeing your hands.'],
                ['What common mistake causes the hips to sink in freestyle?', ['Looking down', 'Lifting the head forward to look ahead', 'Kicking from the hips', 'Rotating the body'], 1, 'Lifting the head drops the hips and increases drag.'],
            ]],
            ['Water Safety', 'Safe habits for pools and open water.', [
                ['Which is a core water safety rule?', ['Swim alone for practice', 'Never swim alone', 'Ignore warning flags', 'Dive anywhere'], 1, 'Swimming with others or under supervision gives you help if something goes wrong.'],
                ['If you are caught in a rip current, what is a recommended response?', ['Swim directly against it', 'Swim parallel to the shore, and call or signal for help', 'Panic and thrash', 'Stop moving and sink'], 1, 'Rip currents are narrow, so swimming sideways out of them and signaling for help is advised.'],
                ['Which number should you call in an emergency?', ['Any friend', 'The local emergency number for your area', 'A random website', 'Nobody'], 1, 'Emergency numbers vary by country, so learn the one for where you swim.'],
                ['Where is the safest place to swim at a beach?', ['Anywhere', 'Near rocks', 'Between the lifeguard flags', 'Far from shore'], 2, 'Flagged areas are monitored by lifeguards and checked for hazards.'],
                ['If someone is struggling and you are untrained, what should you do?', ['Jump in immediately', 'Call for help and alert a lifeguard', 'Ignore them', 'Swim away'], 1, 'Untrained rescue attempts can put two people in danger. Get trained help.'],
                ['How should you enter water that is unfamiliar?', ['Head first diving', 'Feet first and carefully', 'Running jump', 'Backflip'], 1, 'Entering feet first lets you check depth and avoids head and neck injuries.'],
                ['Why should you avoid swimming during a thunderstorm?', ['The water gets cold', 'Lightning is a serious risk', 'Waves are small', 'It is boring'], 1, 'Lightning can strike water and nearby areas, so leave the water.'],
            ]],
            ['Swimming Equipment', 'Gear and how it helps you train.', [
                ['What is the main purpose of swimming goggles?', ['Make you faster', 'Protect your eyes and help you see underwater', 'Keep your ears dry', 'Help you float'], 1, 'Goggles protect the eyes from chlorine and make vision clearer.'],
                ['What does a pull buoy mainly isolate?', ['The legs', 'The arms and upper body', 'The neck', 'The ankles'], 1, 'A buoy between the thighs lifts the hips so you can focus on the arms.'],
                ['What is a kickboard mainly used for?', ['Practicing kicks', 'Practicing diving', 'Protecting hair', 'Improving vision'], 0, 'Kickboards let you rest the arms and focus on leg technique.'],
                ['What is one benefit of swim fins?', ['They shrink drag', 'They build leg strength and ankle flexibility', 'They fix goggles', 'They clean the water'], 1, 'Fins increase kick power and give a feel for gliding.'],
                ['Why might someone use a nose clip?', ['To keep water out of the nose', 'To hold goggles', 'To pull the arms', 'To protect the ears'], 0, 'Nose clips help when practicing backstroke or flip turns.'],
                ['What is one benefit of wearing a swim cap?', ['It reduces drag and keeps hair out of the face', 'It makes you float', 'It cleans the pool', 'It protects the eyes'], 0, 'Caps reduce drag from hair and keep hair out of the way.'],
                ['What should swimmers be careful about when using paddles?', ['They are too cold', 'Overuse can strain the shoulders', 'They sink', 'They need batteries'], 1, 'Paddles add resistance, so introduce them gradually and use them in short sets.'],
            ]],
            ['Advanced Swimming Knowledge', 'Turns, pacing and performance.', [
                ['What is the first action in a flip turn?', ['Stop swimming', 'Tuck the chin and begin a somersault', 'Lift the head', 'Grab the wall'], 1, 'A tucked chin starts the rotation of the somersault.'],
                ['What is a negative split?', ['A slower second half', 'A faster second half of a swim', 'Two equal halves', 'A missed turn'], 1, 'In a negative split, you swim the second half faster than the first.'],
                ['What does SWOLF combine?', ['Strokes and time for a length', 'Speed and weight', 'Strength and lift', 'Style and form'], 0, 'SWOLF adds the number of strokes to the seconds taken for a length.'],
                ['What is the catch phase?', ['The finish of the stroke', 'The early part of the pull where the hand grips the water', 'The kick', 'The breath'], 1, 'The catch is where the hand and forearm start to hold the water.'],
                ['Why does a high-elbow catch help?', ['It reduces breathing', 'It lets the forearm push more water backward', 'It makes arms longer', 'It sinks the hips'], 1, 'A high elbow puts the forearm vertical so more surface pushes water back.'],
                ['What kind of training uses short fast efforts with rest?', ['Interval training', 'Floating', 'Recovery swimming', 'Treading'], 0, 'Intervals mix hard efforts and rest to build speed.'],
                ['What is a common competition limit for underwater dolphin kicks after a start or turn?', ['15 meters', '5 meters', '40 meters', 'No limit'], 0, 'Many competition rules limit the underwater phase to 15 meters before the head must surface.'],
            ]],
        ];

        foreach ($quizzes as [$name, $description, $questions]) {
            $category = QuizCategory::updateOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'description' => $description]);

            foreach ($questions as [$text, $choices, $correct, $explanation]) {
                $question = QuizQuestion::updateOrCreate(
                    ['quiz_category_id' => $category->id, 'question' => $text],
                    ['explanation' => $explanation]
                );

                $question->choices()->delete();

                foreach ($choices as $index => $label) {
                    $question->choices()->create(['label' => $label, 'is_correct' => $index === $correct]);
                }
            }
        }
    }
}
