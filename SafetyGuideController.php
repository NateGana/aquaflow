<?php

namespace App\Http\Controllers\Admin;

use App\Models\SafetyGuide;

class SafetyGuideController extends CrudController
{
    protected function model(): string
    {
        return SafetyGuide::class;
    }

    protected function route(): string
    {
        return 'admin.safety-guides';
    }

    protected function singular(): string
    {
        return 'Safety guide';
    }

    protected function plural(): string
    {
        return 'Water safety guides';
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:150'],
            ['name' => 'summary', 'label' => 'Summary', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'rules' => 'required|string|max:8000'],
            ['name' => 'points', 'label' => 'Key points (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:3000'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'rules' => 'nullable|integer|min:0', 'default' => 0],
        ];
    }

    protected function columns(): array
    {
        return [['key' => 'title', 'label' => 'Title'], ['key' => 'summary', 'label' => 'Summary'], ['key' => 'sort_order', 'label' => 'Order']];
    }
}
