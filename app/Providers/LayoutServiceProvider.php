<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class LayoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les variables communes à tous les layouts dashboard
        View::composer('layouts.dashboard-*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $club = $user->clubs()->first();
                $userRole = $club ? $user->roleInClub($club) : null;
                
                $view->with([
                    'currentClub' => $club,
                    'userRole' => $userRole,
                    'isAdmin' => $userRole && in_array($userRole->slug, [Role::OWNER, Role::ADMIN, Role::MODERATOR]),
                    'isStaff' => $userRole && in_array($userRole->slug, [Role::OWNER, Role::ADMIN, Role::COACH, Role::MODERATOR]),
                ]);
            }
        });

        // Composer pour les partiels de navigation
        View::composer('layouts.partials.*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $club = $user->clubs()->first();
                $userRole = $club ? $user->roleInClub($club) : null;
                
                $view->with([
                    'currentClub' => $club,
                    'userRole' => $userRole,
                ]);
            }
        });
    }
}

