<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ChecksOwnership;
use App\Models\TrainingSchedule;
use App\Support\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    use ChecksOwnership;

    public function index(Request $request)
    {
        $user = $request->user();
        $month = preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $request->query('month'))
            ? Carbon::parse($request->query('month').'-01')
            : now()->startOfMonth();

        $start = $month->copy()->startOfWeek();
        $end = $month->copy()->endOfMonth()->endOfWeek();

        $events = $user->schedules()->whereBetween('scheduled_on', [$start->toDateString(), $end->toDateString()])
            ->orderBy('start_time')->get()->groupBy(fn ($s) => $s->scheduled_on->toDateString());

        $days = [];
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days[] = [
                'date' => $day->copy(),
                'in_month' => $day->isSameMonth($month),
                'items' => $events[$day->toDateString()] ?? collect(),
            ];
        }

        return view('schedule.index', [
            'weeks' => array_chunk($days, 7),
            'month' => $month,
            'previous' => $month->copy()->subMonth()->format('Y-m'),
            'next' => $month->copy()->addMonth()->format('Y-m'),
            'upcoming' => $user->schedules()->whereNull('completed_at')->whereDate('scheduled_on', '>=', today())->orderBy('scheduled_on')->orderBy('start_time')->get(),
            'overdue' => $user->schedules()->whereNull('completed_at')->whereDate('scheduled_on', '<', today())->orderByDesc('scheduled_on')->limit(10)->get(),
            'completed' => $user->schedules()->whereNotNull('completed_at')->orderByDesc('scheduled_on')->limit(10)->get(),
            'activities' => Level::ACTIVITIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->schedules()->create($this->validated($request, true));

        return back()->with('success', 'Training session scheduled.');
    }

    public function update(Request $request, TrainingSchedule $schedule): RedirectResponse
    {
        $this->owned($schedule)->update($this->validated($request, false));

        return back()->with('success', 'Schedule updated.');
    }

    public function complete(TrainingSchedule $schedule): RedirectResponse
    {
        $this->owned($schedule)->update(['completed_at' => $schedule->completed_at ? null : now()]);

        return back()->with('success', $schedule->completed_at ? 'Session marked as completed.' : 'Session reopened.');
    }

    public function destroy(TrainingSchedule $schedule): RedirectResponse
    {
        $this->owned($schedule)->delete();

        return back()->with('success', 'Session removed from your schedule.');
    }

    private function validated(Request $request, bool $future): array
    {
        return $request->validate([
            'activity' => ['required', Rule::in(array_keys(Level::ACTIVITIES))],
            'scheduled_on' => ['required', 'date', $future ? 'after_or_equal:today' : 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);
    }
}
