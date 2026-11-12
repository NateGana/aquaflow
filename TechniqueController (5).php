<?php

namespace App\Http\Controllers\Admin;

use App\Models\Technique;
use App\Support\Level;

class TechniqueController extends CrudController
{
    protected function model(): string
    {
        return Technique::class;
    }

    protected function route(): string
    {
        return 'admin.techniques';
    }

    protected function singular(): string
    {
        return 'Technique';
    }

    protected function plural(): string
    {
        return 'Swimming techniques';
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:150'],
            ['name' => 'level', 'label' => 'Difficulty level', 'type' => 'select', 'options' => Level::ALL, 'rules' => 'required|in:beginner,intermediate,advanced'],
            ['name' => 'keyword', 'label' => 'Keyword for related lessons', 'type' => 'text', 'rules' => 'nullable|string|max:100'],
            ['name' => 'summary', 'label' => 'Short description', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'description', 'label' => 'Technique description', 'type' => 'textarea', 'rules' => 'required|string|max:5000'],
            ['name' => 'body_position', 'label' => 'Proper body position', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
            ['name' => 'arm_movement', 'label' => 'Arm movement', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
            ['name' => 'leg_movement', 'label' => 'Leg movement', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
            ['name' => 'breathing_pattern', 'label' => 'Breathing pattern', 'type' => 'textarea', 'rules' => 'required|string|max:3000'],
            ['name' => 'steps', 'label' => 'Step-by-step instructions (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:8000'],
            ['name' => 'mistakes', 'label' => 'Common mistakes (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'tips', 'label' => 'Improvement tips (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:5000'],
            ['name' => 'image', 'label' => 'Image (JPG, PNG or WebP, max 2 MB)', 'type' => 'file', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
        ];
    }

    protected function columns(): array
    {
        return [['key' => 'title', 'label' => 'Title'], ['key' => 'level', 'label' => 'Level', 'badge' => 'level'], ['key' => 'summary', 'label' => 'Summary']];
    }
}
