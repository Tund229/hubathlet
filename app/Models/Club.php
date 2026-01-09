<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Club extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'primary_color',
        'secondary_color',
        'accent_color',
        'email',
        'phone',
        'city',
        'country',
        'is_active',
        'plan',
        'trial_ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'trial_ends_at' => 'date',
    ];

    /**
     * Les paramètres du club
     */
    public function settings(): HasOne
    {
        return $this->hasOne(ClubSetting::class);
    }

    /**
     * Les séances/entraînements du club
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }

    /**
     * Séances à venir
     */
    public function upcomingTrainings(): HasMany
    {
        return $this->trainings()->upcoming();
    }

    /**
     * Séances passées
     */
    public function pastTrainings(): HasMany
    {
        return $this->trainings()->past();
    }

    /**
     * Tous les membres du club (utilisateurs avec leurs rôles)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_user')
            ->withPivot(['role_id', 'jersey_number', 'position', 'license_number', 'joined_at', 'license_expires_at', 'status', 'notes'])
            ->withTimestamps();
    }

    /**
     * Membres actifs du club
     */
    public function activeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('status', 'active');
    }

    /**
     * Membres en attente de validation
     */
    public function pendingMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('status', 'pending');
    }

    /**
     * Le propriétaire du club
     */
    public function owner()
    {
        $ownerRole = Role::findBySlug(Role::OWNER);
        return $this->members()->wherePivot('role_id', $ownerRole?->id)->first();
    }

    /**
     * Les coaches du club
     */
    public function coaches(): BelongsToMany
    {
        $coachRole = Role::findBySlug(Role::COACH);
        return $this->members()->wherePivot('role_id', $coachRole?->id);
    }

    /**
     * Les joueurs du club
     */
    public function players(): BelongsToMany
    {
        $playerRole = Role::findBySlug(Role::PLAYER);
        return $this->members()->wherePivot('role_id', $playerRole?->id);
    }

    /**
     * Le staff du club (owner, admin, coach, moderator)
     */
    public function staff(): BelongsToMany
    {
        $staffRoles = Role::whereIn('slug', [Role::OWNER, Role::ADMIN, Role::COACH, Role::MODERATOR])->pluck('id');
        return $this->members()->wherePivotIn('role_id', $staffRoles);
    }

    /**
     * Compte les membres par rôle
     */
    public function getMemberCountByRole(): array
    {
        return $this->members()
            ->selectRaw('roles.slug, COUNT(*) as count')
            ->join('roles', 'club_user.role_id', '=', 'roles.id')
            ->groupBy('roles.slug')
            ->pluck('count', 'slug')
            ->toArray();
    }

    /**
     * Génère le slug à partir du nom
     */
    public static function generateSlug(string $name): string
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = static::where('slug', 'like', $slug . '%')->count();
        
        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }
}
