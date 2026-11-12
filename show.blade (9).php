@extends('layouts.admin')
@section('title', $challenge->title)

@section('content')
<x-page-header :title="$challenge->title" :subtitle="$challenge->description">
    <x-slot:actions>
        <a href="{{ route('admin.challenges.edit', $challenge) }}" class="btn btn-outline">Edit challenge</a>
        <a href="{{ route('admin.challenges.index') }}" class="btn btn-outline">Back to list</a>
    </x-slot:actions>
</x-page-header>

<div class="grid gap-4 sm:grid-cols-4">
    <x-stat-card label="Target" :value="number_format($challenge->target).' '.$challenge->unit()" icon="flag"/>
    <x-stat-card label="Reward" :value="number_format($challenge->points).' pts'" icon="award"/>
    <x-stat-card label="Participants" :value="$rows->count()" icon="users"/>
    <x-stat-card label="Completed" :value="$rows->filter(fn ($row) => $row['participant']->completed_at)->count()" icon="check"/>
</div>

<section class="card mt-6 overflow-hidden">
    <div class="border-b border-slate-100 px-6 py-4"><h2 class="text-lg">Participant progress</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-6 py-3 font-semibold">Participant</th><th class="px-3 py-3 font-semibold">Joined</th><th class="w-64 px-3 py-3 font-semibold">Progress</th><th class="px-6 py-3 font-semibold">Status</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($rows as $row)
                    <tr>
                        <td class="px-6 py-3 font-medium text-navy">{{ $row['participant']->user->name }}</td>
                        <td class="px-3 py-3">{{ $row['participant']->joined_on->format('d M Y') }}</td>
                        <td class="px-3 py-3"><x-progress-bar :percent="$row['percent']" :detail="$row['value'].' / '.$challenge->target"/></td>
                        <td class="px-6 py-3"><span class="badge {{ $row['participant']->completed_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $row['participant']->completed_at ? 'Completed' : 'In progress' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-500">Nobody has joined yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
