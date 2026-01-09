<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubSetting extends Model
{
    protected $fillable = [
        'club_id',
        'app_name',
        'app_short_name',
        'app_icon',
        'app_theme_color',
        'app_background_color',
        'timezone',
        'language',
        'push_notifications',
        'public_registration',
    ];

    protected $casts = [
        'push_notifications' => 'boolean',
        'public_registration' => 'boolean',
    ];

    /**
     * Le club associé à ces paramètres
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Liste des timezones disponibles
     */
    public static function getTimezones(): array
    {
        return [
            'Europe/Paris' => 'Paris (UTC+1)',
            'Europe/London' => 'Londres (UTC)',
            'Europe/Brussels' => 'Bruxelles (UTC+1)',
            'Europe/Zurich' => 'Zurich (UTC+1)',
            'America/New_York' => 'New York (UTC-5)',
            'America/Los_Angeles' => 'Los Angeles (UTC-8)',
            'Africa/Casablanca' => 'Casablanca (UTC)',
            'Africa/Tunis' => 'Tunis (UTC+1)',
        ];
    }

    /**
     * Liste des langues disponibles
     */
    public static function getLanguages(): array
    {
        return [
            'fr' => 'Français',
            'en' => 'English',
            'es' => 'Español',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'ar' => 'العربية',
        ];
    }
}
