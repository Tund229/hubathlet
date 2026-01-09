<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class LayoutHelper
{
    /**
     * Détermine le layout approprié en fonction du rôle de l'utilisateur
     */
    public static function getLayout(): string
    {
        if (!Auth::check()) {
            return 'layouts.app';
        }

        $user = Auth::user();
        $club = $user->clubs()->first();

        if (!$club) {
            return 'layouts.dashboard-player'; // Default pour les nouveaux utilisateurs
        }

        $role = $user->roleInClub($club);

        if (!$role) {
            return 'layouts.dashboard-player';
        }

        return match($role->slug) {
            Role::OWNER, Role::ADMIN, Role::MODERATOR => 'layouts.dashboard-admin',
            Role::COACH => 'layouts.dashboard-coach',
            Role::PLAYER => 'layouts.dashboard-player',
            Role::PARENT => 'layouts.dashboard-parent',
            default => 'layouts.dashboard-player',
        };
    }

    /**
     * Vérifie si l'utilisateur est admin (owner, admin, moderator)
     */
    public static function isAdmin(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $club = $user->clubs()->first();

        if (!$club) {
            return false;
        }

        $role = $user->roleInClub($club);

        return $role && in_array($role->slug, [Role::OWNER, Role::ADMIN, Role::MODERATOR]);
    }

    /**
     * Vérifie si l'utilisateur est staff (owner, admin, coach, moderator)
     */
    public static function isStaff(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        $club = $user->clubs()->first();

        if (!$club) {
            return false;
        }

        $role = $user->roleInClub($club);

        return $role && in_array($role->slug, [Role::OWNER, Role::ADMIN, Role::COACH, Role::MODERATOR]);
    }

    /**
     * Récupère le rôle de l'utilisateur courant
     */
    public static function getUserRole(): ?Role
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $club = $user->clubs()->first();

        if (!$club) {
            return null;
        }

        return $user->roleInClub($club);
    }

    /**
     * Retourne la route du dashboard appropriée selon le rôle
     */
    public static function getDashboardRoute(): string
    {
        $role = self::getUserRole();

        if (!$role) {
            return 'dashboard';
        }

        return match($role->slug) {
            Role::OWNER, Role::ADMIN, Role::MODERATOR => 'dashboard',
            Role::COACH => 'coach.dashboard',
            Role::PLAYER => 'player.dashboard',
            Role::PARENT => 'parent.dashboard',
            default => 'dashboard',
        };
    }
}

