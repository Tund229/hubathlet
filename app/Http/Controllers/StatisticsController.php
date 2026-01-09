<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    /**
     * Récupère le club courant de l'utilisateur
     */
    protected function getCurrentClub()
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Dashboard des statistiques
     */
    public function index(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $period = $request->get('period', 'month'); // week, month, year, all
        $startDate = $this->getStartDate($period);

        // Stats globales
        $globalStats = $this->getGlobalStats($club, $startDate);
        
        // Stats de présence
        $attendanceStats = $this->getAttendanceStats($club, $startDate);
        
        // Top membres (par temps d'entraînement)
        $topMembers = $this->getTopMembers($club, $startDate, 5);
        
        // Évolution des entraînements
        $trainingEvolution = $this->getTrainingEvolution($club, $period);
        
        // Stats par type de séance
        $typeStats = $this->getTypeStats($club, $startDate);
        
        // Répartition des rôles
        $roleDistribution = $this->getRoleDistribution($club);

        return view('statistics.index', compact(
            'club', 'period', 'globalStats', 'attendanceStats', 
            'topMembers', 'trainingEvolution', 'typeStats', 'roleDistribution'
        ));
    }

    /**
     * Statistiques détaillées des membres
     */
    public function members(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $sort = $request->get('sort', 'attendance'); // attendance, training_time, name

        // Récupérer tous les membres avec leurs stats
        $members = $club->activeMembers()->get()->map(function ($member) use ($club, $startDate) {
            return $this->getMemberStats($member, $club, $startDate);
        });

        // Trier
        $members = match($sort) {
            'attendance' => $members->sortByDesc('attendance_rate'),
            'training_time' => $members->sortByDesc('total_training_minutes'),
            'name' => $members->sortBy('name'),
            default => $members->sortByDesc('attendance_rate'),
        };

        return view('statistics.members', compact('club', 'members', 'period', 'sort'));
    }

    /**
     * Statistiques d'un membre spécifique
     */
    public function member(User $member, Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        // Vérifier que le membre appartient au club
        if (!$club->members()->where('user_id', $member->id)->exists()) {
            return redirect()->route('statistics.members')->with('error', 'Membre non trouvé.');
        }

        $period = $request->get('period', 'year');
        $startDate = $this->getStartDate($period);

        // Stats détaillées du membre
        $stats = $this->getMemberDetailedStats($member, $club, $startDate);
        
        // Historique des présences
        $attendanceHistory = $this->getMemberAttendanceHistory($member, $club, $period);
        
        // Progression mensuelle
        $monthlyProgress = $this->getMemberMonthlyProgress($member, $club);

        return view('statistics.member', compact('club', 'member', 'stats', 'attendanceHistory', 'monthlyProgress', 'period'));
    }

    /**
     * Statistiques des entraînements
     */
    public function trainings(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Stats des entraînements
        $stats = [
            'total' => $club->trainings()->where('date', '>=', $startDate)->count(),
            'completed' => $club->trainings()->where('date', '>=', $startDate)->where('status', 'completed')->count(),
            'cancelled' => $club->trainings()->where('date', '>=', $startDate)->where('status', 'cancelled')->count(),
            'total_duration' => $this->getTotalTrainingDuration($club, $startDate),
            'avg_participants' => $this->getAverageParticipants($club, $startDate),
            'avg_attendance_rate' => $this->getAverageAttendanceRate($club, $startDate),
        ];

        // Entraînements avec stats
        $trainings = $club->trainings()
            ->where('date', '>=', $startDate)
            ->where('status', 'completed')
            ->with('participants')
            ->orderBy('date', 'desc')
            ->paginate(15);

        // Stats par jour de la semaine
        $dayStats = $this->getDayOfWeekStats($club, $startDate);

        // Stats par créneau horaire
        $timeStats = $this->getTimeSlotStats($club, $startDate);

        return view('statistics.trainings', compact('club', 'stats', 'trainings', 'dayStats', 'timeStats', 'period'));
    }

    // ========== HELPERS ==========

    protected function getStartDate(string $period): Carbon
    {
        return match($period) {
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            'all' => Carbon::createFromDate(2000, 1, 1),
            default => now()->startOfMonth(),
        };
    }

    protected function getGlobalStats($club, Carbon $startDate): array
    {
        $totalTrainings = $club->trainings()->where('date', '>=', $startDate)->count();
        $completedTrainings = $club->trainings()->where('date', '>=', $startDate)->where('status', 'completed')->count();
        
        return [
            'total_members' => $club->activeMembers()->count(),
            'total_trainings' => $totalTrainings,
            'completed_trainings' => $completedTrainings,
            'total_training_hours' => round($this->getTotalTrainingDuration($club, $startDate) / 60, 1),
            'avg_attendance_rate' => $this->getAverageAttendanceRate($club, $startDate),
            'active_players' => $club->players()->wherePivot('status', 'active')->count(),
            'active_coaches' => $club->coaches()->wherePivot('status', 'active')->count(),
        ];
    }

    protected function getAttendanceStats($club, Carbon $startDate): array
    {
        $trainings = $club->trainings()
            ->where('date', '>=', $startDate)
            ->where('status', 'completed')
            ->with('participants')
            ->get();

        $totalParticipants = 0;
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalExcused = 0;

        foreach ($trainings as $training) {
            foreach ($training->participants as $participant) {
                $totalParticipants++;
                match($participant->pivot->status) {
                    'present' => $totalPresent++,
                    'absent' => $totalAbsent++,
                    'excused' => $totalExcused++,
                    default => null,
                };
            }
        }

        return [
            'total' => $totalParticipants,
            'present' => $totalPresent,
            'absent' => $totalAbsent,
            'excused' => $totalExcused,
            'rate' => $totalParticipants > 0 ? round(($totalPresent / $totalParticipants) * 100) : 0,
        ];
    }

    protected function getTopMembers($club, Carbon $startDate, int $limit): \Illuminate\Support\Collection
    {
        $members = $club->activeMembers()->get();
        
        return $members->map(function ($member) use ($club, $startDate) {
            $stats = $this->getMemberStats($member, $club, $startDate);
            return (object) array_merge(['user' => $member], $stats);
        })
        ->sortByDesc('total_training_minutes')
        ->take($limit)
        ->values();
    }

    protected function getMemberStats(User $member, $club, Carbon $startDate): array
    {
        // Récupérer les entraînements auxquels le membre a participé
        $participations = DB::table('training_user')
            ->join('trainings', 'training_user.training_id', '=', 'trainings.id')
            ->where('training_user.user_id', $member->id)
            ->where('trainings.club_id', $club->id)
            ->where('trainings.date', '>=', $startDate)
            ->where('trainings.status', 'completed')
            ->get();

        $totalSessions = $participations->count();
        $presentSessions = $participations->where('status', 'present')->count();
        
        // Calculer le temps total d'entraînement
        $totalMinutes = 0;
        foreach ($participations->where('status', 'present') as $participation) {
            $training = Training::find($participation->training_id);
            if ($training) {
                $totalMinutes += $training->duration;
            }
        }

        // Récupérer le rôle du membre
        $membership = $club->members()->where('user_id', $member->id)->first();
        $role = $membership ? Role::find($membership->pivot->role_id) : null;

        return [
            'id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'role' => $role,
            'total_sessions' => $totalSessions,
            'present_sessions' => $presentSessions,
            'attendance_rate' => $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 0,
            'total_training_minutes' => $totalMinutes,
            'total_training_hours' => round($totalMinutes / 60, 1),
        ];
    }

    protected function getMemberDetailedStats(User $member, $club, Carbon $startDate): array
    {
        $baseStats = $this->getMemberStats($member, $club, $startDate);
        
        // Stats supplémentaires
        $allTimeStats = $this->getMemberStats($member, $club, Carbon::createFromDate(2000, 1, 1));
        
        // Dernière présence
        $lastPresence = DB::table('training_user')
            ->join('trainings', 'training_user.training_id', '=', 'trainings.id')
            ->where('training_user.user_id', $member->id)
            ->where('trainings.club_id', $club->id)
            ->where('training_user.status', 'present')
            ->orderBy('trainings.date', 'desc')
            ->first();

        // Série actuelle (consécutive)
        $streak = $this->calculateStreak($member, $club);

        return array_merge($baseStats, [
            'all_time_hours' => $allTimeStats['total_training_hours'],
            'all_time_sessions' => $allTimeStats['total_sessions'],
            'last_presence' => $lastPresence ? Carbon::parse($lastPresence->date) : null,
            'current_streak' => $streak,
        ]);
    }

    protected function calculateStreak(User $member, $club): int
    {
        $participations = DB::table('training_user')
            ->join('trainings', 'training_user.training_id', '=', 'trainings.id')
            ->where('training_user.user_id', $member->id)
            ->where('trainings.club_id', $club->id)
            ->where('trainings.status', 'completed')
            ->orderBy('trainings.date', 'desc')
            ->get();

        $streak = 0;
        foreach ($participations as $participation) {
            if ($participation->status === 'present') {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    protected function getMemberAttendanceHistory(User $member, $club, string $period): \Illuminate\Support\Collection
    {
        $startDate = $this->getStartDate($period);
        
        return DB::table('training_user')
            ->join('trainings', 'training_user.training_id', '=', 'trainings.id')
            ->where('training_user.user_id', $member->id)
            ->where('trainings.club_id', $club->id)
            ->where('trainings.date', '>=', $startDate)
            ->orderBy('trainings.date', 'desc')
            ->select('trainings.*', 'training_user.status as attendance_status')
            ->get();
    }

    protected function getMemberMonthlyProgress(User $member, $club): array
    {
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $participations = DB::table('training_user')
                ->join('trainings', 'training_user.training_id', '=', 'trainings.id')
                ->where('training_user.user_id', $member->id)
                ->where('trainings.club_id', $club->id)
                ->where('trainings.status', 'completed')
                ->whereBetween('trainings.date', [$startOfMonth, $endOfMonth])
                ->get();

            $totalMinutes = 0;
            foreach ($participations->where('status', 'present') as $p) {
                $training = Training::find($p->training_id);
                if ($training) {
                    $totalMinutes += $training->duration;
                }
            }

            $months[] = [
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'sessions' => $participations->where('status', 'present')->count(),
                'hours' => round($totalMinutes / 60, 1),
                'attendance_rate' => $participations->count() > 0 
                    ? round(($participations->where('status', 'present')->count() / $participations->count()) * 100)
                    : 0,
            ];
        }

        return $months;
    }

    protected function getTrainingEvolution($club, string $period): array
    {
        $data = [];
        $format = match($period) {
            'week' => 'D',
            'month' => 'd/m',
            'year' => 'M',
            default => 'd/m',
        };
        
        $iterations = match($period) {
            'week' => 7,
            'month' => 4,
            'year' => 12,
            default => 4,
        };

        for ($i = $iterations - 1; $i >= 0; $i--) {
            $date = match($period) {
                'week' => now()->subDays($i),
                'month' => now()->subWeeks($i),
                'year' => now()->subMonths($i),
                default => now()->subWeeks($i),
            };

            $startDate = match($period) {
                'week' => $date->copy()->startOfDay(),
                'month' => $date->copy()->startOfWeek(),
                'year' => $date->copy()->startOfMonth(),
                default => $date->copy()->startOfWeek(),
            };

            $endDate = match($period) {
                'week' => $date->copy()->endOfDay(),
                'month' => $date->copy()->endOfWeek(),
                'year' => $date->copy()->endOfMonth(),
                default => $date->copy()->endOfWeek(),
            };

            $trainings = $club->trainings()
                ->whereBetween('date', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count();

            $data[] = [
                'label' => $date->format($format),
                'value' => $trainings,
            ];
        }

        return $data;
    }

    protected function getTypeStats($club, Carbon $startDate): array
    {
        $types = ['training', 'match', 'event', 'meeting'];
        $stats = [];

        foreach ($types as $type) {
            $count = $club->trainings()
                ->where('date', '>=', $startDate)
                ->where('type', $type)
                ->count();

            $stats[] = [
                'type' => $type,
                'label' => match($type) {
                    'training' => 'Entraînements',
                    'match' => 'Matchs',
                    'event' => 'Événements',
                    'meeting' => 'Réunions',
                    default => $type,
                },
                'count' => $count,
                'color' => match($type) {
                    'training' => '#10B981',
                    'match' => '#3B82F6',
                    'event' => '#8B5CF6',
                    'meeting' => '#F59E0B',
                    default => '#6B7280',
                },
            ];
        }

        return $stats;
    }

    protected function getRoleDistribution($club): array
    {
        $roles = Role::all();
        $distribution = [];

        foreach ($roles as $role) {
            $count = $club->members()->wherePivot('role_id', $role->id)->count();
            if ($count > 0) {
                $distribution[] = [
                    'role' => $role->name,
                    'count' => $count,
                    'color' => $role->color,
                ];
            }
        }

        return $distribution;
    }

    protected function getTotalTrainingDuration($club, Carbon $startDate): int
    {
        $trainings = $club->trainings()
            ->where('date', '>=', $startDate)
            ->where('status', 'completed')
            ->get();

        return $trainings->sum(fn($t) => $t->duration);
    }

    protected function getAverageParticipants($club, Carbon $startDate): float
    {
        $trainings = $club->trainings()
            ->where('date', '>=', $startDate)
            ->where('status', 'completed')
            ->withCount('participants')
            ->get();

        if ($trainings->isEmpty()) return 0;

        return round($trainings->avg('participants_count'), 1);
    }

    protected function getAverageAttendanceRate($club, Carbon $startDate): float
    {
        $trainings = $club->trainings()
            ->where('date', '>=', $startDate)
            ->where('status', 'completed')
            ->with('participants')
            ->get();

        if ($trainings->isEmpty()) return 0;

        $rates = $trainings->map(fn($t) => $t->attendance_rate)->filter();
        
        return $rates->isEmpty() ? 0 : round($rates->avg());
    }

    protected function getDayOfWeekStats($club, Carbon $startDate): array
    {
        $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $stats = [];

        for ($i = 1; $i <= 7; $i++) {
            $count = $club->trainings()
                ->where('date', '>=', $startDate)
                ->whereRaw('DAYOFWEEK(date) = ?', [$i == 7 ? 1 : $i + 1])
                ->count();

            $stats[] = [
                'day' => $days[$i - 1],
                'count' => $count,
            ];
        }

        return $stats;
    }

    protected function getTimeSlotStats($club, Carbon $startDate): array
    {
        $slots = [
            ['label' => 'Matin (6h-12h)', 'start' => '06:00', 'end' => '12:00'],
            ['label' => 'Après-midi (12h-18h)', 'start' => '12:00', 'end' => '18:00'],
            ['label' => 'Soir (18h-22h)', 'start' => '18:00', 'end' => '22:00'],
        ];

        $stats = [];
        foreach ($slots as $slot) {
            $count = $club->trainings()
                ->where('date', '>=', $startDate)
                ->whereTime('start_time', '>=', $slot['start'])
                ->whereTime('start_time', '<', $slot['end'])
                ->count();

            $stats[] = [
                'label' => $slot['label'],
                'count' => $count,
            ];
        }

        return $stats;
    }
}

