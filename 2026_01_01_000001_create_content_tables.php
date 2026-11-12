<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('swimming_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('swimming_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('swimming_categories')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('level')->index();
            $table->string('skill')->default('technique')->index();
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('summary');
            $table->text('introduction');
            $table->json('objectives')->nullable();
            $table->json('steps')->nullable();
            $table->json('mistakes')->nullable();
            $table->json('tips')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('techniques', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('level')->index();
            $table->string('keyword')->nullable();
            $table->string('summary');
            $table->text('description');
            $table->text('body_position');
            $table->text('arm_movement');
            $table->text('leg_movement');
            $table->text('breathing_pattern');
            $table->json('steps')->nullable();
            $table->json('mistakes')->nullable();
            $table->json('tips')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('swimming_lessons')->cascadeOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });

        Schema::create('equipment_guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('purpose');
            $table->text('benefits');
            $table->string('recommended_for');
            $table->json('usage_tips')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('safety_guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->text('content');
            $table->json('points')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_guides');
        Schema::dropIfExists('equipment_guides');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('techniques');
        Schema::dropIfExists('swimming_lessons');
        Schema::dropIfExists('swimming_categories');
    }
};
