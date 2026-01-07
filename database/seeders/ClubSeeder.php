<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Club par défaut
        $club = Club::firstOrCreate(
            ['slug' => 'hubathlet'],
            [
                'name' => 'Hubathlet Club',
                'logo' => null,
                'primary_color' => '#0F172A', 
                'secondary_color' => '#F8FAFC',
                'accent_color' => '#22C55E', 
                'email' => 'contact@hubathlet.app',
                'city' => '—',
                'country' => '—',
                'plan' => 'demo',
                'is_active' => true,
            ]
        );

        // Settings du club
        ClubSetting::firstOrCreate(
            ['club_id' => $club->id],
            [
                'app_name' => 'Hubathlet',
                'app_short_name' => 'Hubathlet',
                'app_icon' => null,
                'app_theme_color' => '#0F172A',
                'app_background_color' => '#F8FAFC',
                'timezone' => 'Europe/Paris',
                'language' => 'fr',
                'push_notifications' => false,
                'public_registration' => true,
            ]
        );
    }
}
