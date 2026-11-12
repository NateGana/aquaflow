@extends('layouts.auth')
@section('title', 'Log in')

@section('content')
<h1 class="text-3xl font-extrabold">Welcome back</h1>
<p class="mt-2 text-slate-500">Log in to continue your swimming journey.</p>

<form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
    @csrf
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" class="input" required autofocus autocomplete="email">
    </div>
    <div>
        <div class="flex items-center justify-between">
            <label for="password" class="label">Password</label>
            <a href="{{ route('password.request') }}" class="mb-1.5 text-xs font-semibold text-ocean hover:underline">Forgot password?</a>
        </div>
        <input id="password" name="password" type="password" class="input" required autocomplete="current-password">
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" class="rounded border-slate-300 text-ocean focus:ring-aqua"> Remember me</label>
    <button class="btn btn-primary w-full py-3">Log in</button>
</form>
<p class="mt-6 text-center text-sm text-slate-500">New to AquaFlow? <a href="{{ route('register') }}" class="font-semibold text-ocean hover:underline">Create an account</a></p>
@endsection
