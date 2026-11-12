@extends('layouts.admin')
@section('title', 'Overview')

@section('content')
<x-page-header title="Administrator dashboard" subtitle="A snapshot of AquaFlow activity."/>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    @foreach($stats as $stat)<x-stat-card :label="$stat['label']" :value="number_format($stat['value'])" :icon="$stat['icon']"/>@endforeach
</div>

<section class="card mt-8 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h2 class="text-lg">Recent registrations</h2>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-ocean hover:underline">Manage users</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-6 py-3 font-semibold">Name</th><th class="px-3 py-3 font-semibold">Email</th><th class="px-3 py-3 font-semibold">Level</th><th class="px-3 py-3 font-semibold">Role</th><th class="px-6 py-3 font-semibold">Joined</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($recent as $user)
                    <tr>
                        <td class="px-6 py-3 font-medium text-navy"><a href="{{ route('admin.users.show', $user) }}" class="hover:text-ocean">{{ $user->name }}</a></td>
                        <td class="px-3 py-3">{{ $user->email }}</td>
                        <td class="px-3 py-3">{{ $user->levelLabel() }}</td>
                        <td class="px-3 py-3">{{ $user->role?->label ?? 'None' }}</td>
                        <td class="px-6 py-3 text-slate-500">{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
