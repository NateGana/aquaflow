<?php

namespace Database\Seeders;

use App\Models\EquipmentGuide;
use App\Models\SafetyGuide;
use App\Models\SwimmingCategory;
use App\Models\SwimmingLesson;
use App\Models\Technique;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->categories();
        $this->lessons();
        $this->techniques();
        $this->equipment();
        $this->safety();
    }

    private function categories(): void
    {
        foreach ([
            ['Beginner Swimming', 'First steps in the water: comfort, floating, breathing and basic movement.'],
            ['Intermediate Swimming', 'Build real strokes, coordination and breathing rhythm.'],
            ['Advanced Swimming', 'Refine mechanics, turns and race-style skills.'],
            ['Swimming Fitness', 'Speed and endurance work for stronger, longer swims.'],
            ['Water Safety', 'Knowledge and habits that keep you safe in and around water.'],
        ] as [$name, $description]) {
            SwimmingCategory::firstOrCreate(['name' => $name], ['description' => $description]);
        }
    }

    private function lessons(): void
    {
        $categories = SwimmingCategory::pluck('id', 'slug');

        $lessons = [
            ['beginner-swimming', 'beginner', 'confidence', 15, 'Introduction to Swimming',
                'What swimming involves, how to prepare, and what your first sessions look like.',
                'Swimming is a life skill and a gentle full-body workout. This lesson explains the basics of pool etiquette, what to bring, and how to approach your first sessions calmly.',
                ['Understand how a typical swimming session is structured', 'Know what to bring and how to prepare', 'Feel comfortable entering the pool area'],
                ['Arrive early, shower, and tie back long hair or wear a swim cap.', 'Walk the pool deck and note the shallow end, deep end, and lifeguard position.', 'Enter the shallow end using the steps or ladder.', 'Walk slowly in chest-deep water and notice how your body feels lighter.', 'Finish with a few minutes of relaxed breathing at the wall.'],
                ['Rushing into deep water before building comfort', 'Skipping the pre-swim shower and warm-up', 'Holding the breath and tensing the whole body'],
                ['Book short, frequent sessions rather than one long one.', 'Tell the lifeguard you are new so they can keep an eye out.', 'Focus on feeling relaxed, not on distance.']],
            ['beginner-swimming', 'beginner', 'confidence', 20, 'Water Familiarization',
                'Get used to water on your face, in your ears, and around your body.',
                'Confidence in the water starts with simple comfort drills. These exercises help you get used to water on your face and the feeling of moving through it.',
                ['Become comfortable with water on the face', 'Move confidently in shallow water', 'Open your eyes underwater with goggles'],
                ['Stand in shoulder-depth water holding the wall.', 'Splash water gently on your face using cupped hands.', 'Lower your chin into the water and blow bubbles.', 'Put goggles on and submerge your face for two seconds.', 'Walk across the shallow end swinging your arms to feel the resistance.'],
                ['Wiping the face constantly instead of adapting to the water', 'Letting go of the wall too early', 'Wearing goggles that are too tight or too loose'],
                ['Practice at the same time each week so progress feels familiar.', 'Exhale slowly through the nose to keep water out.', 'Celebrate small wins: two seconds becomes five.']],
            ['beginner-swimming', 'beginner', 'confidence', 20, 'How to Float',
                'Learn the relaxed front float that underpins every stroke.',
                'Floating shows you that water supports your body. A relaxed front float is the base of good body position in every stroke.',
                ['Hold a relaxed front float for five seconds', 'Understand how the lungs create buoyancy', 'Recover to standing safely'],
                ['Stand in waist-deep water with your hands on the wall or a partner nearby.', 'Take a breath and lower your face into the water.', 'Extend your arms forward and let your legs rise gently.', 'Hold the float, relaxed, for a few seconds.', 'To stand, pull knees to chest and press your hands down.'],
                ['Lifting the head up, which sinks the hips', 'Stiffening the legs and holding the breath too long', 'Forgetting how to stand up from the float'],
                ['Think of lying on the water, not fighting it.', 'Use a kickboard or a coach for the first attempts.', 'Practice the recovery to standing before you try longer floats.']],
            ['beginner-swimming', 'beginner', 'breathing', 20, 'Proper Breathing Techniques',
                'Rhythmic breathing: exhale underwater, inhale quickly above it.',
                'Good swimmers breathe out under the water and take quick breaths in. Learning this rhythm early removes the panic that makes swimming tiring.',
                ['Exhale steadily underwater', 'Inhale quickly with the mouth', 'Combine breathing with a simple float'],
                ['Hold the wall, take a breath, and put your face in the water.', 'Blow out slowly through your nose and mouth to make bubbles.', 'Turn your head to the side and take a short breath.', 'Return your face to the water and repeat ten times.', 'Add a front float between breaths when comfortable.'],
                ['Holding the breath and then gasping', 'Lifting the whole head instead of turning it', 'Breathing in through the nose underwater'],
                ['Hum underwater to keep the exhale steady.', 'Keep one goggle in the water when turning to breathe.', 'Count a steady rhythm: bubble, bubble, breathe.']],
            ['beginner-swimming', 'beginner', 'technique', 20, 'Basic Kicking',
                'Use a relaxed flutter kick to move and stabilize your body.',
                'The flutter kick keeps your legs up and helps you move forward. It starts at the hips and works best with loose ankles.',
                ['Perform a relaxed flutter kick', 'Keep kicks small and steady', 'Kick while holding a kickboard'],
                ['Sit on the pool edge and kick with straight but relaxed legs.', 'Hold a kickboard and push off the wall with your face in the water.', 'Kick from the hips with small, quick movements.', 'Keep toes loosely pointed and ankles floppy.', 'Lift your face to breathe when needed, then return to the water.'],
                ['Bending the knees too much like pedaling a bike', 'Kicking too big and too tense', 'Looking forward and dropping the hips'],
                ['Aim for a small splash, not a big one.', 'Stretch your calves and ankles before sessions.', 'Practice short repeats with rest at the wall.']],
            ['beginner-swimming', 'beginner', 'technique', 20, 'Basic Arm Movements',
                'Understand the catch, pull and recovery of the arm stroke.',
                'Arms provide most of the propulsion in swimming. Start with simple pulling patterns on land and in shallow water before adding the kick.',
                ['Describe the three phases of an arm stroke', 'Perform the pull with a high elbow', 'Combine arm pulls with a float'],
                ['Stand in shallow water, bend forward, and reach one arm forward.', 'Press the palm backward under the body, keeping the elbow high.', 'Finish the push past your hip.', 'Recover the arm forward above the water and alternate arms.', 'Add a kickboard-supported float and repeat.'],
                ['Pulling with a straight, stiff arm', 'Crossing the hand over the centerline', 'Rushing the stroke without a long reach'],
                ['Think long and slow: reach far, pull smoothly.', 'Film a short clip to check your elbow height.', 'Practice one arm at a time before both.']],
            ['beginner-swimming', 'beginner', 'confidence', 20, 'Floating on Your Back',
                'Learn a calm back float, one of the most useful rest positions.',
                'A back float lets you breathe freely and rest in the water. It is an important skill for building confidence and water safety.',
                ['Hold a calm back float', 'Keep hips high by pressing the chest up', 'Breathe naturally while floating'],
                ['Stand with your back to the wall or a coach for support.', 'Lean back and rest the back of your head in the water, ears submerged.', 'Look at the ceiling and press your chest up gently.', 'Spread arms and legs like a star for balance.', 'Breathe slowly and relax your whole body.'],
                ['Lifting the head to look at your feet', 'Sitting in the water with bent hips', 'Tightening up in fear of sinking'],
                ['A deep breath adds buoyancy.', 'Use a pull buoy or a coach for the first tries.', 'Gentle sculling with your hands keeps you steady.']],
            ['water-safety', 'beginner', 'confidence', 25, 'Basic Water Safety',
                'The rules, habits and awareness every swimmer needs from day one.',
                'Safety knowledge is part of learning to swim. This lesson covers the habits that reduce risk and prepares you to use the Water Safety Center in more depth.',
                ['Name key pool and open water rules', 'Know how to signal for help', 'Understand the value of supervision'],
                ['Always swim where a lifeguard or responsible adult can see you.', 'Learn where the exits, ladders and shallow zones are.', 'Practice raising an arm and calling for help in shallow water.', 'Never rely on inflatable toys as safety devices.', 'Read the Water Safety Center for emergency awareness steps.'],
                ['Swimming alone or without supervision', 'Overestimating ability in deep or moving water', 'Ignoring posted warnings and flags'],
                ['Swim with a partner and tell someone where you are going.', 'Take a certified course, including CPR training, in person.', 'Online lessons never replace hands-on instruction.']],

            ['intermediate-swimming', 'intermediate', 'technique', 30, 'Freestyle Swimming',
                'Put kick, arms and breathing together in front crawl.',
                'Freestyle is the fastest and most common stroke. This lesson links your kick, arm cycle and side breathing into a smooth rhythm.',
                ['Swim 25 meters of continuous freestyle', 'Coordinate arms with a steady kick', 'Breathe to the side without lifting the head'],
                ['Push off the wall in a streamlined position.', 'Start a steady flutter kick with face in the water.', 'Add alternating arm strokes, reaching long and pulling past the hip.', 'Rotate your body slightly with each stroke.', 'Turn your head to breathe during the arm recovery and return face to the water.'],
                ['Crossing the hands over in front of the head', 'Lifting the head forward to breathe', 'Kicking too hard and tiring quickly'],
                ['Glide for a moment after each push-off.', 'Practice with a snorkel to focus on arm technique.', 'Count strokes per length and aim to reduce them.']],
            ['intermediate-swimming', 'intermediate', 'technique', 30, 'Breaststroke Swimming',
                'Master the frog kick and the glide that makes breaststroke efficient.',
                'Breaststroke is a symmetrical stroke with a powerful frog kick and a distinct glide. Timing matters more than strength.',
                ['Perform an effective frog kick', 'Coordinate pull, breathe, kick and glide', 'Swim 25 meters of breaststroke'],
                ['Stand at the wall and practice the frog kick shape with your hands.', 'Practice kicking with a kickboard, feet turned out.', 'Add a small, sweeping arm pull to shoulder width.', 'Breathe as your hands pull, then kick as you shoot forward.', 'Glide with arms extended before the next cycle.'],
                ['Kicking before the arms have recovered forward', 'Pulling arms too far back past the shoulders', 'Skipping the glide'],
                ['Think: pull, breathe, kick, glide.', 'Keep your knees no wider than your hips.', 'Press the soles of the feet backward, not down.']],
            ['intermediate-swimming', 'intermediate', 'technique', 30, 'Backstroke Swimming',
                'Swim on your back with a steady kick and alternating arms.',
                'Backstroke lets you breathe freely and is a great way to relax your shoulders. Body rotation and a steady head position are the keys.',
                ['Hold a stable head and level hips', 'Rotate shoulders with each arm stroke', 'Swim 25 meters of backstroke'],
                ['Push off the wall on your back with arms overhead.', 'Start a small, continuous flutter kick.', 'Enter the hand pinky-first above the head.', 'Pull down along the body and rotate your shoulders.', 'Keep your eyes on the ceiling and your chin slightly tucked.'],
                ['Sitting too low in the water with bent hips', 'Lifting the head to look at the feet', 'Windmilling arms without rotating the body'],
                ['Keep a cup of water balanced on your forehead in your mind.', 'Use the lane lines or backstroke flags to guide your path.', 'Practice kicking on your side for balance.']],
            ['intermediate-swimming', 'intermediate', 'technique', 20, 'Proper Body Position',
                'Stay long, flat and streamlined to cut drag in every stroke.',
                'Your body position decides how much energy you spend. A flat, balanced body glides further with each stroke.',
                ['Keep the head, hips and heels in a line', 'Press the chest slightly down', 'Reduce drag in every stroke'],
                ['Push off the wall in a streamlined glide and notice your balance.', 'Look at the pool floor with a neutral neck.', 'Press your chest down and let the hips rise.', 'Squeeze your core gently to keep the body straight.', 'Repeat with a kick and then with full strokes.'],
                ['Head lifted too high', 'Hips sagging because the core is slack', 'Legs spread wide when kicking'],
                ['Imagine swimming downhill.', 'Use a pull buoy to feel high hips, then try without it.', 'Film from the side if possible.']],
            ['intermediate-swimming', 'intermediate', 'technique', 25, 'Swimming Coordination',
                'Combine arms, legs and breath so strokes flow without stopping.',
                'Coordination is timing: each limb does its job at the right moment. Drills break the stroke into pieces so you can rebuild it smoothly.',
                ['Link kick and arm timing', 'Use drills to fix timing errors', 'Keep a steady rhythm'],
                ['Do six kicks on your side, then switch sides.', 'Swim catch-up freestyle: one hand waits until the other finishes.', 'Add a fingertip-drag drill to improve recovery.', 'Swim a full length focusing only on rhythm.', 'Rest and repeat three times.'],
                ['Stopping the kick during the pull', 'Rushing the arm recovery', 'Holding the breath when concentrating'],
                ['Use a tempo trainer or count in your head.', 'Slow down to find rhythm, then speed up.', 'Ask a coach to watch one limb at a time.']],
            ['intermediate-swimming', 'intermediate', 'breathing', 25, 'Breathing While Swimming',
                'Take smooth side breaths without breaking your rhythm.',
                'Breathing while swimming means exhaling underwater and inhaling during the head turn. Bilateral breathing, every three strokes, helps balance the stroke.',
                ['Breathe on both sides', 'Exhale fully before each breath', 'Maintain stroke rhythm while breathing'],
                ['Hold the wall and practice rotating the head to each side.', 'Add a kickboard and one arm for side-breathing practice.', 'Swim freestyle breathing every two strokes.', 'Progress to every three strokes.', 'Rest and repeat, focusing on a slow exhale.'],
                ['Holding the breath underwater', 'Rotating the head too far', 'Breaking the kick when you breathe'],
                ['Keep one goggle in the water.', 'Breathe low and early in the arm recovery.', 'Practice on both sides evenly.']],
            ['swimming-fitness', 'intermediate', 'endurance', 35, 'Improving Swimming Speed',
                'Raise your pace with intervals and cleaner mechanics.',
                'Speed comes from efficiency and power. Interval training and technique work together to make you faster without burning out.',
                ['Use short intervals to build pace', 'Combine speed work with good technique', 'Track your times'],
                ['Warm up with 200 meters easy.', 'Swim 4 x 50 meters at a strong pace with 20 seconds rest.', 'Recover with 100 meters easy.', 'Repeat the main set once if you feel good.', 'Cool down with relaxed swimming.'],
                ['Sprinting every length and losing form', 'Skipping the warm-up', 'Resting too little between hard efforts'],
                ['Record split times in the Progress Tracker.', 'Quality before quantity.', 'Increase intensity in small steps each week.']],
            ['swimming-fitness', 'intermediate', 'endurance', 40, 'Swimming Endurance',
                'Build the ability to swim longer at a comfortable pace.',
                'Endurance is the ability to keep a good stroke for longer. Gradual increases in distance and steady pacing are more useful than occasional hard swims.',
                ['Swim continuously for 10 minutes or more', 'Use even pacing', 'Recover well between sessions'],
                ['Warm up with 100 meters of easy swimming.', 'Swim 4 x 100 meters at a steady pace with 30 seconds rest.', 'Gradually reduce the rest over several weeks.', 'Finish with 100 meters of choice stroke.', 'Log your total distance afterwards.'],
                ['Starting too fast', 'Increasing distance too quickly', 'Ignoring breathing rhythm'],
                ['Add 10 percent more distance per week at most.', 'Breathe every three strokes when tired.', 'Use the Endurance Builder challenge for motivation.']],

            ['advanced-swimming', 'advanced', 'technique', 35, 'Butterfly Swimming',
                'Learn the dolphin kick and powerful simultaneous arm stroke.',
                'Butterfly is demanding and rewarding. Rhythm and body undulation matter more than raw power.',
                ['Perform an undulating dolphin kick', 'Time two kicks per arm cycle', 'Swim 25 meters of butterfly'],
                ['Practice dolphin kick on your front and back with arms extended.', 'Add a single-arm butterfly drill, alternating arms.', 'Learn the two-kick timing: one as hands enter, one as they exit.', 'Breathe forward at the end of the pull with a low head.', 'Swim short repeats with long rests.'],
                ['Pulling the arms too wide', 'Lifting the head high to breathe', 'Over-kicking and tiring in a few strokes'],
                ['Think chest down, hips up.', 'Swim short distances with perfect form.', 'Use short fins to feel the body wave.']],
            ['advanced-swimming', 'advanced', 'technique', 35, 'Advanced Freestyle Techniques',
                'Refine the catch, rotation and kick timing for faster freestyle.',
                'At this level, freestyle gains come from small details: a high-elbow catch, long body line, and efficient six-beat or two-beat kick rhythm.',
                ['Use an early vertical forearm catch', 'Control rotation from the hips', 'Choose a kick rhythm for the distance'],
                ['Swim 200 meters easy and focus on length per stroke.', 'Use fist drill to feel the forearm catch.', 'Add paddles briefly to feel the pull.', 'Swim 6 x 50 meters at pace with 15 seconds rest.', 'Cool down with relaxed swimming.'],
                ['Dropping the elbow during the pull', 'Over-rotating the shoulders', 'Dead spots between strokes'],
                ['Film underwater if you can.', 'Count strokes and keep them consistent.', 'Use a two-beat kick for longer distances.']],
            ['advanced-swimming', 'advanced', 'technique', 30, 'Flip Turns',
                'Turn at the wall without stopping and push off in a tight streamline.',
                'Flip turns save time and keep rhythm. Practice in open water first, then at the wall, and always with enough depth and clear space.',
                ['Perform a clean tuck and rotation', 'Plant feet on the wall', 'Push off in a streamline'],
                ['Practice somersaults in open water with a tucked chin.', 'Swim toward the wall and begin the turn about one body length away.', 'Tuck, rotate, and place the feet on the wall.', 'Push off on your side or back with arms in streamline.', 'Rotate to your front and start kicking.'],
                ['Starting the flip too close to the wall', 'Unstreamlined push-off', 'Holding breath too long'],
                ['Exhale gently through the nose during the flip.', 'Practice in lanes with a coach for safety.', 'Do not attempt without enough water depth.']],
            ['advanced-swimming', 'advanced', 'technique', 30, 'Efficient Stroke Mechanics',
                'Reduce drag and wasted motion across all four strokes.',
                'Efficiency means going further with each stroke. Focus on distance per stroke, a long body line and relaxed hands.',
                ['Measure strokes per length', 'Lower drag with better alignment', 'Maintain form when tired'],
                ['Swim a length counting strokes and record it.', 'Try to reduce your count by one stroke.', 'Add a glide phase at the wall.', 'Review technique with a drill and video.', 'Repeat and compare your counts.'],
                ['Spinning arms faster to go faster', 'Crossing the midline', 'Ignoring pace changes'],
                ['SWOLF (strokes plus seconds) is a useful efficiency score.', 'Focus on one fix per session.', 'Relax hands and fingers slightly apart.']],
            ['advanced-swimming', 'advanced', 'breathing', 30, 'Advanced Breathing Control',
                'Use controlled breathing patterns to hold pace and stay relaxed.',
                'Advanced swimmers choose breathing patterns on purpose. Controlled breathing reduces fatigue and keeps stroke technique stable.',
                ['Use breathing patterns for different distances', 'Practice reduced-breath sets safely', 'Stay relaxed at high effort'],
                ['Warm up with easy swimming and a bilateral breathing pattern.', 'Swim 4 x 50 meters breathing every three strokes.', 'Swim 4 x 50 meters with a small-breath pattern only if comfortable.', 'Stop any set if you feel dizzy or uncomfortable.', 'Cool down and breathe easily.'],
                ['Holding the breath for long periods', 'Hypoxic sets without supervision', 'Ignoring warning signs such as dizziness'],
                ['Never practice breath-holding sets alone.', 'Always follow your coach and lifeguard.', 'Prioritize safety over performance.']],
            ['swimming-fitness', 'advanced', 'endurance', 40, 'Sprint Swimming',
                'Short, explosive efforts with full recovery and strong starts.',
                'Sprinting needs power, precision, and enough rest. Quality efforts with full recovery beat many tired repeats.',
                ['Swim 25 to 50 meters at high speed', 'Maintain stroke length at speed', 'Recover fully between efforts'],
                ['Warm up with at least 400 meters, including some fast 25s.', 'Swim 6 x 25 meters fast with 45 seconds rest.', 'Review your stroke count and tempo.', 'Cool down with 200 meters easy.', 'Log your times in the tracker.'],
                ['Skipping the warm-up', 'Resting too little, which reduces speed', 'Sacrificing technique for effort'],
                ['Keep sprint sessions short.', 'Focus on starts and finishes.', 'Allow enough recovery days.']],
            ['swimming-fitness', 'advanced', 'endurance', 45, 'Endurance Training',
                'Structured sets for aerobic base, pacing and mental toughness.',
                'Endurance training uses long sets at steady effort to build your aerobic base. Pacing and technique under fatigue are the focus.',
                ['Swim 800 meters or more continuously', 'Hold an even pace', 'Maintain stroke quality'],
                ['Warm up with 300 meters mixed strokes.', 'Swim 3 x 400 meters at an even pace with 45 seconds rest.', 'Check stroke count at the start and end of each set.', 'Finish with 200 meters easy.', 'Record distance and perceived effort.'],
                ['Going out too fast', 'Ignoring nutrition and hydration', 'Training hard every day without rest'],
                ['Hydrate even though you are in the water.', 'Plan recovery days.', 'Build up gradually and listen to your body.']],
        ];

        foreach ($lessons as $order => [$category, $level, $skill, $minutes, $title, $summary, $intro, $objectives, $steps, $mistakes, $tips]) {
            SwimmingLesson::updateOrCreate(['title' => $title], [
                'category_id' => $categories[$category],
                'level' => $level,
                'skill' => $skill,
                'duration_minutes' => $minutes,
                'summary' => $summary,
                'introduction' => $intro,
                'objectives' => $objectives,
                'steps' => $steps,
                'mistakes' => $mistakes,
                'tips' => $tips,
                'sort_order' => $order + 1,
                'is_published' => true,
            ]);
        }
    }

    private function techniques(): void
    {
        $techniques = [
            ['Freestyle', 'intermediate', 'Freestyle', 'The fast, flowing front crawl with alternating arms and a steady flutter kick.',
                'Freestyle, or front crawl, is the most common competitive stroke. The body stays long and flat while the arms alternate and the legs kick steadily.',
                'Keep your body flat and long, head neutral and looking at the pool floor, hips near the surface.',
                'Reach forward, enter the water fingertips first, catch the water with a high elbow, pull to the hip, then recover over the water.',
                'Use a steady flutter kick from the hips with relaxed ankles and small, quick movements.',
                'Exhale steadily underwater and turn the head to the side to inhale during the arm recovery.',
                ['Push off in a streamline and begin kicking.', 'Start alternating arm strokes with a long reach.', 'Rotate the body slightly with each stroke.', 'Turn the head to breathe on the recovery side.', 'Return the face to the water and keep a steady rhythm.'],
                ['Lifting the head to look forward', 'Crossing the hands in front of the head', 'Kicking from the knees'],
                ['Count strokes per length and try to lower the count.', 'Practice side kicking for balance.', 'Breathe on both sides for symmetry.']],
            ['Breaststroke', 'intermediate', 'Breaststroke', 'A symmetrical stroke with a frog kick, a glide, and the head above water to breathe.',
                'Breaststroke uses simultaneous arm and leg movements. It is often the first stroke people learn because the head can stay above water.',
                'Stay streamlined during the glide, with the chest slightly lower when the hands sweep and rising slightly as you breathe.',
                'Sweep both hands out and in under the chin in a small circular motion, then shoot the arms forward.',
                'Draw the heels toward the hips, turn the feet out, and kick backward in a circular motion before squeezing the legs together.',
                'Lift the head to breathe at the end of the pull and exhale as the arms extend forward.',
                ['Start in a long streamline.', 'Sweep the hands out and in.', 'Breathe as the hands come together.', 'Kick as the arms shoot forward.', 'Glide before beginning the next cycle.'],
                ['Pulling the hands too far back', 'Kicking with the knees too wide', 'Skipping the glide'],
                ['Remember the rhythm: pull, breathe, kick, glide.', 'Keep your heels close to the hips.', 'Be patient with the timing.']],
            ['Backstroke', 'intermediate', 'Backstroke', 'Swim on your back with alternating arms and a steady flutter kick.',
                'Backstroke is swum face up, so breathing is easy. Shoulder rotation and a steady head are the keys to a smooth stroke.',
                'Lie flat with your hips near the surface, head still, and eyes looking up at the ceiling.',
                'Enter the hand little-finger first above the shoulder, pull down along the body, and rotate the shoulders with each stroke.',
                'Use a small, steady flutter kick from the hips with relaxed ankles.',
                'Breathe freely and regularly; inhale on one arm and exhale on the other.',
                ['Push off the wall on your back, arms overhead.', 'Begin a steady flutter kick.', 'Reach one arm back, entering with the pinky first.', 'Pull and rotate the shoulders.', 'Alternate arms in a smooth rhythm.'],
                ['Sitting low in the water', 'Lifting the head', 'Using a straight, stiff arm without rotation'],
                ['Use the backstroke flags to know when the wall is near.', 'Keep the chin slightly tucked.', 'Practice on-your-back kicking drills.']],
            ['Butterfly', 'advanced', 'Butterfly', 'A powerful stroke with a dolphin kick and simultaneous arms.',
                'Butterfly demands rhythm, flexibility and strength. The body moves in a wave from chest to hips.',
                'Keep the body flat during the pull and let the chest and hips undulate in a wave.',
                'Both arms enter the water together, pull in a keyhole shape, and recover over the surface.',
                'Use a dolphin kick: two kicks per arm cycle, with the legs together.',
                'Lift the head slightly forward at the end of the pull to breathe, keeping the chin low.',
                ['Practice the dolphin kick with arms extended.', 'Add single-arm butterfly to learn timing.', 'Combine both arms with two kicks per cycle.', 'Breathe every second stroke at first.', 'Swim short distances with rest.'],
                ['Pulling the arms too wide', 'Lifting the head too high', 'Kicking from the knees only'],
                ['Think of the stroke as a wave.', 'Use short fins to learn the rhythm.', 'Swim short sets with good form.']],
            ['Floating', 'beginner', 'Float', 'The foundation of water comfort: letting the water support you.',
                'Floating shows that the body is naturally buoyant. Relaxed breathing and balance make floating easy.',
                'Lie flat on your front or back with arms extended or spread, head relaxed, and body horizontal.',
                'Extend the arms forward on the front float or spread them for balance on the back float.',
                'Keep the legs relaxed and together; small kicks help keep balance.',
                'Take a deep breath to increase buoyancy and breathe slowly.',
                ['Take a deep breath.', 'Lean forward or back and let the water support you.', 'Extend your arms for balance.', 'Relax your body and hold for five seconds.', 'Return to standing safely.'],
                ['Lifting the head', 'Tensing the body', 'Holding the breath too long'],
                ['Practice with a coach or partner.', 'Use a kickboard for support.', 'Start in shallow water.']],
            ['Breathing Techniques', 'beginner', 'Breathing', 'Rhythmic breathing patterns that keep you calm and efficient.',
                'Breathing is the most common challenge for new swimmers. Exhaling underwater makes inhaling quick and easy.',
                'Keep the body flat and rotate the head only as much as needed to reach the surface.',
                'Arms continue stroking while the head turns to breathe during the recovery.',
                'Keep a steady kick to stabilize the body when the head turns.',
                'Exhale slowly through the nose and mouth underwater, then take a quick breath in.',
                ['Practice bubbles at the wall.', 'Add head turns to the side.', 'Combine with a kick.', 'Add arm strokes.', 'Try bilateral breathing every three strokes.'],
                ['Holding the breath', 'Lifting the head too high', 'Breathing too late'],
                ['Hum underwater.', 'Keep one goggle in the water.', 'Relax your jaw and neck.']],
            ['Treading Water', 'intermediate', 'Water Safety', 'Stay upright and afloat in deep water with a rotary kick.',
                'Treading water lets you stay upright in deep water, rest, and look around. It is an important safety skill.',
                'Keep the body vertical with the head above water and the back straight.',
                'Sweep the hands in small figure-eight patterns near the surface.',
                'Use an eggbeater kick, alternating circular leg movements, or a scissor kick.',
                'Breathe normally and stay relaxed.',
                ['Stand upright in deep water, with supervision.', 'Begin the leg kick in circles.', 'Sweep the arms in small patterns.', 'Keep the head above the water.', 'Stay calm and conserve energy.'],
                ['Using large, tiring arm movements', 'Leaning back', 'Panicking and holding the breath'],
                ['Practice near the wall at first.', 'Use short intervals and rest.', 'Take a class for the eggbeater kick.']],
            ['Basic Diving Techniques', 'intermediate', 'Body Position', 'Safe entries into deep water from a seated or kneeling position.',
                'Diving needs depth and supervision. Learn entries from the pool edge only in water that is clearly deep enough and where diving is permitted.',
                'Keep the arms overhead, the head between the arms, and the body streamlined on entry.',
                'Squeeze the arms together above the head and extend them forward at entry.',
                'Push off from the edge with both legs and point the toes.',
                'Exhale gently as you glide and surface for a breath.',
                ['Confirm the water is deep enough and diving is allowed.', 'Start from a kneeling or seated position at the edge.', 'Tuck your chin and extend your arms overhead.', 'Fall forward into a streamlined entry.', 'Glide, then surface and swim.'],
                ['Diving in shallow or unknown water', 'Lifting the head on entry', 'Diving without supervision'],
                ['Never dive in unfamiliar water.', 'Learn with a certified coach.', 'Always check depth and obstacles first.']],
        ];

        foreach ($techniques as [$title, $level, $keyword, $summary, $description, $position, $arms, $legs, $breathing, $steps, $mistakes, $tips]) {
            Technique::updateOrCreate(['title' => $title], [
                'level' => $level,
                'keyword' => $keyword,
                'summary' => $summary,
                'description' => $description,
                'body_position' => $position,
                'arm_movement' => $arms,
                'leg_movement' => $legs,
                'breathing_pattern' => $breathing,
                'steps' => $steps,
                'mistakes' => $mistakes,
                'tips' => $tips,
            ]);
        }
    }

    private function equipment(): void
    {
        $items = [
            ['Swimming Goggles', 'Close-fitting eyewear that keeps water out of your eyes.', 'Protects the eyes from chlorine and improves underwater vision.', 'Lets you swim comfortably with your face in the water and see the pool floor and lane lines.', 'Everyone, especially beginners and lap swimmers', ['Adjust the strap so the seal is snug but comfortable.', 'Wet the lenses and wipe with a soft cloth to reduce fogging.', 'Rinse after use and store in a case.']],
            ['Swimming Caps', 'A stretchy cap that covers the hair.', 'Reduces drag and keeps hair out of the face.', 'Protects hair from chlorine and keeps the pool cleaner.', 'Anyone with long hair and regular swimmers', ['Pull the cap over the hairline and ears.', 'Tuck all hair inside.', 'Rinse and dry the cap after use.']],
            ['Kickboards', 'A buoyant foam board held with the hands.', 'Isolates the legs so you can focus on kicking.', 'Builds leg strength and teaches body position.', 'Beginners and swimmers working on kick technique', ['Hold the board near the front edge, arms extended.', 'Keep the face in the water and breathe regularly.', 'Kick from the hips, not the knees.']],
            ['Pull Buoys', 'A foam float placed between the thighs.', 'Lifts the hips so you can focus on the arms.', 'Isolates upper body strength and improves body position.', 'Intermediate and advanced swimmers', ['Hold the buoy between the thighs, not the ankles.', 'Keep the head neutral and the body flat.', 'Do not use it when you cannot swim safely without it.']],
            ['Swim Fins', 'Flexible fins worn on the feet.', 'Increase kick power and ankle flexibility.', 'Help you feel the body glide and build leg strength.', 'Intermediate and advanced swimmers, and drills', ['Choose a snug fit that does not rub.', 'Kick with long, relaxed legs.', 'Use short training fins for stroke work.']],
            ['Nose Clips', 'A small clip that closes the nostrils.', 'Prevents water from entering the nose.', 'Allows you to focus on technique without discomfort.', 'Backstroke swimmers, flip turn learners, and sensitive noses', ['Place the clip snugly over the nostrils.', 'Practice breathing through the mouth.', 'Rinse after each use.']],
            ['Ear Plugs', 'Silicone plugs that seal the ear canal.', 'Keep water out of the ears.', 'Help reduce ear irritation and infections for frequent swimmers.', 'Swimmers prone to ear problems', ['Insert gently, do not force.', 'Make sure the plugs form a seal.', 'Consult a doctor for ear pain or discharge.']],
            ['Training Paddles', 'Hand paddles worn on the palms.', 'Increase resistance during the pull.', 'Build strength and teach a proper catch.', 'Advanced swimmers with good technique', ['Start with small paddles.', 'Use them for short sets only.', 'Stop if your shoulders feel sore.']],
            ['Swimming Suits', 'Swimwear designed for pool training.', 'Provides comfort and low drag for swimming.', 'Lasts longer in chlorine and stays secure during movement.', 'All swimmers, in training and competition styles', ['Choose a snug but comfortable fit.', 'Rinse after each swim.', 'Dry flat away from direct sun.']],
        ];

        foreach ($items as [$name, $description, $purpose, $benefits, $for, $tips]) {
            EquipmentGuide::updateOrCreate(['name' => $name], [
                'description' => $description,
                'purpose' => $purpose,
                'benefits' => $benefits,
                'recommended_for' => $for,
                'usage_tips' => $tips,
            ]);
        }
    }

    private function safety(): void
    {
        $guides = [
            ['Basic Water Safety Rules', 'The habits that prevent most water accidents.', 'Water safety starts with simple rules: swim where you can be seen, follow posted signs, and know your limits.', ['Never swim alone.', 'Follow lifeguard and staff instructions.', 'Obey signs, flags and depth markers.', 'Do not run on pool decks.']],
            ['Swimming Pool Safety', 'Rules and awareness for public and private pools.', 'Pools are controlled environments, but accidents still happen. Know where the exits, depth markers and rescue equipment are.', ['Check the depth before entering.', 'Do not dive in shallow water.', 'Keep pool gates closed and locked.', 'Supervise children constantly.']],
            ['Beach and Open Water Safety', 'Extra caution for natural water.', 'Open water has currents, waves, cold temperatures and changing conditions. It is very different from a pool.', ['Swim at supervised beaches.', 'Swim between the lifeguard flags.', 'Check conditions and the weather.', 'Learn what rip currents are and how to signal for help.']],
            ['Recognizing Water Hazards', 'Learn to spot risks before they become emergencies.', 'Hazards include strong currents, cold water, underwater objects, weeds, and slippery surfaces.', ['Watch for signs and warning flags.', 'Avoid unfamiliar or murky water.', 'Stay away from piers, rocks and structures.', 'Keep alert for changing weather.']],
            ['Understanding Water Depth', 'Depth changes everything: comfort, safety and diving.', 'Water depth can change suddenly in lakes and the sea. In pools, depth markers show where it is safe to stand and dive.', ['Read the depth markers.', 'Stay within your depth.', 'Never assume that unfamiliar water is deep enough for diving.', 'Use flotation aids if you are not a confident swimmer.']],
            ['Swimming With a Partner', 'The buddy system adds a second set of eyes.', 'A partner can notice problems early and get help. Agree on signals and stay within reach.', ['Choose a partner with similar ability.', 'Agree on signals for help.', 'Keep each other in view.', 'Do not leave your partner alone in the water.']],
            ['Importance of Lifeguards', 'Trained professionals who prevent and respond to emergencies.', 'Lifeguards watch the water, enforce rules and respond to emergencies. Their presence greatly lowers risk.', ['Swim only where lifeguards are on duty.', 'Follow their instructions immediately.', 'Tell them if you are a beginner.', 'Do not distract lifeguards on duty.']],
            ['Basic Drowning Prevention Awareness', 'Drowning is often quick and quiet.', 'Drowning rarely looks like in the movies. A person can slip under with little noise. Constant supervision and barriers are the best prevention.', ['Never leave children unattended near water.', 'Learn the signs: head low, mouth at the water level, no calls for help.', 'Use barriers and covers for private pools.', 'Take a certified swimming and CPR course.']],
            ['Emergency Response Awareness', 'Know how to get help quickly.', 'In an emergency, getting trained help fast matters most. Call your local emergency number and alert a lifeguard. Do not enter the water to attempt a rescue beyond your training; use a reaching or throwing aid from a safe position only if it is safe to do so.', ['Shout for help and alert a lifeguard immediately.', 'Call your local emergency number.', 'Reach or throw a flotation aid from the edge only if safe.', 'Do not enter the water unless you are trained.']],
        ];

        foreach ($guides as $order => [$title, $summary, $content, $points]) {
            SafetyGuide::updateOrCreate(['title' => $title], [
                'summary' => $summary,
                'content' => $content,
                'points' => $points,
                'sort_order' => $order + 1,
            ]);
        }
    }
}
