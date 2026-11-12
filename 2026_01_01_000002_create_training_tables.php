<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('level')->index();
            $table->unsignedTinyInteger('duration_weeks');
            $table->unsignedTinyInteger('sessions_per_week')->default(3);
            $table->string('summary');
            $table->json('objectives')->nullable();
            $table->json('equipment')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('program_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('training_programs')->cascadeOnDelete();
            $table->unsignedTinyInteger('week');
            $table->unsignedTinyInteger('day');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('distance_m')->nullable();
            $table->timestamps();
            $table->index(['program_id', 'week', 'day']);
        });

        Schema::create('program_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained('training_programs')->cascadeOnDelete();
            $table->json('completed_exercises')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'program_id']);
        });

        Schema::create('swimming_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('swam_on');
            $table->string('style');
            $table->unsignedInteger('distance_m');
            $table->unsignedSmallInteger('duration_min');
            $table->unsignedSmallInteger('laps')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'swam_on']);
        });

        Schema::create('swimming_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type');
            $table->string('style')->nullable();
            $table->unsignedInteger('target');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->unsignedInteger('points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('challenge_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('challenge_id')->constrained('swimming_challenges')->cascadeOnDelete();
            $table->date('joined_on');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('points_awarded')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'challenge_id']);
        });

        Schema::create('training_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('activity');
            $table->date('scheduled_on');
            $table->time('start_time');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'scheduled_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
        Schema::dropIfExists('challenge_participants');
        Schema::dropIfExists('swimming_challenges');
        Schema::dropIfExists('swimming_records');
        Schema::dropIfExists('program_enrollments');
        Schema::dropIfExists('program_exercises');
        Schema::dropIfExists('training_programs');
    }
};
