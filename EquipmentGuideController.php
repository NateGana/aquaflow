<?php

namespace App\Http\Controllers\Admin;

use App\Models\EquipmentGuide;

class EquipmentGuideController extends CrudController
{
    protected function model(): string
    {
        return EquipmentGuide::class;
    }

    protected function route(): string
    {
        return 'admin.equipment';
    }

    protected function singular(): string
    {
        return 'Equipment guide';
    }

    protected function plural(): string
    {
        return 'Equipment guides';
    }

    protected function searchColumn(): string
    {
        return 'name';
    }

    protected function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Equipment name', 'type' => 'text', 'rules' => 'required|string|max:120'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rules' => 'required|string|max:2000'],
            ['name' => 'purpose', 'label' => 'Purpose', 'type' => 'textarea', 'rules' => 'required|string|max:1000'],
            ['name' => 'benefits', 'label' => 'Benefits', 'type' => 'textarea', 'rules' => 'required|string|max:1000'],
            ['name' => 'recommended_for', 'label' => 'Recommended users', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'usage_tips', 'label' => 'Proper usage tips (one per line)', 'type' => 'lines', 'rules' => 'nullable|string|max:3000'],
            ['name' => 'image', 'label' => 'Product image (JPG, PNG or WebP, max 2 MB)', 'type' => 'file', 'rules' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
        ];
    }

    protected function columns(): array
    {
        return [['key' => 'name', 'label' => 'Equipment'], ['key' => 'recommended_for', 'label' => 'Recommended for'], ['key' => 'purpose', 'label' => 'Purpose']];
    }
}
