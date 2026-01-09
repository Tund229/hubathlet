<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;

class RedirectToDashboard
{
    /**
     * Redirige vers le dashboard approprié selon le rôle de l'utilisateur
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $club = $user->clubs()->first();
        
        if (!$club) {
            // Rediriger vers une page de création de club ou d'attente
            return $next($request);
        }

        $role = $user->roleInClub($club);
        
        if (!$role) {
            return $next($request);
        }

        // Si on est sur la route dashboard et que l'utilisateur est coach (pur), 
        // rediriger vers le coach dashboard
        if ($request->routeIs('dashboard') && $role->slug === Role::COACH) {
            return redirect()->route('coach.dashboard');
        }

        // Si on est sur la route dashboard et que l'utilisateur est joueur,
        // rediriger vers le player dashboard
        if ($request->routeIs('dashboard') && $role->slug === Role::PLAYER) {
            return redirect()->route('player.dashboard');
        }

        // Si on est sur la route dashboard et que l'utilisateur est parent,
        // rediriger vers le parent dashboard
        if ($request->routeIs('dashboard') && $role->slug === Role::PARENT) {
            return redirect()->route('parent.dashboard');
        }

        return $next($request);
    }
}

