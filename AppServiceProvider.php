<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination::tailwind');

        View::composer('layouts.dashboard', function ($view) {
            $user = auth()->user();

            $view->with([
                'alerts' => $user ? $user->alerts()->latest()->limit(6)->get() : collect(),
                'unreadCount' => $user ? $user->alerts()->whereNull('read_at')->count() : 0,
            ]);
        });
    }
}
