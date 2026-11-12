@php
    $nav = [
        ['Overview', 'admin.dashboard', 'dashboard', ['admin.dashboard']],
        ['Users', 'admin.users.index', 'users', ['admin.users.*']],
        ['Lessons', 'admin.lessons.index', 'academy', ['admin.lessons.*']],
        ['Lesson categories', 'admin.categories.index', 'tag', ['admin.categories.*']],
        ['Techniques', 'admin.techniques.index', 'techniques', ['admin.techniques.*']],
        ['Training programs', 'admin.programs.index', 'programs', ['admin.programs.*']],
        ['Quiz questions', 'admin.quiz-questions.index', 'quiz', ['admin.quiz-questions.*']],
        ['Challenges', 'admin.challenges.index', 'challenges', ['admin.challenges.*']],
        ['Safety guides', 'admin.safety-guides.index', 'shield', ['admin.safety-guides.*']],
        ['Equipment guides', 'admin.equipment.index', 'equipment', ['admin.equipment.*']],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>@yield('title', 'Admin') | AquaFlow Admin</title>
</head>
<body class="min-h-screen" x-data="{ open: false }">
    @include('partials.flash')
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-navy/60 lg:hidden" @click="open = false"></div>
    <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-deep transition-transform duration-200 lg:w-64 lg:translate-x-0" :class="open ? 'translate-x-0' : '-translate-x-full'" aria-label="Admin sidebar">
        <div class="flex h-16 items-center justify-between px-5">
            <a href="{{ route('admin.dashboard') }}"><x-logo/></a>
            <button type="button" class="text-white/70 lg:hidden" @click="open = false" aria-label="Close menu"><x-icon name="x"/></button>
        </div>
        <p class="px-6 pb-2 pt-3 text-xs font-semibold text-aqua">Administration</p>
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 pb-4" aria-label="Admin">
            @foreach($nav as [$label, $route, $icon, $patterns])
                <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs(...$patterns) ? 'nav-link-active' : '' }}"><x-icon :name="$icon" class="h-5 w-5"/> {{ $label }}</a>
            @endforeach
        </nav>
        <div class="border-t border-white/10 p-3">
            <a href="{{ route('dashboard') }}" class="nav-link"><x-icon name="chevron-left" class="h-5 w-5"/> Back to site</a>
        </div>
    </aside>
    <div class="lg:pl-64">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/85 px-4 backdrop-blur sm:px-6">
            <button type="button" class="grid h-10 w-10 place-items-center rounded-lg text-navy hover:bg-slate-100 lg:hidden" @click="open = true" aria-label="Open menu"><x-icon name="menu"/></button>
            <p class="text-sm font-semibold text-navy">@yield('title', 'Admin')</p>
            <div class="ml-auto flex items-center gap-3">
                <span class="hidden text-sm text-slate-500 sm:block">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-outline btn-sm"><x-icon name="logout" class="h-4 w-4"/> Log out</button></form>
            </div>
        </header>
        <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">@yield('content')</main>
    </div>
</body>
</html>
