<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SwimmingChallenge;
use App\Models\SwimmingLesson;
use App\Models\SwimmingRecord;
use App\Models\TrainingProgram;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Database\Seeders\ProgramSeeder;
use Database\Seeders\QuizSeeder;
use Database\Seeders\RewardSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AquaFlowTest extends TestCase
{
    use RefreshDatabase;

    private function member(array $attributes = []): User
    {
        $role = Role::firstOrCreate(['name' => 'member'], ['label' => 'Member']);

        return User::factory()->create($attributes + ['role_id' => $role->id]);
    }

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_home_page_loads(): void
    {
        $this->seed(ContentSeeder::class);

        $this->get('/')->assertOk()->assertSee('Make Every Stroke Count.');
    }

    public function test_registration_creates_account_and_redirects_to_dashboard(): void
    {
        Role::create(['name' => 'member', 'label' => 'Member']);

        $this->post(route('register.store'), [
            'name' => 'Test Swimmer',
            'email' => 'test@example.com',
            'password' => 'password1',
            'password_confirmation' => 'password1',
            'swimming_level' => 'beginner',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'swimming_level' => 'beginner']);
        $this->assertAuthenticated();
    }

    public function test_deactivated_user_cannot_log_in(): void
    {
        $user = $this->member(['is_active' => false]);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guests_are_redirected_from_private_pages(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('records.index'))->assertRedirect(route('login'));
    }

    public function test_regular_users_cannot_access_admin(): void
    {
        $this->actingAs($this->member())->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($this->admin())->get(route('admin.dashboard'))->assertOk();
    }

    public function test_completing_a_lesson_awards_the_first_splash_badge(): void
    {
        $this->seed([ContentSeeder::class, RewardSeeder::class]);
        $user = $this->member();
        $lesson = SwimmingLesson::first();

        $this->actingAs($user)->post(route('lessons.complete', $lesson))->assertRedirect();

        $this->assertDatabaseHas('lesson_progress', ['user_id' => $user->id, 'lesson_id' => $lesson->id]);
        $this->assertTrue($user->achievements()->where('name', 'First Splash')->exists());
    }

    public function test_user_cannot_modify_another_users_record(): void
    {
        $owner = $this->member();
        $intruder = $this->member();
        $record = SwimmingRecord::create(['user_id' => $owner->id, 'swam_on' => today(), 'style' => 'freestyle', 'distance_m' => 100, 'duration_min' => 10, 'laps' => 4]);

        $this->actingAs($intruder)->put(route('records.update', $record), [
            'swam_on' => today()->toDateString(), 'style' => 'freestyle', 'distance_m' => 999, 'duration_min' => 10,
        ])->assertForbidden();

        $this->actingAs($intruder)->delete(route('records.destroy', $record))->assertForbidden();
        $this->assertDatabaseHas('swimming_records', ['id' => $record->id, 'distance_m' => 100]);
    }

    public function test_record_crud_for_owner(): void
    {
        $user = $this->member();

        $this->actingAs($user)->post(route('records.store'), [
            'swam_on' => today()->toDateString(), 'style' => 'backstroke', 'distance_m' => 200, 'duration_min' => 25, 'laps' => 8,
        ])->assertRedirect();

        $record = $user->records()->first();
        $this->assertSame(200, $record->distance_m);

        $this->actingAs($user)->put(route('records.update', $record), [
            'swam_on' => today()->toDateString(), 'style' => 'backstroke', 'distance_m' => 250, 'duration_min' => 25, 'laps' => 10,
        ])->assertRedirect();
        $this->assertSame(250, $record->fresh()->distance_m);

        $this->actingAs($user)->delete(route('records.destroy', $record))->assertRedirect();
        $this->assertDatabaseMissing('swimming_records', ['id' => $record->id]);
    }

    public function test_challenge_progress_completes_and_awards_points(): void
    {
        $this->seed(RewardSeeder::class);
        $user = $this->member();
        $challenge = SwimmingChallenge::where('title', 'First 100 Meters')->first();

        $this->actingAs($user)->post(route('challenges.join', $challenge))->assertRedirect();
        $this->actingAs($user)->post(route('records.store'), [
            'swam_on' => today()->toDateString(), 'style' => 'freestyle', 'distance_m' => 120, 'duration_min' => 15, 'laps' => 5,
        ]);

        $participant = $user->participations()->first();
        $this->assertNotNull($participant->completed_at);
        $this->assertSame(50, $participant->points_awarded);
        $this->assertGreaterThanOrEqual(50, $user->fresh()->points);
    }

    public function test_program_enrollment_and_completion_tracking(): void
    {
        $this->seed(ProgramSeeder::class);
        $user = $this->member();
        $program = TrainingProgram::where('level', 'beginner')->first();
        $exercise = $program->exercises()->first();

        $this->actingAs($user)->post(route('programs.enroll', $program))->assertRedirect();
        $this->actingAs($user)->post(route('programs.toggle', [$program, $exercise]))->assertRedirect();

        $enrollment = $user->enrollments()->first();
        $this->assertSame([$exercise->id], $enrollment->completedIds());
        $this->assertNull($enrollment->completed_at);
    }

    public function test_quiz_scoring_matches_correct_answers(): void
    {
        $this->seed(QuizSeeder::class);
        $user = $this->member();
        $category = \App\Models\QuizCategory::first();

        $this->actingAs($user)->post(route('quiz.start', $category))->assertRedirect();
        $attempt = $user->quizAttempts()->first();

        foreach ($attempt->question_ids as $index => $questionId) {
            $choices = \App\Models\QuizChoice::where('quiz_question_id', $questionId)->get();
            $choice = $index < 3 ? $choices->firstWhere('is_correct', true) : $choices->firstWhere('is_correct', false);

            $this->actingAs($user)->postJson(route('quiz.answer', $attempt), ['question_id' => $questionId, 'choice_id' => $choice->id])
                ->assertOk()->assertJsonPath('correct', $index < 3);
        }

        $this->actingAs($user)->post(route('quiz.finish', $attempt))->assertRedirect(route('quiz.result', $attempt));

        $this->assertSame(3, $attempt->fresh()->score);
        $this->actingAs($user)->get(route('quiz.result', $attempt))->assertOk();
    }

    public function test_admin_can_create_a_lesson(): void
    {
        $this->seed(ContentSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.lessons.store'), [
            'title' => 'Open Water Basics',
            'category_id' => \App\Models\SwimmingCategory::first()->id,
            'level' => 'intermediate',
            'skill' => 'confidence',
            'duration_minutes' => 30,
            'summary' => 'Intro to open water.',
            'introduction' => 'Open water differs from a pool.',
            'objectives' => "Know the hazards\nSwim with a buddy",
            'is_published' => 1,
        ])->assertRedirect(route('admin.lessons.index'));

        $lesson = SwimmingLesson::where('title', 'Open Water Basics')->first();
        $this->assertSame(['Know the hazards', 'Swim with a buddy'], $lesson->objectives);
        $this->assertSame('open-water-basics', $lesson->slug);
    }

    public function test_search_returns_matching_lessons(): void
    {
        $this->seed(ContentSeeder::class);

        $this->get(route('search', ['q' => 'freestyle']))->assertOk()->assertSee('Freestyle Swimming');
    }
}
