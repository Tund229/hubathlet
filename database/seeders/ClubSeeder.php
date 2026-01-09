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

        // Récupérer tous les rôles
        $ownerRole = Role::where('slug', Role::OWNER)->first();
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        $coachRole = Role::where('slug', Role::COACH)->first();
        $moderatorRole = Role::where('slug', Role::MODERATOR)->first();
        $playerRole = Role::where('slug', Role::PLAYER)->first();
        $parentRole = Role::where('slug', Role::PARENT)->first();
        $guestRole = Role::where('slug', Role::GUEST)->first();

        // ========================================
        // 1. OWNER - Propriétaire du club
        // ========================================
        if ($ownerRole) {
            $owner = User::firstOrCreate(
                ['email' => 'owner@hubathlet.app'],
                [
                    'name' => 'Pierre Martin',
                    'password' => Hash::make('password'),
                    'phone' => '06 00 00 00 01',
                ]
            );
            
            $this->attachToClub($club, $owner, $ownerRole, [
                'status' => 'active',
                'joined_at' => now()->subYears(2),
            ]);
        }

        // ========================================
        // 2. ADMIN - Administrateurs
        // ========================================
        if ($adminRole) {
            $admins = [
                ['name' => 'Sophie Lefebvre', 'email' => 'admin@hubathlet.app', 'phone' => '06 00 00 00 02'],
                ['name' => 'Jean-Marc Durand', 'email' => 'admin2@hubathlet.app', 'phone' => '06 00 00 00 03'],
            ];

            foreach ($admins as $adminData) {
                $admin = User::firstOrCreate(
                    ['email' => $adminData['email']],
                    [
                        'name' => $adminData['name'],
                        'password' => Hash::make('password'),
                        'phone' => $adminData['phone'],
                    ]
                );
                
                $this->attachToClub($club, $admin, $adminRole, [
                    'status' => 'active',
                    'joined_at' => now()->subMonths(18),
                ]);
            }
        }

        // ========================================
        // 3. COACH - Entraîneurs
        // ========================================
        if ($coachRole) {
            $coaches = [
                ['name' => 'Marc Dupont', 'email' => 'coach@hubathlet.app', 'phone' => '06 12 34 56 78', 'license' => 'COACH-2024-001'],
                ['name' => 'Isabelle Renard', 'email' => 'coach2@hubathlet.app', 'phone' => '06 23 45 67 89', 'license' => 'COACH-2024-002'],
                ['name' => 'Antoine Moreau', 'email' => 'coach3@hubathlet.app', 'phone' => '06 34 56 78 90', 'license' => 'COACH-2024-003'],
            ];

            foreach ($coaches as $coachData) {
                $coach = User::firstOrCreate(
                    ['email' => $coachData['email']],
                    [
                        'name' => $coachData['name'],
                        'password' => Hash::make('password'),
                        'phone' => $coachData['phone'],
                    ]
                );
                
                $this->attachToClub($club, $coach, $coachRole, [
                    'status' => 'active',
                    'joined_at' => now()->subMonths(rand(6, 24)),
                    'license_number' => $coachData['license'],
                ]);
            }
        }

        // ========================================
        // 4. MODERATOR - Modérateurs
        // ========================================
        if ($moderatorRole) {
            $moderators = [
                ['name' => 'Claire Fontaine', 'email' => 'moderator@hubathlet.app', 'phone' => '06 11 22 33 44'],
                ['name' => 'Nicolas Bernard', 'email' => 'moderator2@hubathlet.app', 'phone' => '06 22 33 44 55'],
            ];

            foreach ($moderators as $modData) {
                $moderator = User::firstOrCreate(
                    ['email' => $modData['email']],
                    [
                        'name' => $modData['name'],
                        'password' => Hash::make('password'),
                        'phone' => $modData['phone'],
                    ]
                );
                
                $this->attachToClub($club, $moderator, $moderatorRole, [
                    'status' => 'active',
                    'joined_at' => now()->subMonths(rand(3, 12)),
                ]);
            }
        }

        // ========================================
        // 5. PLAYER - Joueurs/Adhérents
        // ========================================
        if ($playerRole) {
            $players = [
                ['name' => 'Thomas Martin', 'email' => 'thomas@demo.com', 'jersey' => '10', 'position' => 'Attaquant', 'birth' => '2000-05-15'],
                ['name' => 'Lucas Bernard', 'email' => 'lucas@demo.com', 'jersey' => '7', 'position' => 'Milieu', 'birth' => '1998-08-22'],
                ['name' => 'Emma Petit', 'email' => 'emma@demo.com', 'jersey' => '9', 'position' => 'Attaquante', 'birth' => '2001-03-10'],
                ['name' => 'Léa Dubois', 'email' => 'lea@demo.com', 'jersey' => '5', 'position' => 'Défenseuse', 'birth' => '1999-11-28'],
                ['name' => 'Hugo Moreau', 'email' => 'hugo@demo.com', 'jersey' => '1', 'position' => 'Gardien', 'birth' => '1997-07-03'],
                ['name' => 'Chloé Lambert', 'email' => 'chloe@demo.com', 'jersey' => '11', 'position' => 'Ailière', 'birth' => '2002-01-20'],
                ['name' => 'Nathan Girard', 'email' => 'nathan@demo.com', 'jersey' => '8', 'position' => 'Milieu', 'birth' => '2000-09-14'],
                ['name' => 'Manon Roux', 'email' => 'manon@demo.com', 'jersey' => '3', 'position' => 'Défenseuse', 'birth' => '2001-06-07'],
                ['name' => 'Alexandre Fournier', 'email' => 'alexandre@demo.com', 'jersey' => '6', 'position' => 'Milieu défensif', 'birth' => '1999-04-25'],
                ['name' => 'Julie Mercier', 'email' => 'julie@demo.com', 'jersey' => '2', 'position' => 'Latérale', 'birth' => '2000-12-01'],
                ['name' => 'Maxime Leroy', 'email' => 'maxime@demo.com', 'jersey' => '4', 'position' => 'Défenseur central', 'birth' => '1998-02-18'],
                ['name' => 'Camille Simon', 'email' => 'camille@demo.com', 'jersey' => '14', 'position' => 'Remplaçant', 'birth' => '2003-10-30'],
            ];

            $licenseCounter = 1;
            foreach ($players as $playerData) {
                $player = User::firstOrCreate(
                    ['email' => $playerData['email']],
                    [
                        'name' => $playerData['name'],
                        'password' => Hash::make('password'),
                        'birth_date' => $playerData['birth'],
                    ]
                );
                
                $this->attachToClub($club, $player, $playerRole, [
                    'status' => 'active',
                    'joined_at' => now()->subMonths(rand(1, 24)),
                    'jersey_number' => $playerData['jersey'],
                    'position' => $playerData['position'],
                    'license_number' => 'LIC-2024-' . str_pad($licenseCounter++, 3, '0', STR_PAD_LEFT),
                    'license_expires_at' => now()->addMonths(rand(3, 12)),
                ]);
            }

            // Quelques joueurs en attente
            $pendingPlayers = [
                ['name' => 'Kevin Blanc', 'email' => 'kevin@demo.com'],
                ['name' => 'Sarah Dupuis', 'email' => 'sarah@demo.com'],
            ];

            foreach ($pendingPlayers as $playerData) {
                $player = User::firstOrCreate(
                    ['email' => $playerData['email']],
                    [
                        'name' => $playerData['name'],
                        'password' => Hash::make('password'),
                    ]
                );
                
                $this->attachToClub($club, $player, $playerRole, [
                    'status' => 'pending',
                    'joined_at' => now(),
                ]);
            }

            // Un joueur inactif
            $inactivePlayer = User::firstOrCreate(
                ['email' => 'ancien@demo.com'],
                [
                    'name' => 'François Ancien',
                    'password' => Hash::make('password'),
                ]
            );
            
            $this->attachToClub($club, $inactivePlayer, $playerRole, [
                'status' => 'inactive',
                'joined_at' => now()->subYears(2),
                'notes' => 'A quitté le club en 2025',
            ]);
        }

        // ========================================
        // 6. PARENT - Parents d'adhérents
        // ========================================
        if ($parentRole) {
            $parents = [
                ['name' => 'Marie Martin', 'email' => 'parent@demo.com', 'phone' => '06 99 88 77 66'],
                ['name' => 'Philippe Petit', 'email' => 'parent2@demo.com', 'phone' => '06 88 77 66 55'],
            ];

            foreach ($parents as $parentData) {
                $parent = User::firstOrCreate(
                    ['email' => $parentData['email']],
                    [
                        'name' => $parentData['name'],
                        'password' => Hash::make('password'),
                        'phone' => $parentData['phone'],
                    ]
                );
                
                $this->attachToClub($club, $parent, $parentRole, [
                    'status' => 'active',
                    'joined_at' => now()->subMonths(rand(1, 12)),
                    'notes' => 'Parent d\'un joueur',
                ]);
            }
        }

        // ========================================
        // 7. GUEST - Invités
        // ========================================
        if ($guestRole) {
            $guest = User::firstOrCreate(
                ['email' => 'guest@demo.com'],
                [
                    'name' => 'Visiteur Test',
                    'password' => Hash::make('password'),
                ]
            );
            
            $this->attachToClub($club, $guest, $guestRole, [
                'status' => 'active',
                'joined_at' => now(),
                'notes' => 'Compte invité pour démonstration',
            ]);
        }

        $this->command->info('✅ Club de démo créé avec succès !');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Owner', 'owner@hubathlet.app', 'password'],
                ['Admin', 'admin@hubathlet.app', 'password'],
                ['Coach', 'coach@hubathlet.app', 'password'],
                ['Moderator', 'moderator@hubathlet.app', 'password'],
                ['Player', 'thomas@demo.com', 'password'],
                ['Parent', 'parent@demo.com', 'password'],
                ['Guest', 'guest@demo.com', 'password'],
            ]
        );
    }

    /**
     * Attache un utilisateur au club s'il n'est pas déjà membre
     */
    private function attachToClub(Club $club, User $user, Role $role, array $pivotData = []): void
    {
        if (!$club->members()->where('user_id', $user->id)->exists()) {
            $club->members()->attach($user->id, array_merge([
                'role_id' => $role->id,
            ], $pivotData));
        }
    }
}
