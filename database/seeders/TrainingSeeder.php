<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Training;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $club = Club::where('slug', 'hubathlet-demo')->first();
        
        if (!$club) {
            $this->command->error('❌ Club de démo non trouvé. Lancez d\'abord ClubSeeder.');
            return;
        }

        // Récupérer les coachs et joueurs
        $coachRole = Role::where('slug', Role::COACH)->first();
        $playerRole = Role::where('slug', Role::PLAYER)->first();
        
        $coaches = $club->members()->wherePivot('role_id', $coachRole->id)->get();
        $players = $club->members()->wherePivot('role_id', $playerRole->id)->wherePivot('status', 'active')->get();
        
        if ($coaches->isEmpty()) {
            $this->command->error('❌ Aucun coach trouvé. Lancez d\'abord ClubSeeder.');
            return;
        }

        $mainCoach = $coaches->first();
        $secondCoach = $coaches->skip(1)->first() ?? $mainCoach;

        // ========================================
        // SÉANCES PASSÉES (avec présences)
        // ========================================
        $this->createPastTrainings($club, $mainCoach, $secondCoach, $players);

        // ========================================
        // SÉANCES AUJOURD'HUI
        // ========================================
        $this->createTodayTrainings($club, $mainCoach, $players);

        // ========================================
        // SÉANCES À VENIR
        // ========================================
        $this->createUpcomingTrainings($club, $mainCoach, $secondCoach, $players);

        $this->command->info('✅ Séances d\'entraînement créées avec succès !');
        $this->command->info('   - Séances passées avec présences');
        $this->command->info('   - Séances aujourd\'hui');
        $this->command->info('   - Séances à venir');
    }

    /**
     * Créer des séances passées avec des présences
     */
    private function createPastTrainings(Club $club, User $mainCoach, User $secondCoach, $players): void
    {
        $types = ['training', 'training', 'training', 'match', 'training'];
        $locations = ['Stade Municipal', 'Gymnase A', 'Terrain B', 'Complexe Sportif'];
        
        // Séances des 4 dernières semaines
        for ($week = 4; $week >= 1; $week--) {
            $weekStart = Carbon::now()->subWeeks($week)->startOfWeek();
            
            // Mardi - Entraînement collectif
            $tuesday = $weekStart->copy()->addDays(1);
            $training1 = Training::create([
                'club_id' => $club->id,
                'coach_id' => $mainCoach->id,
                'title' => 'Entraînement collectif',
                'description' => 'Séance collective - travail technique et tactique',
                'type' => 'training',
                'location' => $locations[array_rand($locations)],
                'date' => $tuesday->toDateString(),
                'start_time' => '18:30',
                'end_time' => '20:00',
                'status' => Training::STATUS_COMPLETED,
                'color' => Training::getTypeColor('training'),
            ]);
            $this->addParticipantsWithAttendance($training1, $players);

            // Jeudi - Entraînement ou match
            $thursday = $weekStart->copy()->addDays(3);
            $type = $types[array_rand($types)];
            $training2 = Training::create([
                'club_id' => $club->id,
                'coach_id' => $secondCoach->id,
                'title' => $type === 'match' ? 'Match amical' : 'Entraînement physique',
                'description' => $type === 'match' ? 'Match contre équipe locale' : 'Préparation physique et endurance',
                'type' => $type,
                'location' => $type === 'match' ? 'Stade Municipal' : $locations[array_rand($locations)],
                'date' => $thursday->toDateString(),
                'start_time' => $type === 'match' ? '15:00' : '18:00',
                'end_time' => $type === 'match' ? '17:00' : '19:30',
                'status' => Training::STATUS_COMPLETED,
                'color' => Training::getTypeColor($type),
            ]);
            $this->addParticipantsWithAttendance($training2, $players);

            // Samedi - Entraînement ou événement (1 sur 2)
            if ($week % 2 === 0) {
                $saturday = $weekStart->copy()->addDays(5);
                $training3 = Training::create([
                    'club_id' => $club->id,
                    'coach_id' => $mainCoach->id,
                    'title' => 'Entraînement libre',
                    'description' => 'Séance libre - travail individuel et collectif',
                    'type' => 'training',
                    'location' => 'Gymnase A',
                    'date' => $saturday->toDateString(),
                    'start_time' => '10:00',
                    'end_time' => '12:00',
                    'status' => Training::STATUS_COMPLETED,
                    'color' => Training::getTypeColor('training'),
                ]);
                $this->addParticipantsWithAttendance($training3, $players->take(8));
            }
        }

        // Une réunion passée
        $lastWeek = Carbon::now()->subWeek();
        Training::create([
            'club_id' => $club->id,
            'coach_id' => $mainCoach->id,
            'title' => 'Réunion d\'équipe',
            'description' => 'Point sur la saison et objectifs',
            'type' => 'meeting',
            'location' => 'Salle de réunion',
            'date' => $lastWeek->toDateString(),
            'start_time' => '19:00',
            'end_time' => '20:00',
            'status' => Training::STATUS_COMPLETED,
            'color' => Training::getTypeColor('meeting'),
        ]);
    }

    /**
     * Créer des séances pour aujourd'hui
     */
    private function createTodayTrainings(Club $club, User $coach, $players): void
    {
        $today = Carbon::today();
        
        // Séance du matin (si on est le weekend)
        if ($today->isWeekend()) {
            $morningTraining = Training::create([
                'club_id' => $club->id,
                'coach_id' => $coach->id,
                'title' => 'Entraînement matinal',
                'description' => 'Séance technique du matin',
                'type' => 'training',
                'location' => 'Stade Municipal',
                'date' => $today->toDateString(),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'status' => Training::STATUS_SCHEDULED,
                'color' => Training::getTypeColor('training'),
            ]);
            
            // Ajouter les participants (inscrits)
            foreach ($players->take(10) as $player) {
                $morningTraining->participants()->attach($player->id, [
                    'status' => 'registered',
                ]);
            }
        }
        
        // Séance du soir
        $eveningTraining = Training::create([
            'club_id' => $club->id,
            'coach_id' => $coach->id,
            'title' => 'Entraînement du soir',
            'description' => 'Séance collective - préparation match',
            'type' => 'training',
            'location' => 'Gymnase A',
            'date' => $today->toDateString(),
            'start_time' => '18:30',
            'end_time' => '20:00',
            'status' => Training::STATUS_SCHEDULED,
            'color' => Training::getTypeColor('training'),
        ]);
        
        // Ajouter tous les joueurs actifs
        foreach ($players as $player) {
            $eveningTraining->participants()->attach($player->id, [
                'status' => 'registered',
            ]);
        }
    }

    /**
     * Créer des séances à venir
     */
    private function createUpcomingTrainings(Club $club, User $mainCoach, User $secondCoach, $players): void
    {
        // Prochaines 2 semaines
        for ($week = 1; $week <= 2; $week++) {
            $weekStart = Carbon::now()->addWeeks($week)->startOfWeek();
            
            // Mardi - Entraînement
            $tuesday = $weekStart->copy()->addDays(1);
            $training1 = Training::create([
                'club_id' => $club->id,
                'coach_id' => $mainCoach->id,
                'title' => 'Entraînement collectif',
                'description' => 'Séance technique et tactique',
                'type' => 'training',
                'location' => 'Stade Municipal',
                'date' => $tuesday->toDateString(),
                'start_time' => '18:30',
                'end_time' => '20:00',
                'status' => Training::STATUS_SCHEDULED,
                'color' => Training::getTypeColor('training'),
            ]);
            $this->addParticipants($training1, $players);

            // Jeudi - Entraînement
            $thursday = $weekStart->copy()->addDays(3);
            $training2 = Training::create([
                'club_id' => $club->id,
                'coach_id' => $secondCoach->id,
                'title' => 'Préparation physique',
                'description' => 'Renforcement musculaire et cardio',
                'type' => 'training',
                'location' => 'Gymnase A',
                'date' => $thursday->toDateString(),
                'start_time' => '18:00',
                'end_time' => '19:30',
                'status' => Training::STATUS_SCHEDULED,
                'color' => Training::getTypeColor('training'),
            ]);
            $this->addParticipants($training2, $players);

            // Samedi - Match ou événement
            $saturday = $weekStart->copy()->addDays(5);
            if ($week === 1) {
                // Match la première semaine
                $match = Training::create([
                    'club_id' => $club->id,
                    'coach_id' => $mainCoach->id,
                    'title' => 'Match de championnat',
                    'description' => 'Journée 12 - vs FC Olympique',
                    'type' => 'match',
                    'location' => 'Stade Municipal',
                    'date' => $saturday->toDateString(),
                    'start_time' => '15:00',
                    'end_time' => '17:00',
                    'status' => Training::STATUS_SCHEDULED,
                    'color' => Training::getTypeColor('match'),
                ]);
                $this->addParticipants($match, $players);
            } else {
                // Événement la deuxième semaine
                Training::create([
                    'club_id' => $club->id,
                    'coach_id' => $mainCoach->id,
                    'title' => 'Tournoi inter-clubs',
                    'description' => 'Tournoi amical avec 4 équipes',
                    'type' => 'event',
                    'location' => 'Complexe Sportif',
                    'date' => $saturday->toDateString(),
                    'start_time' => '09:00',
                    'end_time' => '18:00',
                    'status' => Training::STATUS_SCHEDULED,
                    'color' => Training::getTypeColor('event'),
                ]);
            }
        }

        // Réunion dans 10 jours
        $meetingDate = Carbon::now()->addDays(10);
        Training::create([
            'club_id' => $club->id,
            'coach_id' => $mainCoach->id,
            'title' => 'Réunion de mi-saison',
            'description' => 'Bilan et préparation de la deuxième partie de saison',
            'type' => 'meeting',
            'location' => 'Salle de réunion',
            'date' => $meetingDate->toDateString(),
            'start_time' => '19:00',
            'end_time' => '20:30',
            'status' => Training::STATUS_SCHEDULED,
            'color' => Training::getTypeColor('meeting'),
        ]);
    }

    /**
     * Ajouter des participants avec des présences variées (pour séances passées)
     */
    private function addParticipantsWithAttendance(Training $training, $players): void
    {
        $statuses = ['present', 'present', 'present', 'present', 'present', 'present', 'present', 'absent', 'excused', 'late'];
        
        foreach ($players as $player) {
            $status = $statuses[array_rand($statuses)];
            
            $training->participants()->attach($player->id, [
                'status' => $status,
            ]);
        }
    }

    /**
     * Ajouter des participants inscrits (pour séances futures)
     */
    private function addParticipants(Training $training, $players): void
    {
        foreach ($players as $player) {
            $training->participants()->attach($player->id, [
                'status' => 'registered',
            ]);
        }
    }
}

