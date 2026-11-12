@php
    $nav = [
        ['Home', auth()->check() ? 'dashboard' : 'home', 'home', ['dashboard', 'home']],
        ['Swim Academy', 'lessons.index', 'academy', ['lessons.*']],
        ['Swimming Techniques', 'techniques.index', 'techniques', ['techniques.*']],
        ['Training Programs', 'programs.index', 'programs', ['programs.*']],
        ['Progress Tracker', 'records.index', 'tracker', ['records.*']],
        ['Challenges', 'challenges.index', 'challenges', ['challenges.*']],
        ['Water Safety', 'safety.index', 'shield', ['safety.*']],
        ['Equipment Guide', 'equipment.index', 'equipment', ['equipment.*']],
        ['Swimming Quiz', 'quiz.index', 'quiz', ['quiz.*']],
        ['My Schedule', 'schedule.index', 'calendar', ['schedule.*']],
        ['My Achievements', 'achievements.index', 'award', ['achievements.*']],
        ['Settings', 'settings.index', 'settings', ['settings.*']],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <title>@yield('title', 'AquaFlow') | AquaFlow</title>
</head>
<body class="min-h-screen" x-data="{ open: false, collapsed: localStorage.getItem('aquaflow.collapsed') === '1' }"
      x-init="$watch('collapsed', value => localStorage.setItem('aquaflow.collapsed', value ? '1' : '0'))">
    @include('partials.flash')

    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-navy/60 lg:hidden" @click="open = false"></div>

    <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-navy transition-all duration-200 lg:translate-x-0"
           :class="[open ? 'translate-x-0' : '-translate-x-full', collapsed ? 'lg:w-20' : 'lg:w-64']" aria-label="Sidebar">
        <div class="flex h-16 items-center justify-between px-5">
            <a href="{{ route('home') }}" aria-label="AquaFlow home"><span :class="collapsed ? 'lg:hidden' : ''"><x-logo/></span><span class="hidden" :class="collapsed ? 'lg:inline' : ''"><x-logo :compact="true"/></span></a>
            <button type="button" class="text-white/70 lg:hidden" @click="open = false" aria-label="Close menu"><x-icon name="x"/></button>
        </div>
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4" aria-label="Primary">
            @foreach($nav as [$label, $route, $icon, $patterns])
                <a href="{{ route($route) }}" title="{{ $label }}" class="nav-link {{ request()->routeIs(...$patterns) ? 'nav-link-active' : '' }}" @if(request()->routeIs(...$patterns)) aria-current="page" @endif>
                    <x-icon :name="$icon" class="h-5 w-5 shrink-0"/>
                    <span :class="collapsed && 'lg:hidden'">{{ $label }}</span>
                </a>
            @endforeach
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.dashboard') }}" title="Admin panel" class="nav-link mt-4 border-t border-white/10 pt-4">
                    <x-icon name="dashboard" class="h-5 w-5 shrink-0"/>
                    <span :class="collapsed && 'lg:hidden'">Admin Panel</span>
                </a>
            @endif
        </nav>
        <div class="px-5 pb-5 text-xs text-slate-500" :class="collapsed && 'lg:hidden'">Learn. Train. Swim Beyond Limits.</div>
    </aside>

    <div class="transition-all duration-200" :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
        <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-slate-200 bg-white/85 px-4 backdrop-blur sm:px-6">
            <button type="button" class="grid h-10 w-10 place-items-center rounded-lg text-navy hover:bg-slate-100 lg:hidden" @click="open = true" aria-label="Open menu"><x-icon name="menu"/></button>
            <button type="button" class="hidden h-10 w-10 place-items-center rounded-lg text-navy hover:bg-slate-100 lg:grid" @click="collapsed = !collapsed" :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                <x-icon name="chevron-left" x-show="!collapsed"/><x-icon name="chevron-right" x-show="collapsed" x-cloak/>
            </button>

            <form action="{{ route('search') }}" method="GET" class="relative ml-1 flex-1 sm:max-w-md" role="search">
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"/>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search lessons, techniques, programs..." class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm focus:border-ocean focus:bg-white focus:outline-none focus:ring-2 focus:ring-aqua/30" aria-label="Search">
            </form>

            <div class="ml-auto flex items-center gap-2">
                @auth
                    <div class="relative" x-data="{ o: false }" @click.outside="o = false" @keydown.escape.window="o = false">
                        <button type="button" class="relative grid h-10 w-10 place-items-center rounded-lg text-navy hover:bg-slate-100" @click="o = !o" aria-label="Notifications" :aria-expanded="o">
                            <x-icon name="bell"/>
                            @if($unreadCount > 0)<span class="absolute right-1.5 top-1.5 grid h-4 min-w-4 place-items-center rounded-full bg-aqua px-1 text-[10px] font-bold text-navy">{{ $unreadCount }}</span>@endif
                        </button>
                        <div x-show="o" x-cloak x-transition class="absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-xl">
                            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-bold text-navy">Notifications</p>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.read') }}">@csrf<button class="text-xs font-semibold text-ocean hover:underline">Mark all read</button></form>
                                @endif
                            </div>
                            <ul class="max-h-80 divide-y divide-slate-100 overflow-y-auto">
                                @forelse($alerts as $alert)
                                    <li class="px-4 py-3 {{ $alert->read_at ? '' : 'bg-mist/50' }}">
                                        <a href="{{ $alert->link ?: '#' }}" class="block">
                                            <p class="text-sm font-semibold text-navy">{{ $alert->title }}</p>
                                            @if($alert->body)<p class="mt-0.5 text-xs text-slate-500">{{ $alert->body }}</p>@endif
                                            <p class="mt-1 text-[11px] text-slate-400">{{ $alert->created_at->diffForHumans() }}</p>
                                        </a>
                                    </li>
                                @empty
                                    <li class="px-4 py-8 text-center text-sm text-slate-500">You are all caught up.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="relative" x-data="{ o: false }" @click.outside="o = false" @keydown.escape.window="o = false">
                        <button type="button" class="flex items-center gap-2 rounded-xl p-1 pr-2 hover:bg-slate-100" @click="o = !o" :aria-expanded="o" aria-label="Profile menu">
                            @if(auth()->user()->avatarUrl())
                                <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <span class="grid h-9 w-9 place-items-center rounded-full bg-gradient-to-br from-aqua to-ocean text-sm font-bold text-white">{{ auth()->user()->initials() }}</span>
                            @endif
                            <span class="hidden text-sm font-semibold text-navy sm:block">{{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</span>
                            <x-icon name="chevron-down" class="hidden h-4 w-4 text-slate-400 sm:block"/>
                        </button>
                        <div x-show="o" x-cloak x-transition class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-100 bg-white p-2 shadow-xl">
                            <div class="px-3 py-2">
                                <p class="truncate text-sm font-bold text-navy">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-50"><x-icon name="user" class="h-4 w-4"/> Profile and settings</a>
                            <a href="{{ route('achievements.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-50"><x-icon name="award" class="h-4 w-4"/> My achievements</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm hover:bg-slate-50"><x-icon name="dashboard" class="h-4 w-4"/> Admin panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mt-1 border-t border-slate-100 pt-1">@csrf
                                <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50"><x-icon name="logout" class="h-4 w-4"/> Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign up</a>
                @endauth
            </div>
        </header>

        <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">@yield('content')</main>
    </div>
</body>
</html>
