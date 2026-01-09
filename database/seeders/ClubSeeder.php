<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\ClubSetting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Club par défaut
        $club = Club::firstOrCreate(
            ['slug' => 'hubathlet-demo'],
            [
                'name' => 'Hubathlet Demo Club',
                'logo' => null,
                'primary_color' => '#0F172A', 
                'secondary_color' => '#F8FAFC',
                'accent_color' => '#22C55E', 
                'email' => 'contact@hubathlet.app',
                'city' => 'Paris',
                'country' => 'France',
                'plan' => 'demo',
                'is_active' => true,
                'phone' => '06 12 34 56 78',
            ]
        );

        // Settings du club
        ClubSetting::firstOrCreate(
            ['club_id' => $club->id],
            [
                'app_name' => 'Hubathlet Demo',
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

        // Créer un utilisateur propriétaire de demo
        $ownerRole = Role::where('slug', Role::OWNER)->first();
        $coachRole = Role::where('slug', Role::COACH)->first();
        $playerRole = Role::where('slug', Role::PLAYER)->first();
        
        if ($ownerRole) {
            // Utilisateur admin/propriétaire
            $owner = User::firstOrCreate(
                ['email' => 'admin@hubathlet.app'],
                [
                    'name' => 'Admin Hubathlet',
                    'password' => Hash::make('password'),
                ]
            );
            
            // Associer au club si pas déjà membre
            if (!$club->members()->where('user_id', $owner->id)->exists()) {
                $club->members()->attach($owner->id, [
                    'role_id' => $ownerRole->id,
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }
        }

        // Ajouter quelques membres de demo
        if ($coachRole && $playerRole) {
            // Coach
            $coach = User::firstOrCreate(
                ['email' => 'coach@hubathlet.app'],
                [
                    'name' => 'Marc Dupont',
                    'password' => Hash::make('password'),
                    'phone' => '06 12 34 56 78',
                ]
            );
            
            if (!$club->members()->where('user_id', $coach->id)->exists()) {
                $club->members()->attach($coach->id, [
                    'role_id' => $coachRole->id,
                    'status' => 'active',
                    'joined_at' => now()->subMonths(6),
                    'license_number' => 'COACH-2024-001',
                ]);
            }

            // Joueurs de demo
            $players = [
                ['name' => 'Thomas Martin', 'email' => 'thomas@demo.com', 'jersey_number' => '10', 'position' => 'Attaquant'],
                ['name' => 'Lucas Bernard', 'email' => 'lucas@demo.com', 'jersey_number' => '7', 'position' => 'Milieu'],
                ['name' => 'Emma Petit', 'email' => 'emma@demo.com', 'jersey_number' => '9', 'position' => 'Attaquante'],
                ['name' => 'Léa Dubois', 'email' => 'lea@demo.com', 'jersey_number' => '5', 'position' => 'Défenseuse'],
                ['name' => 'Hugo Moreau', 'email' => 'hugo@demo.com', 'jersey_number' => '1', 'position' => 'Gardien'],
            ];

            foreach ($players as $playerData) {
                $player = User::firstOrCreate(
                    ['email' => $playerData['email']],
                    [
                        'name' => $playerData['name'],
                        'password' => Hash::make('password'),
                    ]
                );
                
                if (!$club->members()->where('user_id', $player->id)->exists()) {
                    $club->members()->attach($player->id, [
                        'role_id' => $playerRole->id,
                        'status' => 'active',
                        'joined_at' => now()->subMonths(rand(1, 12)),
                        'jersey_number' => $playerData['jersey_number'],
                        'position' => $playerData['position'],
                        'license_number' => 'LIC-2024-' . str_pad($player->id, 3, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }
    }
}
