<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Propriétaire',
                'slug' => Role::OWNER,
                'description' => 'Créateur et propriétaire du club. Accès total à toutes les fonctionnalités.',
                'color' => '#7C3AED', // Violet
                'level' => 100,
                'is_system' => true,
            ],
            [
                'name' => 'Administrateur',
                'slug' => Role::ADMIN,
                'description' => 'Gestionnaire du club. Peut gérer les membres, séances et paramètres.',
                'color' => '#2563EB', // Bleu
                'level' => 80,
                'is_system' => true,
            ],
            [
                'name' => 'Coach',
                'slug' => Role::COACH,
                'description' => 'Entraîneur. Peut créer des séances, faire l\'appel et voir les stats des joueurs.',
                'color' => '#059669', // Emerald
                'level' => 60,
                'is_system' => true,
            ],
            [
                'name' => 'Modérateur',
                'slug' => Role::MODERATOR,
                'description' => 'Modérateur. Peut gérer les membres et valider les inscriptions.',
                'color' => '#D97706', // Amber
                'level' => 40,
                'is_system' => true,
            ],
            [
                'name' => 'Joueur',
                'slug' => Role::PLAYER,
                'description' => 'Membre actif du club. Accès à l\'application, présences et statistiques personnelles.',
                'color' => '#10B981', // Green
                'level' => 20,
                'is_system' => true,
            ],
            [
                'name' => 'Parent',
                'slug' => Role::PARENT,
                'description' => 'Parent d\'un adhérent. Accès limité au suivi de son enfant.',
                'color' => '#EC4899', // Pink
                'level' => 10,
                'is_system' => true,
            ],
            [
                'name' => 'Invité',
                'slug' => Role::GUEST,
                'description' => 'Visiteur. Accès en lecture seule au planning public.',
                'color' => '#6B7280', // Gray
                'level' => 0,
                'is_system' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}

