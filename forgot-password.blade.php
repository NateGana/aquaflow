@extends('layouts.auth')
@section('title', 'Forgot password')

@section('content')
<h1 class="text-3xl font-extrabold">Reset your password</h1>
<p class="mt-2 text-slate-500">Enter your email and we will send you a reset link.</p>

<form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" class="input" required autofocus autocomplete="email">
    </div>
    <button class="btn btn-primary w-full py-3">Send reset link</button>
</form>
<p class="mt-6 text-center text-sm text-slate-500"><a href="{{ route('login') }}" class="font-semibold text-ocean hover:underline">Back to log in</a></p>
@endsection
