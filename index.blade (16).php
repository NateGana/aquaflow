@extends('layouts.dashboard')
@section('title', 'My Schedule')

@section('content')
<div x-data="{ edit: null }">
    <x-page-header title="My training schedule" subtitle="Plan your swims, see them on the calendar and mark them done."/>

    <div class="grid gap-6 xl:grid-cols-3">
        <section class="card p-6 xl:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg">{{ $month->format('F Y') }}</h2>
                <div class="flex items-center gap-1">
                    <a href="{{ route('schedule.index', ['month' => $previous]) }}" class="grid h-9 w-9 place-items-center rounded-lg hover:bg-slate-100" aria-label="Previous month"><x-icon name="chevron-left"/></a>
                    <a href="{{ route('schedule.index') }}" class="btn btn-outline btn-sm">Today</a>
                    <a href="{{ route('schedule.index', ['month' => $next]) }}" class="grid h-9 w-9 place-items-center rounded-lg hover:bg-slate-100" aria-label="Next month"><x-icon name="chevron-right"/></a>
                </div>
            </div>
            <div class="mt-4 overflow-x-auto">
                <div class="min-w-[560px]">
                    <div class="grid grid-cols-7 gap-px text-center text-xs font-semibold text-slate-400">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $name)<div class="py-2">{{ $name }}</div>@endforeach
                    </div>
                    <div class="grid grid-cols-7 gap-px overflow-hidden rounded-xl bg-slate-200">
                        @foreach($weeks as $week)
                            @foreach($week as $day)
                                <div class="min-h-24 bg-white p-1.5 {{ $day['in_month'] ? '' : 'bg-slate-50 text-slate-300' }}">
                                    <span class="inline-grid h-6 w-6 place-items-center rounded-full text-xs font-semibold {{ $day['date']->isToday() ? 'bg-ocean text-white' : '' }}">{{ $day['date']->format('j') }}</span>
                                    @foreach($day['items'] as $item)
                                        <button type="button" class="mt-1 block w-full truncate rounded px-1.5 py-0.5 text-left text-[11px] font-medium {{ $item->completed_at ? 'bg-emerald-100 text-emerald-700 line-through' : 'bg-mist text-ocean' }}"
                                                @click="edit = @js(['id' => $item->id, 'activity' => $item->activity, 'scheduled_on' => $item->scheduled_on->toDateString(), 'start_time' => $item->timeLabel(), 'notes' => $item->notes])">{{ $item->timeLabel() }} {{ $item->activity }}</button>
                                    @endforeach
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="card p-6">
            <h2 class="text-lg">Add training session</h2>
            <form method="POST" action="{{ route('schedule.store') }}" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="label" for="activity">Swimming activity</label>
                    <select id="activity" name="activity" class="input" required>@foreach($activities as $key => $label)<option value="{{ $key }}" @selected(old('activity') === $key)>{{ $label }}</option>@endforeach</select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label" for="scheduled_on">Date</label><input id="scheduled_on" type="date" name="scheduled_on" min="{{ today()->toDateString() }}" value="{{ old('scheduled_on', today()->toDateString()) }}" class="input" required></div>
                    <div><label class="label" for="start_time">Start time</label><input id="start_time" type="time" name="start_time" value="{{ old('start_time', '07:00') }}" class="input" required></div>
                </div>
                <div><label class="label" for="notes">Notes</label><textarea id="notes" name="notes" rows="2" maxlength="500" class="input">{{ old('notes') }}</textarea></div>
                <button class="btn btn-primary w-full"><x-icon name="plus" class="h-4 w-4"/> Add to schedule</button>
            </form>
        </section>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        @foreach([['Upcoming sessions', $upcoming, 'No upcoming sessions.'], ['Missed or overdue', $overdue, 'Nothing overdue.'], ['Completed sessions', $completed, 'No completed sessions yet.']] as [$heading, $list, $empty])
            <section class="card p-6">
                <h2 class="text-lg">{{ $heading }}</h2>
                <ul class="mt-4 divide-y divide-slate-100">
                    @forelse($list as $session)
                        <li class="py-3">
                            <p class="text-sm font-semibold text-navy">{{ $session->activity }}</p>
                            <p class="text-xs text-slate-500">{{ $session->scheduled_on->format('D, d M') }} at {{ $session->timeLabel() }}</p>
                            @if($session->notes)<p class="mt-1 text-xs text-slate-500">{{ $session->notes }}</p>@endif
                            <div class="mt-2 flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('schedule.complete', $session) }}">@csrf @method('PATCH')<button class="btn btn-outline btn-sm">{{ $session->completed_at ? 'Reopen' : 'Mark completed' }}</button></form>
                                <button type="button" class="btn btn-outline btn-sm" @click="edit = @js(['id' => $session->id, 'activity' => $session->activity, 'scheduled_on' => $session->scheduled_on->toDateString(), 'start_time' => $session->timeLabel(), 'notes' => $session->notes])">Edit</button>
                                <form method="POST" action="{{ route('schedule.destroy', $session) }}" data-confirm="Remove this session from your schedule?">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" aria-label="Delete session"><x-icon name="trash" class="h-3.5 w-3.5"/></button></form>
                            </div>
                        </li>
                    @empty
                        <li class="py-6 text-center text-sm text-slate-500">{{ $empty }}</li>
                    @endforelse
                </ul>
            </section>
        @endforeach
    </div>

    <div x-show="edit" x-cloak x-transition.opacity class="fixed inset-0 z-[60] grid place-items-center bg-navy/60 p-4" @keydown.escape.window="edit = null">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" @click.outside="edit = null" role="dialog" aria-modal="true" aria-label="Edit session">
            <div class="flex items-center justify-between"><h2 class="text-lg">Edit session</h2><button type="button" @click="edit = null" aria-label="Close"><x-icon name="x"/></button></div>
            <form method="POST" :action="'{{ url('/schedule') }}/' + (edit ? edit.id : '')" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <template x-if="edit"><div class="space-y-4">
                <div>
                    <label class="label">Swimming activity</label>
                    <select name="activity" class="input" required x-model="edit.activity">@foreach($activities as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label">Date</label><input type="date" name="scheduled_on" class="input" required x-model="edit.scheduled_on"></div>
                    <div><label class="label">Start time</label><input type="time" name="start_time" class="input" required x-model="edit.start_time"></div>
                </div>
                <div><label class="label">Notes</label><textarea name="notes" rows="2" maxlength="500" class="input" x-model="edit.notes"></textarea></div>
                </div></template>
                <div class="flex gap-2"><button class="btn btn-primary flex-1">Save changes</button><button type="button" class="btn btn-outline" @click="edit = null">Cancel</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
