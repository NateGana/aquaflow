<?php

namespace App\Http\Controllers\Admin;

use App\Models\SwimmingChallenge;
use App\Services\ChallengeService;

class ChallengeController extends CrudController
{
    protected function model(): string
    {
        return SwimmingChallenge::class;
    }

    protected function route(): string
    {
        return 'admin.challenges';
    }

    protected function singular(): string
    {
        return 'Challenge';
    }

    protected function plural(): string
    {
        return 'Swimming challenges';
    }

    protected function counts(): array
    {
        return ['participants'];
    }

    protected function fields(): array
    {
        return [
            ['name' => 'title', 'label' => 'Challenge title', 'type' => 'text', 'rules' => 'required|string|max:150'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rules' => 'required|string|max:1000'],
            ['name' => 'type', 'label' => 'Progress is measured by', 'type' => 'select', 'options' => SwimmingChallenge::TYPES, 'rules' => 'required|in:distance,style_distance,sessions,streak,single_distance'],
            ['name' => 'style', 'label' => 'Style (only for style-distance challenges)', 'type' => 'select', 'options' => ['' => 'Not applicable'] + \App\Support\Level::STYLES, 'rules' => 'nullable|in:freestyle,breaststroke,backstroke,butterfly,mixed,drills'],
            ['name' => 'target', 'label' => 'Target', 'type' => 'number', 'rules' => 'required|integer|min:1|max:1000000'],
            ['name' => 'starts_on', 'label' => 'Start date', 'type' => 'date', 'rules' => 'required|date'],
            ['name' => 'ends_on', 'label' => 'End date', 'type' => 'date', 'rules' => 'required|date|after_or_equal:starts_on'],
            ['name' => 'points', 'label' => 'Reward points', 'type' => 'number', 'rules' => 'required|integer|min:0|max:100000'],
            ['name' => 'is_active', 'label' => 'Active', 'type' => 'checkbox', 'default' => true],
        ];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Challenge'],
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'target', 'label' => 'Target'],
            ['key' => 'ends_on', 'label' => 'Ends', 'badge' => 'date'],
            ['key' => 'participants_count', 'label' => 'Participants'],
        ];
    }

    public function show(string $id, ChallengeService $service)
    {
        $challenge = $this->find($id);

        $rows = $challenge->participants()->with('user')->get()->map(fn ($participant) => [
            'participant' => $participant,
            'value' => min($service->progress($participant), $challenge->target),
            'percent' => $participant->completed_at ? 100 : $service->percent($participant),
        ]);

        return view('admin.challenges.show', ['challenge' => $challenge, 'rows' => $rows]);
    }
}
