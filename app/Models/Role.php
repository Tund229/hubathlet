<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'level',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * Constantes pour les slugs de rôles
     */
    const OWNER = 'owner';
    const ADMIN = 'admin';
    const COACH = 'coach';
    const MODERATOR = 'moderator';
    const PLAYER = 'player';
    const PARENT = 'parent';
    const GUEST = 'guest';

    /**
     * Les utilisateurs ayant ce rôle
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'club_user')
            ->withPivot(['club_id', 'jersey_number', 'position', 'license_number', 'joined_at', 'status', 'notes'])
            ->withTimestamps();
    }

    /**
     * Vérifie si c'est un rôle d'administration (owner, admin, moderator)
     */
    public function isAdminRole(): bool
    {
        return in_array($this->slug, [self::OWNER, self::ADMIN, self::MODERATOR]);
    }

    /**
     * Vérifie si c'est un rôle de staff (owner, admin, coach, moderator)
     */
    public function isStaffRole(): bool
    {
        return in_array($this->slug, [self::OWNER, self::ADMIN, self::COACH, self::MODERATOR]);
    }

    /**
     * Récupère un rôle par son slug
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
