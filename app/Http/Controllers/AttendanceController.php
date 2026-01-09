<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Training;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Récupère le club courant
     */
    protected function getCurrentClub(): ?Club
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Liste des présences (accessible à tous les rôles)
     */
    public function index(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('welcome')->with('error', 'Aucun club trouvé.');
        }

        $user = auth()->user();
        $userRole = $user->roleInClub($club);
        $isStaff = $userRole && $userRole->isStaffRole();

        // Filtres
        $filter = $request->get('filter', 'today');
        $trainingId = $request->get('training');
        $memberId = $request->get('member');

        // Récupérer les séances selon le filtre
        $query = Training::where('club_id', $club->id)
            ->with(['participants', 'coach'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($filter === 'today') {
            $query->whereDate('date', Carbon::today());
        } elseif ($filter === 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($filter === 'month') {
            $query->whereMonth('date', Carbon::now()->month)
                  ->whereYear('date', Carbon::now()->year);
        }

        if ($trainingId) {
            $query->where('id', $trainingId);
        }

        // Pour les joueurs, ne montrer que leurs propres séances
        if (!$isStaff) {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $trainings = $query->paginate(10);

        // Séances du jour avec statut de pointage
        $todayTrainings = Training::where('club_id', $club->id)
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time')
            ->get();

        // Stats du jour
        $todayStats = [
            'total_trainings' => $todayTrainings->count(),
            'total_participants' => $todayTrainings->sum(fn($t) => $t->participants->count()),
            'present' => $todayTrainings->sum(fn($t) => $t->participants->where('pivot.status', 'present')->count()),
            'absent' => $todayTrainings->sum(fn($t) => $t->participants->where('pivot.status', 'absent')->count()),
        ];

        // Liste des membres pour filtre (staff uniquement)
        $members = $isStaff ? $club->members()->orderBy('name')->get() : collect();

        // Liste des séances pour filtre
        $trainingsList = Training::where('club_id', $club->id)
            ->whereDate('date', '>=', Carbon::now()->subMonth())
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.index', compact(
            'club', 'trainings', 'todayTrainings', 'todayStats', 
            'filter', 'isStaff', 'members', 'trainingsList', 'trainingId', 'memberId'
        ));
    }

    /**
     * Check-in rapide (joueur marque sa présence)
     */
    public function checkIn(Request $request, Training $training)
    {
        $club = $this->getCurrentClub();
        $user = auth()->user();

        // Vérifier que la séance appartient au club
        if ($training->club_id !== $club->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Séance non trouvée.'], 404);
            }
            return back()->with('error', 'Séance non trouvée.');
        }

        // Vérifier que la séance est aujourd'hui
        if (!$training->date->isToday()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vous ne pouvez pointer que pour les séances du jour.'], 400);
            }
            return back()->with('error', 'Vous ne pouvez pointer que pour les séances du jour.');
        }

        // Vérifier que le joueur est inscrit à cette séance
        $participation = $training->participants()->where('user_id', $user->id)->first();
        
        if (!$participation) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Vous n\'êtes pas inscrit à cette séance.'], 403);
            }
            return back()->with('error', 'Vous n\'êtes pas inscrit à cette séance.');
        }

        // Vérifier que le statut n'est pas déjà marqué (pas de double pointage)
        if (in_array($participation->pivot->status, ['present', 'late', 'absent'])) {
            $statusLabels = [
                'present' => 'Présent',
                'late' => 'En retard', 
                'absent' => 'Absent'
            ];
            $currentStatus = $statusLabels[$participation->pivot->status] ?? $participation->pivot->status;
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => "Vous avez déjà pointé pour cette séance ({$currentStatus}).",
                    'already_checked' => true,
                    'status' => $participation->pivot->status
                ], 400);
            }
            return back()->with('info', "Vous avez déjà pointé pour cette séance ({$currentStatus}).");
        }

        // Calculer les horaires
        $now = Carbon::now();
        $startTime = Carbon::parse($training->date->format('Y-m-d') . ' ' . $training->start_time);
        $endTime = Carbon::parse($training->date->format('Y-m-d') . ' ' . $training->end_time);

        // Déterminer le statut selon l'heure de pointage
        if ($now->gt($endTime)) {
            // Pointage après la fin de la séance → Absent
            $status = 'absent';
            $message = 'La séance est terminée. Vous êtes marqué absent.';
        } elseif ($now->gt($startTime)) {
            // Pointage après le début mais avant la fin → En retard
            $status = 'late';
            $message = 'Présence enregistrée (en retard)';
        } else {
            // Pointage avant ou à l'heure → Présent
            $status = 'present';
            $message = 'Présence enregistrée !';
        }

        // Marquer la présence
        $training->participants()->updateExistingPivot($user->id, [
            'status' => $status,
            'arrived_at' => $now,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status,
            ]);
        }

        return back()->with($status === 'absent' ? 'warning' : 'success', $message);
    }

    /**
     * Marquer la présence d'un participant (staff)
     */
    public function markAttendance(Request $request, Training $training, User $participant)
    {
        $club = $this->getCurrentClub();
        $user = auth()->user();
        $userRole = $user->roleInClub($club);

        // Vérifier les permissions
        if (!$userRole || !$userRole->isStaffRole()) {
            return back()->with('error', 'Action non autorisée.');
        }

        // Vérifier que la séance appartient au club
        if ($training->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:registered,present,absent,excused,late'],
        ]);

        $training->participants()->updateExistingPivot($participant->id, [
            'status' => $validated['status'],
            'arrived_at' => in_array($validated['status'], ['present', 'late']) ? Carbon::now() : null,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour',
            ]);
        }

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Marquer tous présents (staff)
     */
    public function markAllPresent(Training $training)
    {
        $club = $this->getCurrentClub();
        $user = auth()->user();
        $userRole = $user->roleInClub($club);

        if (!$userRole || !$userRole->isStaffRole()) {
            return back()->with('error', 'Action non autorisée.');
        }

        if ($training->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        foreach ($training->participants as $participant) {
            if ($participant->pivot->status === 'registered') {
                $training->participants()->updateExistingPivot($participant->id, [
                    'status' => 'present',
                    'arrived_at' => Carbon::now(),
                ]);
            }
        }

        return back()->with('success', 'Tous les participants ont été marqués présents.');
    }

    /**
     * Export des présences (staff)
     */
    public function export(Request $request)
    {
        $club = $this->getCurrentClub();
        $user = auth()->user();
        $userRole = $user->roleInClub($club);

        if (!$userRole || !$userRole->isStaffRole()) {
            return back()->with('error', 'Action non autorisée.');
        }

        // TODO: Implémenter l'export CSV/PDF
        return back()->with('info', 'Fonctionnalité bientôt disponible.');
    }
}

