<?php

namespace App\Http\Controllers\Admin;

use App\Models\SwimmingCategory;

class CategoryController extends CrudController
{
    protected function model(): string
    {
        return SwimmingCategory::class;
    }

    protected function route(): string
    {
        return 'admin.categories';
    }

    protected function singular(): string
    {
        return 'Category';
    }

    protected function plural(): string
    {
        return 'Lesson categories';
    }

    protected function searchColumn(): string
    {
        return 'name';
    }

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'rules' => 'required|string|max:100'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
        ];
    }

    protected function columns(): array
    {
        return [['key' => 'name', 'label' => 'Name'], ['key' => 'slug', 'label' => 'Slug'], ['key' => 'description', 'label' => 'Description']];
    }
}
