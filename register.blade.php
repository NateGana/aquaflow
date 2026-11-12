@extends('layouts.auth')
@section('title', 'Create account')

@section('content')
<h1 class="text-3xl font-extrabold">Create your account</h1>
<p class="mt-2 text-slate-500">Free to join. Pick the level that fits you best.</p>

<form method="POST" action="{{ route('register.store') }}" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="name" class="label">Full name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" class="input" required autofocus autocomplete="name">
    </div>
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" class="input" required autocomplete="email">
    </div>
    <div>
        <label for="swimming_level" class="label">Swimming experience level</label>
        <select id="swimming_level" name="swimming_level" class="input" required>
            @foreach(\App\Models\User::LEVELS as $key => $label)
                <option value="{{ $key }}" @selected(old('swimming_level', 'complete_beginner') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="password" class="label">Password</label>
            <input id="password" name="password" type="password" class="input" required autocomplete="new-password">
        </div>
        <div>
            <label for="password_confirmation" class="label">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="input" required autocomplete="new-password">
        </div>
    </div>
    <p class="text-xs text-slate-500">At least 8 characters, with letters and numbers.</p>
    <button class="btn btn-primary w-full py-3">Create account</button>
</form>
<p class="mt-6 text-center text-sm text-slate-500">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-ocean hover:underline">Log in</a></p>
@endsection
