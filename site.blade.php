<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>@yield('title', 'AquaFlow - Learn. Train. Swim Beyond Limits.')</title>
</head>
<body class="min-h-screen bg-white">
    @include('partials.flash')
    <header x-data="{ open: false }" class="absolute inset-x-0 top-0 z-40">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" aria-label="AquaFlow home"><x-logo/></a>
            <nav class="hidden items-center gap-8 text-sm font-medium text-white/85 lg:flex" aria-label="Main">
                <a href="{{ route('lessons.index') }}" class="hover:text-aqua">Swim Academy</a>
                <a href="{{ route('techniques.index') }}" class="hover:text-aqua">Techniques</a>
                <a href="{{ route('programs.index') }}" class="hover:text-aqua">Programs</a>
                <a href="{{ route('safety.index') }}" class="hover:text-aqua">Water Safety</a>
                <a href="{{ route('equipment.index') }}" class="hover:text-aqua">Equipment</a>
            </nav>
            <div class="hidden items-center gap-3 lg:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-aqua btn-sm">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-white hover:text-aqua">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-aqua btn-sm">Get started</a>
                @endauth
            </div>
            <button type="button" class="grid h-10 w-10 place-items-center rounded-lg text-white lg:hidden" @click="open = !open" :aria-expanded="open" aria-label="Toggle menu">
                <x-icon name="menu" x-show="!open"/><x-icon name="x" x-show="open" x-cloak/>
            </button>
        </div>
        <div x-show="open" x-cloak x-transition class="mx-4 rounded-2xl bg-navy/95 p-4 shadow-xl backdrop-blur lg:hidden">
            <nav class="flex flex-col gap-1 text-sm font-medium text-white" aria-label="Mobile">
                @foreach([['Swim Academy', 'lessons.index'], ['Techniques', 'techniques.index'], ['Programs', 'programs.index'], ['Water Safety', 'safety.index'], ['Equipment', 'equipment.index']] as [$label, $route])
                    <a href="{{ route($route) }}" class="rounded-lg px-3 py-2.5 hover:bg-white/10">{{ $label }}</a>
                @endforeach
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-aqua mt-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2.5 hover:bg-white/10">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-aqua mt-2">Get started</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>@yield('content')</main>

    <footer class="bg-navy text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <x-logo/>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-400">AquaFlow is a swimming learning and training hub: structured lessons, technique guides, training programs and progress tracking, with safety at the center of everything.</p>
                <p class="mt-4 text-sm font-semibold text-aqua">Learn. Train. Swim Beyond Limits.</p>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Explore</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a class="hover:text-aqua" href="{{ route('lessons.index') }}">Swim Academy</a></li>
                    <li><a class="hover:text-aqua" href="{{ route('techniques.index') }}">Swimming Techniques</a></li>
                    <li><a class="hover:text-aqua" href="{{ route('programs.index') }}">Training Programs</a></li>
                    <li><a class="hover:text-aqua" href="{{ route('safety.index') }}">Water Safety</a></li>
                    <li><a class="hover:text-aqua" href="{{ route('equipment.index') }}">Equipment Guide</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-bold text-white">Contact</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li>hello@aquaflow.example</li>
                    <li>+1 (555) 010-2030</li>
                    <li>100 Harbor Lane, Bluewater</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} AquaFlow. Online tutorials do not replace certified swimming instruction, lifeguard supervision or hands-on CPR training.
        </div>
    </footer>
</body>
</html>
