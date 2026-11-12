<?php

namespace App\Http\Controllers\Admin;

use App\Models\SwimmingCategory;
use App\Models\SwimmingLesson;
use App\Support\Level;

class LessonController extends CrudController
{
    protected function model(): string
    {
        return SwimmingLesson::class;
    }

    protected function route(): string
    {
        return 'admin.lessons';
    }

    protected function singular(): string
    {
        return 'Lesson';
    }

    protected function plural(): string
    {
        return 'Swimming lessons';
    }

    protected function with(): array
    {
        return ['category'];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:150'],
            ['name' => 'category_id', 'label' => 'Category', 'type' => 'select', 'options' => SwimmingCategory::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'required|exists:swimming_categories,id'],
            ['name' => 'level', 'label' => 'Difficulty level', 'type' => 'select', 'options' => Level::ALL, 'rules' => 'required|in:beginner,intermediate,advanced'],
            ['name' => 'skill', 'label' => 'Skill focus', 'type' => 'select', 'options' => Level::SKILLS, 'rules' => 'required|in:technique,breathing,confidence,endurance'],
            ['name' => 'duration_minutes', 'label' => 'Duration (minutes)', 'type' => 'number', 'rules' => 'required|integer|min:1|max:600'],
            ['name' => 'summary', 'label' => 'Short description', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'introduction', 'label' => 'Introduction', 'type' => 'textarea', 'rules' => 'required|string|max:5000'],
            ['name' => 'objectives', 'label' => 'Learning objectives (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'steps', 'label' => 'Step-by-step instructions (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:8000'],
            ['name' => 'mistakes', 'label' => 'Common mistakes (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'tips', 'label' => 'Improvement tips (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'image', 'label' => 'Thumbnail image (JPG, PNG or WebP, max 2 MB)', 'type' => 'file', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
            ['name' => 'video_url', 'label' => 'Video URL (YouTube or Vimeo, optional)', 'type' => 'url', 'rules' => 'nullable|url|max:255'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0],
            ['name' => 'is_published', 'label' => 'Published', 'type' => 'checkbox', 'default' => true],
        ];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'category.name', 'label' => 'Category'],
            ['key' => 'level', 'label' => 'Level', 'badge' => 'level'],
            ['key' => 'duration_minutes', 'label' => 'Minutes'],
            ['key' => 'is_published', 'label' => 'Published', 'badge' => 'bool'],
        ];
    }
}
