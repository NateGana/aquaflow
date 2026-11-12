<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>@yield('title') | AquaFlow</title>
</head>
<body class="min-h-screen bg-white">
    @include('partials.flash')
    <div class="grid min-h-screen lg:grid-cols-2">
        <aside class="relative hidden overflow-hidden bg-navy p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <x-media :seed="1" label="Swimming pool" icon="droplet" class="absolute inset-0 opacity-60"/>
            <div class="absolute inset-0 bg-gradient-to-t from-navy via-navy/60 to-transparent"></div>
            <a href="{{ route('home') }}" class="relative"><x-logo/></a>
            <div class="relative max-w-md">
                <p class="text-3xl font-extrabold leading-tight text-white">Make every stroke count.</p>
                <p class="mt-3 text-white/75">Lessons, training programs and progress tracking for every level, from your first float to your fastest lap.</p>
            </div>
        </aside>
        <section class="flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 inline-block lg:hidden"><x-logo :dark="true"/></a>
                @yield('content')
            </div>
        </section>
    </div>
</body>
</html>
