<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Training;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PlayerController extends Controller
{
    /**
     * Récupère le club courant
     */
    protected function getCurrentClub(): ?Club
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Récupère les infos de membership
     */
    protected function getMembership(Club $club)
    {
        return $club->members()->where('user_id', auth()->id())->first();
    }

    /**
     * Dashboard Joueur - Vue principale
     */
    public function dashboard()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        $membership = $this->getMembership($club);
        $role = $membership ? Role::find($membership->pivot->role_id) : null;
        
        // Séances auxquelles le joueur participe
        $myTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->orderBy('trainings.date')
            ->orderBy('trainings.start_time')
            ->get();

        // Prochaine séance (la plus proche)
        $nextTraining = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->where('trainings.date', '>=', Carbon::today())
            ->where('trainings.status', '!=', Training::STATUS_CANCELLED)
            ->orderBy('trainings.date')
            ->orderBy('trainings.start_time')
            ->first();

        // Séances d'aujourd'hui
        $todayTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isToday();
        });

        // Séances de la semaine (pour mini-calendrier)
        $weekTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->whereBetween('trainings.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('trainings.date')
            ->orderBy('trainings.start_time')
            ->get();

        // Prochaines séances (5 max)
        $upcomingTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->where('trainings.date', '>=', Carbon::today())
            ->where('trainings.status', '!=', Training::STATUS_CANCELLED)
            ->orderBy('trainings.date')
            ->orderBy('trainings.start_time')
            ->take(5)
            ->get();

        // Stats de présence
        $completedTrainings = $myTrainings->where('status', Training::STATUS_COMPLETED);
        $totalParticipations = $completedTrainings->count();
        $presentCount = $completedTrainings->filter(function ($training) {
            return in_array($training->pivot->status, ['present', 'late']);
        })->count();
        $attendanceRate = $totalParticipations > 0 ? round(($presentCount / $totalParticipations) * 100) : 0;

        // Total heures d'entraînement
        $totalMinutes = $completedTrainings->filter(function ($training) {
            return in_array($training->pivot->status, ['present', 'late']);
        })->sum('duration');
        $totalHours = round($totalMinutes / 60, 1);

        // Streak de présence (séances consécutives présent)
        $streak = 0;
        $sortedCompleted = $completedTrainings->sortByDesc('date');
        foreach ($sortedCompleted as $training) {
            if (in_array($training->pivot->status, ['present', 'late'])) {
                $streak++;
            } else {
                break;
            }
        }

        // Rang dans l'équipe (basé sur le taux de présence)
        $allPlayersStats = $club->players()->get()->map(function ($player) use ($club) {
            $playerTrainings = $player->trainings()
                ->where('trainings.club_id', $club->id)
                ->where('trainings.status', Training::STATUS_COMPLETED)
                ->get();
            $total = $playerTrainings->count();
            $present = $playerTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
            return [
                'id' => $player->id,
                'rate' => $total > 0 ? round(($present / $total) * 100) : 0
            ];
        })->sortByDesc('rate')->values();
        
        $rank = $allPlayersStats->search(fn($p) => $p['id'] === $user->id) + 1;
        $totalPlayers = $allPlayersStats->count();

        // Dernières activités (5 dernières séances)
        $recentActivities = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->where('trainings.status', Training::STATUS_COMPLETED)
            ->orderBy('trainings.date', 'desc')
            ->take(5)
            ->get();

        // Objectif de présence (90%)
        $presenceGoal = 90;
        $goalProgress = min(100, round(($attendanceRate / $presenceGoal) * 100));

        return view('player.dashboard', compact(
            'club',
            'user',
            'membership',
            'role',
            'nextTraining',
            'todayTrainings',
            'weekTrainings',
            'upcomingTrainings',
            'totalParticipations',
            'presentCount',
            'attendanceRate',
            'totalHours',
            'streak',
            'rank',
            'totalPlayers',
            'recentActivities',
            'presenceGoal',
            'goalProgress'
        ));
    }

    /**
     * Planning du joueur
     */
    public function schedule(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Filtres
        $filter = $request->get('filter', 'upcoming');
        
        $query = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->with('coach', 'participants');

        if ($filter === 'upcoming') {
            $query->where('trainings.date', '>=', Carbon::today())->orderBy('trainings.date')->orderBy('trainings.start_time');
        } elseif ($filter === 'past') {
            $query->where('trainings.date', '<', Carbon::today())->orderBy('trainings.date', 'desc')->orderBy('trainings.start_time', 'desc');
        } elseif ($filter === 'week') {
            $query->whereBetween('trainings.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                  ->orderBy('trainings.date')->orderBy('trainings.start_time');
        } elseif ($filter === 'month') {
            $query->whereMonth('trainings.date', Carbon::now()->month)
                  ->whereYear('trainings.date', Carbon::now()->year)
                  ->orderBy('trainings.date')->orderBy('trainings.start_time');
        } else {
            $query->orderBy('trainings.date', 'desc')->orderBy('trainings.start_time', 'desc');
        }

        $trainings = $query->paginate(10);

        // Séances d'aujourd'hui
        $todayTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->whereDate('trainings.date', Carbon::today())
            ->orderBy('trainings.start_time')
            ->get();

        // Stats
        $stats = [
            'upcoming' => $user->trainings()->where('trainings.club_id', $club->id)->where('trainings.date', '>=', Carbon::today())->count(),
            'this_week' => $user->trainings()->where('trainings.club_id', $club->id)->whereBetween('trainings.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => $user->trainings()->where('trainings.club_id', $club->id)->whereMonth('trainings.date', Carbon::now()->month)->whereYear('trainings.date', Carbon::now()->year)->count(),
        ];

        // Données pour le calendrier FullCalendar
        $calendarEvents = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->whereBetween('trainings.date', [
                Carbon::now()->subMonths(3)->startOfMonth(),
                Carbon::now()->addMonths(3)->endOfMonth()
            ])
            ->get()
            ->map(function ($training) {
                $myStatus = $training->pivot->status ?? 'registered';
                return [
                    'id' => $training->id,
                    'title' => $training->title,
                    'start' => $training->date->format('Y-m-d') . 'T' . $training->start_time,
                    'end' => $training->date->format('Y-m-d') . 'T' . $training->end_time,
                    'url' => route('planning.show', $training),
                    'backgroundColor' => $training->type_color,
                    'borderColor' => $training->type_color,
                    'extendedProps' => [
                        'type' => $training->type,
                        'typeLabel' => $training->type_label,
                        'location' => $training->location,
                        'time' => Carbon::parse($training->start_time)->format('H:i') . ' - ' . Carbon::parse($training->end_time)->format('H:i'),
                        'status' => $training->status,
                        'myStatus' => $myStatus,
                    ],
                ];
            });

        return view('player.schedule', compact('trainings', 'todayTrainings', 'stats', 'filter', 'calendarEvents'));
    }

    /**
     * Statistiques du joueur
     */
    public function stats()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        $membership = $this->getMembership($club);
        $role = $membership ? Role::find($membership->pivot->role_id) : null;
        
        // Toutes les participations terminées
        $completedTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->where('trainings.status', Training::STATUS_COMPLETED)
            ->orderBy('trainings.date', 'desc')
            ->get();

        // Stats globales
        $totalTrainings = $completedTrainings->count();
        $presentCount = $completedTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
        $absentCount = $completedTrainings->filter(fn($t) => $t->pivot->status === 'absent')->count();
        $excusedCount = $completedTrainings->filter(fn($t) => $t->pivot->status === 'excused')->count();
        $lateCount = $completedTrainings->filter(fn($t) => $t->pivot->status === 'late')->count();
        
        $attendanceRate = $totalTrainings > 0 ? round(($presentCount / $totalTrainings) * 100) : 0;

        // Total heures
        $totalMinutes = $completedTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->sum('duration');
        $totalHours = round($totalMinutes / 60, 1);

        // Évolution mensuelle (6 derniers mois)
        $monthlyStats = [];
        $monthlyHours = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M');
            
            $monthTrainings = $completedTrainings->filter(function ($t) use ($date) {
                return $t->date->month === $date->month && $t->date->year === $date->year;
            });
            
            $monthTotal = $monthTrainings->count();
            $monthPresent = $monthTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
            
            $monthlyStats[$monthName] = $monthTotal > 0 ? round(($monthPresent / $monthTotal) * 100) : 0;
            
            $monthMinutes = $monthTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->sum('duration');
            $monthlyHours[$monthName] = round($monthMinutes / 60, 1);
        }

        // Stats par type de séance
        $statsByType = [];
        foreach (['training', 'match', 'event', 'meeting'] as $type) {
            $typeTrainings = $completedTrainings->where('type', $type);
            $typeTotal = $typeTrainings->count();
            $typePresent = $typeTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
            $statsByType[$type] = [
                'total' => $typeTotal,
                'present' => $typePresent,
                'rate' => $typeTotal > 0 ? round(($typePresent / $typeTotal) * 100) : 0,
            ];
        }

        // Comparaison avec la moyenne de l'équipe
        $teamAverage = 0;
        $teamCount = 0;
        foreach ($club->players()->get() as $player) {
            $playerTrainings = $player->trainings()
                ->where('trainings.club_id', $club->id)
                ->where('trainings.status', Training::STATUS_COMPLETED)
                ->get();
            $pTotal = $playerTrainings->count();
            $pPresent = $playerTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
            if ($pTotal > 0) {
                $teamAverage += ($pPresent / $pTotal) * 100;
                $teamCount++;
            }
        }
        $teamAverage = $teamCount > 0 ? round($teamAverage / $teamCount) : 0;
        $vsTeam = $attendanceRate - $teamAverage;

        // Dernières participations
        $recentTrainings = $completedTrainings->take(10);

        // Badges
        $badges = $this->calculateBadges($attendanceRate, $totalHours, $presentCount, $lateCount);

        return view('player.stats', compact(
            'club', 'user', 'membership', 'role',
            'totalTrainings', 'presentCount', 'absentCount', 'excusedCount', 'lateCount',
            'attendanceRate', 'totalHours', 'monthlyStats', 'monthlyHours', 'statsByType',
            'teamAverage', 'vsTeam', 'recentTrainings', 'badges'
        ));
    }

    /**
     * Calculer les badges du joueur
     */
    protected function calculateBadges($attendanceRate, $totalHours, $presentCount, $lateCount)
    {
        $badges = [];
        
        // Badge Assiduité
        if ($attendanceRate >= 95) {
            $badges[] = ['name' => 'Exemplaire', 'desc' => '+95% présence', 'color' => 'amber'];
        } elseif ($attendanceRate >= 90) {
            $badges[] = ['name' => 'Assidu', 'desc' => '+90% présence', 'color' => 'emerald'];
        } elseif ($attendanceRate >= 80) {
            $badges[] = ['name' => 'Régulier', 'desc' => '+80% présence', 'color' => 'blue'];
        }
        
        // Badge Heures
        if ($totalHours >= 100) {
            $badges[] = ['name' => 'Centurion', 'desc' => '+100h d\'entraînement', 'color' => 'violet'];
        } elseif ($totalHours >= 50) {
            $badges[] = ['name' => 'Endurant', 'desc' => '+50h d\'entraînement', 'color' => 'orange'];
        }
        
        // Badge Participations
        if ($presentCount >= 50) {
            $badges[] = ['name' => 'Vétéran', 'desc' => '+50 séances', 'color' => 'slate'];
        } elseif ($presentCount >= 20) {
            $badges[] = ['name' => 'Confirmé', 'desc' => '+20 séances', 'color' => 'cyan'];
        }
        
        // Badge Ponctualité
        $lateRatio = $presentCount > 0 ? ($lateCount / $presentCount) * 100 : 0;
        if ($presentCount >= 10 && $lateRatio < 5) {
            $badges[] = ['name' => 'Ponctuel', 'desc' => 'Toujours à l\'heure', 'color' => 'green'];
        }
        
        return $badges;
    }

    /**
     * Profil du joueur
     */
    public function profile()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        $membership = $this->getMembership($club);
        $role = $membership ? Role::find($membership->pivot->role_id) : null;
        
        // Stats pour la carte
        $completedTrainings = $user->trainings()
            ->where('trainings.club_id', $club->id)
            ->where('trainings.status', Training::STATUS_COMPLETED)
            ->get();
        $totalParticipations = $completedTrainings->count();
        $presentCount = $completedTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
        $attendanceRate = $totalParticipations > 0 ? round(($presentCount / $totalParticipations) * 100) : 0;
        
        return view('player.profile', compact('club', 'user', 'membership', 'role', 'attendanceRate', 'totalParticipations'));
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
        ]);
        
        $user->update($validated);
        
        return back()->with('success', 'Profil mis à jour !');
    }

    /**
     * Paramètres du joueur
     */
    public function settings()
    {
        $user = auth()->user();
        $club = $this->getCurrentClub();
        
        return view('player.settings', compact('user', 'club'));
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);
        
        return back()->with('success', 'Mot de passe mis à jour !');
    }
}
