@extends('layouts.admin')
@section('title', $member->name)

@section('content')
@php $self = $member->is(auth()->user()); @endphp
<nav class="mb-4 text-sm text-slate-500"><a href="{{ route('admin.users.index') }}" class="hover:text-ocean">Users</a> <span class="mx-1">/</span> <span class="text-navy">{{ $member->name }}</span></nav>

<div class="grid gap-6 lg:grid-cols-3">
    <section class="card p-6">
        <div class="flex items-center gap-4">
            @if($member->avatarUrl())<img src="{{ $member->avatarUrl() }}" alt="" class="h-16 w-16 rounded-full object-cover">@else<span class="grid h-16 w-16 place-items-center rounded-full bg-gradient-to-br from-aqua to-ocean text-xl font-bold text-white">{{ $member->initials() }}</span>@endif
            <div><h1 class="text-xl">{{ $member->name }}</h1><p class="text-sm text-slate-500">{{ $member->email }}</p></div>
        </div>
        <dl class="mt-5 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Level</dt><dd class="font-semibold text-navy">{{ $member->levelLabel() }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Role</dt><dd class="font-semibold text-navy">{{ $member->role?->label ?? 'None' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Status</dt><dd class="font-semibold {{ $member->is_active ? 'text-emerald-600' : 'text-red-600' }}">{{ $member->is_active ? 'Active' : 'Deactivated' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Joined</dt><dd class="font-semibold text-navy">{{ $member->created_at->format('d M Y') }}</dd></div>
        </dl>

        @if($self)
            <p class="mt-6 rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">You cannot change your own role or status.</p>
        @else
            <form method="POST" action="{{ route('admin.users.update', $member) }}" class="mt-6 space-y-3">
                @csrf @method('PATCH')
                <label for="role_id" class="label">Role</label>
                <select id="role_id" name="role_id" class="input">@foreach($roles as $role)<option value="{{ $role->id }}" @selected($member->role_id === $role->id)>{{ $role->label }}</option>@endforeach</select>
                <button class="btn btn-primary w-full">Update role</button>
            </form>
            <form method="POST" action="{{ route('admin.users.update', $member) }}" class="mt-3" data-confirm="{{ $member->is_active ? 'Deactivate this account? The user will not be able to log in.' : 'Reactivate this account?' }}">
                @csrf @method('PATCH')
                <input type="hidden" name="is_active" value="{{ $member->is_active ? 0 : 1 }}">
                <button class="btn {{ $member->is_active ? 'btn-danger' : 'btn-outline' }} w-full">{{ $member->is_active ? 'Deactivate account' : 'Activate account' }}</button>
            </form>
        @endif
    </section>

    <div class="space-y-6 lg:col-span-2">
        <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-5">
            <x-stat-card label="Lessons" :value="$stats['lessons']" icon="academy"/>
            <x-stat-card label="Sessions" :value="$stats['sessions']" icon="tracker"/>
            <x-stat-card label="Distance" :value="number_format($stats['distance']).' m'" icon="droplet"/>
            <x-stat-card label="Quizzes" :value="$stats['quizzes']" icon="quiz"/>
            <x-stat-card label="Points" :value="number_format($stats['points'])" icon="award"/>
        </div>
        <section class="card p-6">
            <h2 class="text-lg">Badges</h2>
            <div class="mt-3 flex flex-wrap gap-2">@forelse($achievements as $badge)<span class="badge bg-mist text-ocean">{{ $badge->name }}</span>@empty<p class="text-sm text-slate-500">No badges earned yet.</p>@endforelse</div>
        </section>
        <section class="card p-6">
            <h2 class="text-lg">Learning history</h2>
            <ul class="mt-3 divide-y divide-slate-100">
                @forelse($history as $row)
                    <li class="flex items-center justify-between py-2.5 text-sm"><span class="font-medium text-navy">{{ $row->lesson->title }}</span><span class="text-slate-500">{{ $row->completed_at ? 'Completed '.$row->completed_at->format('d M Y') : 'In progress' }}</span></li>
                @empty
                    <li class="py-4 text-sm text-slate-500">No lessons started.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection
