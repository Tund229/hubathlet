<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Training;
use App\Models\Role;
use Illuminate\Http\Request;
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
     * Dashboard Joueur - Vue principale
     */
    public function dashboard()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Séances auxquelles le joueur participe
        $myTrainings = $user->trainings()
            ->where('club_id', $club->id)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        // Séances d'aujourd'hui
        $todayTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isToday();
        });

        // Prochaines séances
        $upcomingTrainings = $myTrainings->filter(function ($training) {
            return $training->date && $training->date->isFuture();
        })->take(5);

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

        return view('player.dashboard', compact(
            'club',
            'todayTrainings',
            'upcomingTrainings',
            'totalParticipations',
            'attendanceRate',
            'totalHours'
        ));
    }

    /**
     * Planning du joueur
     */
    public function schedule(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Filtres
        $filter = $request->get('filter', 'upcoming');
        
        $query = $user->trainings()
            ->where('club_id', $club->id)
            ->with('coach', 'participants');

        if ($filter === 'upcoming') {
            $query->where('date', '>=', Carbon::today())->orderBy('date')->orderBy('start_time');
        } elseif ($filter === 'past') {
            $query->where('date', '<', Carbon::today())->orderBy('date', 'desc')->orderBy('start_time', 'desc');
        } elseif ($filter === 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                  ->orderBy('date')->orderBy('start_time');
        } elseif ($filter === 'month') {
            $query->whereMonth('date', Carbon::now()->month)
                  ->whereYear('date', Carbon::now()->year)
                  ->orderBy('date')->orderBy('start_time');
        } else {
            $query->orderBy('date', 'desc')->orderBy('start_time', 'desc');
        }

        $trainings = $query->paginate(10);

        // Séances d'aujourd'hui
        $todayTrainings = $user->trainings()
            ->where('club_id', $club->id)
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time')
            ->get();

        // Stats
        $stats = [
            'upcoming' => $user->trainings()->where('club_id', $club->id)->where('date', '>=', Carbon::today())->count(),
            'this_week' => $user->trainings()->where('club_id', $club->id)->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'this_month' => $user->trainings()->where('club_id', $club->id)->whereMonth('date', Carbon::now()->month)->whereYear('date', Carbon::now()->year)->count(),
        ];

        // Données pour le calendrier FullCalendar
        $calendarEvents = $user->trainings()
            ->where('club_id', $club->id)
            ->whereBetween('date', [
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
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        
        // Toutes les participations terminées
        $completedTrainings = $user->trainings()
            ->where('club_id', $club->id)
            ->where('status', Training::STATUS_COMPLETED)
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
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->translatedFormat('M Y');
            
            $monthTrainings = $completedTrainings->filter(function ($t) use ($date) {
                return $t->date->month === $date->month && $t->date->year === $date->year;
            });
            
            $monthTotal = $monthTrainings->count();
            $monthPresent = $monthTrainings->filter(fn($t) => in_array($t->pivot->status, ['present', 'late']))->count();
            
            $monthlyStats[$monthName] = $monthTotal > 0 ? round(($monthPresent / $monthTotal) * 100) : 0;
        }

        // Dernières participations
        $recentTrainings = $user->trainings()
            ->where('club_id', $club->id)
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        return view('player.stats', compact(
            'totalTrainings', 'presentCount', 'absentCount', 'excusedCount', 'lateCount',
            'attendanceRate', 'totalHours', 'monthlyStats', 'recentTrainings'
        ));
    }

    /**
     * Profil du joueur
     */
    public function profile()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        $membership = $club->members()->where('user_id', $user->id)->first();
        
        return view('player.profile', compact('club', 'user', 'membership'));
    }

    /**
     * Paramètres du joueur
     */
    public function settings()
    {
        return view('player.settings');
    }
}

