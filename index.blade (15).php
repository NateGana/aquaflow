@extends('layouts.dashboard')
@section('title', 'Settings')

@section('content')
<x-page-header title="Settings and profile" subtitle="Manage your details, security and learning history."/>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <section class="card p-6">
            <h2 class="text-lg">Profile information</h2>
            <form method="POST" action="{{ route('settings.profile') }}" enctype="multipart/form-data" class="mt-5 space-y-5">
                @csrf @method('PUT')
                <div class="flex items-center gap-4">
                    @if($user->avatarUrl())
                        <img src="{{ $user->avatarUrl() }}" alt="Your profile picture" class="h-16 w-16 rounded-full object-cover">
                    @else
                        <span class="grid h-16 w-16 place-items-center rounded-full bg-gradient-to-br from-aqua to-ocean text-xl font-bold text-white">{{ $user->initials() }}</span>
                    @endif
                    <div class="flex-1">
                        <label for="avatar" class="label">Profile picture</label>
                        <input id="avatar" type="file" name="avatar" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-mist file:px-3 file:py-2 file:text-sm file:font-semibold file:text-ocean">
                        <p class="mt-1 text-xs text-slate-500">JPG, PNG or WebP, up to 2 MB.</p>
                    </div>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label for="name" class="label">Full name</label><input id="name" name="name" value="{{ old('name', $user->name) }}" class="input" required></div>
                    <div><label for="email" class="label">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="input" required></div>
                </div>
                <div>
                    <label for="swimming_level" class="label">Swimming experience level</label>
                    <select id="swimming_level" name="swimming_level" class="input">@foreach(\App\Models\User::LEVELS as $key => $label)<option value="{{ $key }}" @selected(old('swimming_level', $user->swimming_level) === $key)>{{ $label }}</option>@endforeach</select>
                </div>
                <button class="btn btn-primary">Save profile</button>
            </form>
        </section>

        <section class="card p-6">
            <h2 class="text-lg">Change password</h2>
            <form method="POST" action="{{ route('settings.password') }}" class="mt-5 space-y-5">
                @csrf @method('PUT')
                <div><label for="current_password" class="label">Current password</label><input id="current_password" type="password" name="current_password" class="input" required autocomplete="current-password"></div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div><label for="new_password" class="label">New password</label><input id="new_password" type="password" name="password" class="input" required autocomplete="new-password"></div>
                    <div><label for="new_password_confirmation" class="label">Confirm new password</label><input id="new_password_confirmation" type="password" name="password_confirmation" class="input" required autocomplete="new-password"></div>
                </div>
                <button class="btn btn-primary">Update password</button>
            </form>
        </section>

        <section class="card p-6">
            <h2 class="text-lg">Learning history</h2>
            <ul class="mt-4 divide-y divide-slate-100">
                @forelse($history as $row)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <a href="{{ route('lessons.show', $row->lesson) }}" class="text-sm font-semibold text-navy hover:text-ocean">{{ $row->lesson->title }}</a>
                        <span class="badge {{ $row->completed_at ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $row->completed_at ? 'Completed '.$row->completed_at->format('d M') : 'In progress' }}</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-slate-500">No lessons started yet.</li>
                @endforelse
            </ul>
            @if($attempts->isNotEmpty())
                <h3 class="mt-6 text-base">Recent quizzes</h3>
                <ul class="mt-2 divide-y divide-slate-100">
                    @foreach($attempts as $attempt)
                        <li class="flex items-center justify-between py-2 text-sm"><span>{{ $attempt->category->name }}</span><span class="font-semibold text-navy">{{ $attempt->score }}/{{ $attempt->total_questions }}</span></li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    <aside class="card h-fit p-6">
        <h2 class="text-lg">My badges</h2>
        <p class="mt-1 text-sm text-slate-500">{{ number_format($user->points) }} points earned</p>
        <ul class="mt-4 space-y-3">
            @forelse($achievements as $badge)
                <li class="flex items-center gap-3"><span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-aqua to-ocean text-white"><x-icon :name="$badge->icon" class="h-5 w-5"/></span><span class="text-sm font-semibold text-navy">{{ $badge->name }}</span></li>
            @empty
                <li class="text-sm text-slate-500">No badges yet. Complete a lesson to earn your first.</li>
            @endforelse
        </ul>
    </aside>
</div>
@endsection
