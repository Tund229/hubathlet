<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    /**
     * Les clubs auxquels l'utilisateur appartient
     */
    public function clubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'club_user')
            ->withPivot(['role_id', 'jersey_number', 'position', 'license_number', 'joined_at', 'license_expires_at', 'status', 'notes'])
            ->withTimestamps();
    }

    /**
     * Récupère le rôle de l'utilisateur dans un club donné
     */
    public function roleInClub(Club $club): ?Role
    {
        $membership = $this->clubs()->where('club_id', $club->id)->first();
        
        if (!$membership) {
            return null;
        }

        return Role::find($membership->pivot->role_id);
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique dans un club
     */
    public function hasRoleInClub(Club $club, string $roleSlug): bool
    {
        $role = $this->roleInClub($club);
        return $role && $role->slug === $roleSlug;
    }

    /**
     * Vérifie si l'utilisateur est propriétaire d'un club
     */
    public function isOwnerOf(Club $club): bool
    {
        return $this->hasRoleInClub($club, Role::OWNER);
    }

    /**
     * Vérifie si l'utilisateur est admin d'un club
     */
    public function isAdminOf(Club $club): bool
    {
        $role = $this->roleInClub($club);
        return $role && in_array($role->slug, [Role::OWNER, Role::ADMIN]);
    }

    /**
     * Vérifie si l'utilisateur fait partie du staff d'un club
     */
    public function isStaffOf(Club $club): bool
    {
        $role = $this->roleInClub($club);
        return $role && $role->isStaffRole();
    }

    /**
     * Vérifie si l'utilisateur est membre actif d'un club
     */
    public function isActiveMemberOf(Club $club): bool
    {
        $membership = $this->clubs()->where('club_id', $club->id)->first();
        return $membership && $membership->pivot->status === 'active';
    }

    /**
     * Récupère le club principal de l'utilisateur (premier club)
     */
    public function getPrimaryClub(): ?Club
    {
        return $this->clubs()->wherePivot('status', 'active')->first();
    }

    /**
     * Les clubs dont l'utilisateur est propriétaire
     */
    public function ownedClubs(): BelongsToMany
    {
        $ownerRole = Role::findBySlug(Role::OWNER);
        return $this->clubs()->wherePivot('role_id', $ownerRole?->id);
    }

    /**
     * Récupère les initiales de l'utilisateur
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return $initials;
    }
}
