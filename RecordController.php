<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ChecksOwnership;
use App\Models\SwimmingRecord;
use App\Services\AchievementService;
use App\Services\ChallengeService;
use App\Support\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class RecordController extends Controller
{
    use ChecksOwnership;

    public function index(Request $request)
    {
        $user = $request->user();
        $all = $user->records()->orderBy('swam_on')->orderBy('id')->get();

        $weeks = collect(range(7, 0))->map(fn ($i) => now()->startOfWeek()->subWeeks($i));
        $months = collect(range(5, 0))->map(fn ($i) => now()->startOfMonth()->subMonths($i));

        $bests = $all->isEmpty() ? collect() : collect([
            ['label' => 'Longest distance', 'value' => $all->max('distance_m').' m', 'date' => $all->sortByDesc('distance_m')->first()->swam_on],
            ['label' => 'Longest session', 'value' => $all->max('duration_min').' min', 'date' => $all->sortByDesc('duration_min')->first()->swam_on],
            ['label' => 'Most laps', 'value' => $all->max('laps').' laps', 'date' => $all->sortByDesc('laps')->first()->swam_on],
        ]);

        $styleBests = $all->groupBy('style')->map(fn ($group) => $group->max('distance_m'))->sortDesc();

        return view('records.index', [
            'history' => $user->records()->latest('swam_on')->latest('id')->paginate(10),
            'stats' => [
                'distance' => (int) $all->sum('distance_m'),
                'sessions' => $all->count(),
                'average' => (int) round($all->avg('duration_min') ?? 0),
                'longest' => (int) ($all->max('distance_m') ?? 0),
            ],
            'bests' => $bests,
            'styleBests' => $styleBests,
            'charts' => [
                'weekly' => [
                    'labels' => $weeks->map(fn ($w) => $w->format('d M'))->all(),
                    'values' => $weeks->map(fn ($w) => (int) $all->filter(fn ($r) => $r->swam_on->betweenIncluded($w, $w->copy()->endOfWeek()))->sum('distance_m'))->all(),
                ],
                'monthly' => [
                    'labels' => $months->map(fn ($m) => $m->format('M Y'))->all(),
                    'values' => $months->map(fn ($m) => (int) $all->filter(fn ($r) => $r->swam_on->isSameMonth($m))->sum('distance_m'))->all(),
                ],
                'progression' => [
                    'labels' => $all->take(-15)->map(fn ($r) => $r->swam_on->format('d M'))->values()->all(),
                    'values' => $all->take(-15)->pluck('distance_m')->values()->all(),
                ],
            ],
            'styles' => Level::STYLES,
        ]);
    }

    public function store(Request $request, ChallengeService $challenges, AchievementService $achievements): RedirectResponse
    {
        $request->user()->records()->create($this->validated($request));

        $challenges->evaluate($request->user());
        $achievements->sync($request->user());

        return back()->with('success', 'Swimming session recorded.');
    }

    public function update(Request $request, SwimmingRecord $record, ChallengeService $challenges, AchievementService $achievements): RedirectResponse
    {
        $this->owned($record)->update($this->validated($request));

        $challenges->evaluate($request->user());
        $achievements->sync($request->user());

        return back()->with('success', 'Session updated.');
    }

    public function destroy(SwimmingRecord $record): RedirectResponse
    {
        $this->owned($record)->delete();

        return back()->with('success', 'Session deleted.');
    }

    private function validated(Request $request): array
    {
        return array_merge($request->validate([
            'swam_on' => ['required', 'date', 'before_or_equal:today'],
            'style' => ['required', Rule::in(array_keys(Level::STYLES))],
            'distance_m' => ['required', 'integer', 'min:1', 'max:100000'],
            'duration_min' => ['required', 'integer', 'min:1', 'max:1440'],
            'laps' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]), ['laps' => (int) $request->input('laps', 0)]);
    }
}
