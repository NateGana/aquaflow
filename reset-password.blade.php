@extends('layouts.auth')
@section('title', 'Choose a new password')

@section('content')
<h1 class="text-3xl font-extrabold">Choose a new password</h1>
<p class="mt-2 text-slate-500">Use at least 8 characters with letters and numbers.</p>

<form method="POST" action="{{ route('password.update') }}" class="mt-8 space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" class="input" required autocomplete="email">
    </div>
    <div>
        <label for="password" class="label">New password</label>
        <input id="password" name="password" type="password" class="input" required autocomplete="new-password">
    </div>
    <div>
        <label for="password_confirmation" class="label">Confirm new password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="input" required autocomplete="new-password">
    </div>
    <button class="btn btn-primary w-full py-3">Reset password</button>
</form>
@endsection
