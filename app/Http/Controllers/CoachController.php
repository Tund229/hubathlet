<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Training;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoachController extends Controller
{
    /**
     * Récupère le club courant
     */
    protected function getCurrentClub(): ?Club
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Vérifie si l'utilisateur est coach
     */
    protected function isCoach(): bool
    {
        $club = $this->getCurrentClub();
        if (!$club) return false;
        
        $role = auth()->user()->roleInClub($club);
        return $role && in_array($role->slug, [Role::COACH, Role::OWNER, Role::ADMIN]);
    }

    /**
     * Dashboard Coach - Vue principale
     */
    public function dashboard()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Séances du coach (où il est assigné comme coach)
        $myTrainings = Training::where('club_id', $club->id)
            ->where('coach_id', $user->id)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Séances d'aujourd'hui
        $todayTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isToday();
        });

        // Prochaines séances (à venir)
        $upcomingTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isFuture();
        })->take(5);

        // Séances passées (pour stats)
        $pastTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isPast();
        });

        // Stats
        $totalTrainings = $myTrainings->count();
        $completedTrainings = $myTrainings->where('status', Training::STATUS_COMPLETED)->count();
        
        // Taux de présence moyen de mes séances
        $totalAttendances = 0;
        $presentAttendances = 0;
        foreach ($pastTrainings->where('status', Training::STATUS_COMPLETED) as $training) {
            $totalAttendances += $training->participants()->count();
            $presentAttendances += $training->participants()->wherePivot('status', 'present')->count();
        }
        $attendanceRate = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100) : 0;

        // Total heures d'entraînement données
        $totalMinutes = $pastTrainings->where('status', Training::STATUS_COMPLETED)->sum('duration');
        $totalHours = round($totalMinutes / 60, 1);

        // Joueurs que je coache (participants de mes séances)
        $myPlayersIds = [];
        foreach ($myTrainings as $training) {
            $myPlayersIds = array_merge($myPlayersIds, $training->participants()->pluck('users.id')->toArray());
        }
        $myPlayersCount = count(array_unique($myPlayersIds));

        return view('coach.dashboard', compact(
            'club',
            'todayTrainings',
            'upcomingTrainings',
            'totalTrainings',
            'completedTrainings',
            'attendanceRate',
            'totalHours',
            'myPlayersCount'
        ));
    }

    /**
     * Liste des séances du coach
     */
    public function trainings(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        $query = Training::where('club_id', $club->id)
            ->where('coach_id', $user->id)
            ->with('participants')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');

        // Filtres
        if ($request->has('period') && $request->period != '') {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year);
                    break;
                case 'upcoming':
                    $query->where('date', '>=', Carbon::today())->orderBy('date', 'asc');
                    break;
            }
        }

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $trainings = $query->paginate(10);

        $trainingTypes = [
            'training' => 'Entraînement',
            'match' => 'Match',
            'event' => 'Événement',
            'meeting' => 'Réunion',
        ];

        return view('coach.trainings', compact('trainings', 'trainingTypes'));
    }

    /**
     * Page de prise de présence (faire l'appel)
     */
    public function attendance(Training $training)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        // Vérifier que c'est bien une séance du coach
        if ($training->coach_id !== auth()->id()) {
            // Permettre aussi aux admins/owners
            $role = auth()->user()->roleInClub($club);
            if (!$role || !in_array($role->slug, [Role::OWNER, Role::ADMIN])) {
                abort(403, 'Vous n\'êtes pas autorisé à gérer cette séance.');
            }
        }

        $training->load('participants', 'coach');
        
        // Tous les joueurs du club pour pouvoir en ajouter
        $allPlayers = $club->players()->get();
        
        // Joueurs non encore inscrits à cette séance
        $participantIds = $training->participants->pluck('id')->toArray();
        $availablePlayers = $allPlayers->filter(function ($player) use ($participantIds) {
            return !in_array($player->id, $participantIds);
        });

        return view('coach.attendance', compact('training', 'availablePlayers'));
    }

    /**
     * Mettre à jour le statut de présence d'un participant
     */
    public function updateAttendance(Request $request, Training $training, User $participant)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:pending,present,absent,excused,late'],
        ]);

        $training->participants()->updateExistingPivot($participant->id, [
            'status' => $request->status,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Présence mise à jour.');
    }

    /**
     * Ajouter un participant à la séance
     */
    public function addParticipant(Request $request, Training $training)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        // Vérifier que l'utilisateur est membre du club
        if (!$club->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Cet utilisateur n\'est pas membre du club.');
        }

        // Vérifier qu'il n'est pas déjà participant
        if ($training->participants()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'Ce membre participe déjà à cette séance.');
        }

        $training->participants()->attach($request->user_id, [
            'status' => 'pending',
        ]);

        return back()->with('success', 'Participant ajouté.');
    }

    /**
     * Retirer un participant de la séance
     */
    public function removeParticipant(Training $training, User $participant)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        $training->participants()->detach($participant->id);

        return back()->with('success', 'Participant retiré.');
    }

    /**
     * Marquer tous comme présents
     */
    public function markAllPresent(Training $training)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        foreach ($training->participants as $participant) {
            $training->participants()->updateExistingPivot($participant->id, [
                'status' => 'present',
            ]);
        }

        return back()->with('success', 'Tous les participants ont été marqués présents.');
    }

    /**
     * Terminer la séance (marquer comme complétée)
     */
    public function completeTraining(Training $training)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $training->club_id !== $club->id) {
            abort(403);
        }

        $training->update(['status' => Training::STATUS_COMPLETED]);

        return back()->with('success', 'Séance terminée et enregistrée.');
    }

    /**
     * Statistiques des joueurs coachés
     */
    public function playerStats()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Récupérer toutes les séances du coach
        $myTrainings = Training::where('club_id', $club->id)
            ->where('coach_id', $user->id)
            ->where('status', Training::STATUS_COMPLETED)
            ->with('participants')
            ->get();

        // Calculer les stats par joueur
        $playerStats = [];
        foreach ($myTrainings as $training) {
            foreach ($training->participants as $participant) {
                $playerId = $participant->id;
                
                if (!isset($playerStats[$playerId])) {
                    $playerStats[$playerId] = [
                        'user' => $participant,
                        'total' => 0,
                        'present' => 0,
                        'absent' => 0,
                        'excused' => 0,
                        'late' => 0,
                        'total_minutes' => 0,
                    ];
                }
                
                $playerStats[$playerId]['total']++;
                $status = $participant->pivot->status;
                
                if ($status === 'present' || $status === 'late') {
                    $playerStats[$playerId]['present']++;
                    $playerStats[$playerId]['total_minutes'] += $training->duration ?? 90;
                } elseif ($status === 'absent') {
                    $playerStats[$playerId]['absent']++;
                } elseif ($status === 'excused') {
                    $playerStats[$playerId]['excused']++;
                }
                
                if ($status === 'late') {
                    $playerStats[$playerId]['late']++;
                }
            }
        }

        // Calculer le taux de présence et trier
        foreach ($playerStats as &$stat) {
            $stat['attendance_rate'] = $stat['total'] > 0 
                ? round(($stat['present'] / $stat['total']) * 100) 
                : 0;
            $stat['total_hours'] = round($stat['total_minutes'] / 60, 1);
        }

        // Trier par taux de présence décroissant
        usort($playerStats, function ($a, $b) {
            return $b['attendance_rate'] <=> $a['attendance_rate'];
        });

        return view('coach.player-stats', compact('playerStats', 'myTrainings'));
    }
}

