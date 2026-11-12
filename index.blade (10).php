@extends('layouts.dashboard')
@section('title', 'Progress Tracker')

@section('content')
@php
    $make = fn ($key, $label, $type = 'bar') => [
        'type' => $type,
        'labels' => $charts[$key]['labels'],
        'datasets' => [['label' => $label, 'data' => $charts[$key]['values']]],
        'yTitle' => 'Meters',
    ];
    $weekly = $make('weekly', 'Distance per week (m)');
    $monthly = $make('monthly', 'Distance per month (m)');
    $progression = $make('progression', 'Distance per session (m)', 'line');
@endphp

<div x-data="{ edit: null }">
    <x-page-header title="Progress tracker" subtitle="Log every swim and watch your distance, consistency and personal bests improve."/>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total distance" :value="number_format($stats['distance']).' m'" icon="droplet"/>
        <x-stat-card label="Total sessions" :value="$stats['sessions']" icon="tracker"/>
        <x-stat-card label="Average duration" :value="$stats['average'].' min'" icon="clock"/>
        <x-stat-card label="Longest distance" :value="number_format($stats['longest']).' m'" icon="flag"/>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <section class="card p-6 lg:col-span-1">
            <h2 class="text-lg">Log a swim</h2>
            <form method="POST" action="{{ route('records.store') }}" class="mt-4 space-y-4">
                @csrf
                @include('records._fields', ['model' => false])
                <button class="btn btn-primary w-full"><x-icon name="plus" class="h-4 w-4"/> Save session</button>
            </form>
        </section>

        <div class="space-y-6 lg:col-span-2">
            <section class="card p-6">
                <h2 class="text-lg">Weekly swimming activity</h2>
                <div class="mt-4 h-56"><canvas x-data="chart(@js($weekly))"></canvas></div>
            </section>
            <div class="grid gap-6 xl:grid-cols-2">
                <section class="card p-6">
                    <h2 class="text-lg">Monthly activity</h2>
                    <div class="mt-4 h-48"><canvas x-data="chart(@js($monthly))"></canvas></div>
                </section>
                <section class="card p-6">
                    <h2 class="text-lg">Distance progression</h2>
                    <div class="mt-4 h-48"><canvas x-data="chart(@js($progression))"></canvas></div>
                </section>
            </div>
        </div>
    </div>

    <section class="mt-6 card p-6">
        <h2 class="text-lg">Personal best records</h2>
        @if($bests->isEmpty())
            <p class="mt-3 text-sm text-slate-500">Your personal bests will appear after your first logged session.</p>
        @else
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                @foreach($bests as $best)
                    <div class="rounded-xl bg-mist/60 p-4"><p class="text-xs font-semibold text-ocean">{{ $best['label'] }}</p><p class="mt-1 text-xl font-extrabold text-navy">{{ $best['value'] }}</p><p class="text-xs text-slate-500">{{ $best['date']->format('d M Y') }}</p></div>
                @endforeach
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($styleBests as $style => $distance)<span class="badge bg-slate-100 text-slate-700">Best {{ $styles[$style] ?? ucfirst($style) }}: {{ number_format($distance) }} m</span>@endforeach
            </div>
        @endif
    </section>

    <section class="mt-6 card overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4"><h2 class="text-lg">Session history</h2></div>
        @if($history->isEmpty())
            <p class="px-6 py-10 text-center text-sm text-slate-500">No sessions logged yet. Use the form above to record your first swim.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500">
                        <tr><th class="px-6 py-3 font-semibold">Date</th><th class="px-3 py-3 font-semibold">Style</th><th class="px-3 py-3 font-semibold">Distance</th><th class="px-3 py-3 font-semibold">Duration</th><th class="px-3 py-3 font-semibold">Laps</th><th class="px-3 py-3 font-semibold">Notes</th><th class="px-6 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($history as $record)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-3 font-medium text-navy">{{ $record->swam_on->format('d M Y') }}</td>
                                <td class="px-3 py-3">{{ $styles[$record->style] ?? ucfirst($record->style) }}</td>
                                <td class="px-3 py-3">{{ number_format($record->distance_m) }} m</td>
                                <td class="px-3 py-3">{{ $record->duration_min }} min</td>
                                <td class="px-3 py-3">{{ $record->laps }}</td>
                                <td class="max-w-xs truncate px-3 py-3 text-slate-500">{{ $record->notes }}</td>
                                <td class="whitespace-nowrap px-6 py-3 text-right">
                                    <button type="button" class="btn btn-outline btn-sm" @click="edit = @js(['id' => $record->id, 'swam_on' => $record->swam_on->toDateString(), 'style' => $record->style, 'distance_m' => $record->distance_m, 'duration_min' => $record->duration_min, 'laps' => $record->laps, 'notes' => $record->notes])"><x-icon name="edit" class="h-3.5 w-3.5"/> Edit</button>
                                    <form method="POST" action="{{ route('records.destroy', $record) }}" class="inline" data-confirm="Delete this swimming session?">@csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" aria-label="Delete session"><x-icon name="trash" class="h-3.5 w-3.5"/></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-6 py-4">{{ $history->links() }}</div>
        @endif
    </section>

    <div x-show="edit" x-cloak x-transition.opacity class="fixed inset-0 z-[60] grid place-items-center bg-navy/60 p-4" @keydown.escape.window="edit = null">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-6 shadow-2xl" @click.outside="edit = null" role="dialog" aria-modal="true" aria-label="Edit session">
            <div class="flex items-center justify-between"><h2 class="text-lg">Edit session</h2><button type="button" @click="edit = null" aria-label="Close"><x-icon name="x"/></button></div>
            <form method="POST" :action="'{{ url('/tracker') }}/' + (edit ? edit.id : '')" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <template x-if="edit">@include('records._fields', ['model' => true])</template>
                <div class="flex gap-2"><button class="btn btn-primary flex-1">Save changes</button><button type="button" class="btn btn-outline" @click="edit = null">Cancel</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
