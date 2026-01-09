<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PlanningController extends Controller
{
    /**
     * Récupère le club courant de l'utilisateur
     */
    protected function getCurrentClub()
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Affiche le planning
     */
    public function index(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $view = $request->get('view', 'list'); // list, calendar, week
        $filter = $request->get('filter', 'upcoming'); // upcoming, past, all
        $type = $request->get('type', ''); // training, match, event, meeting

        $query = $club->trainings()->with(['coach', 'participants']);

        // Filtres
        if ($filter === 'upcoming') {
            $query->upcoming();
        } elseif ($filter === 'past') {
            $query->past();
        } elseif ($filter === 'week') {
            $query->thisWeek()->orderBy('date')->orderBy('start_time');
        } elseif ($filter === 'month') {
            $query->thisMonth()->orderBy('date')->orderBy('start_time');
        } else {
            $query->orderBy('date', 'desc')->orderBy('start_time');
        }

        if ($type) {
            $query->byType($type);
        }

        $trainings = $query->paginate(10);

        // Stats
        $stats = [
            'total_month' => $club->trainings()->thisMonth()->count(),
            'upcoming' => $club->trainings()->upcoming()->count(),
            'this_week' => $club->trainings()->thisWeek()->count(),
            'completed_month' => $club->trainings()->thisMonth()->where('status', Training::STATUS_COMPLETED)->count(),
        ];

        // Séances du jour
        $todayTrainings = $club->trainings()
            ->whereDate('date', today())
            ->orderBy('start_time')
            ->get();

        // Données pour le calendrier FullCalendar (3 mois avant/après)
        $calendarEvents = $club->trainings()
            ->whereBetween('date', [
                now()->subMonths(3)->startOfMonth(),
                now()->addMonths(3)->endOfMonth()
            ])
            ->get()
            ->map(function ($training) {
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
                        'participantsCount' => $training->participants->count(),
                    ],
                ];
            });

        return view('planning.index', compact('club', 'trainings', 'stats', 'todayTrainings', 'view', 'filter', 'type', 'calendarEvents'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $coaches = $club->coaches()->get();
        $members = $club->activeMembers()->get();

        return view('planning.create', compact('club', 'coaches', 'members'));
    }

    /**
     * Enregistre une nouvelle séance
     */
    public function store(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['training', 'match', 'event', 'meeting'])],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'coach_id' => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'max:7'],
            'notes' => ['nullable', 'string'],
            'participants' => ['nullable', 'array'],
            'participants.*' => ['exists:users,id'],
        ]);

        $training = $club->trainings()->create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'] ?? null,
            'address' => $validated['address'] ?? null,
            'coach_id' => $validated['coach_id'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'color' => $validated['color'] ?? $this->getDefaultColor($validated['type']),
            'notes' => $validated['notes'] ?? null,
            'status' => Training::STATUS_SCHEDULED,
        ]);

        // Ajouter les participants sélectionnés
        if (!empty($validated['participants'])) {
            $training->participants()->attach($validated['participants'], ['status' => 'registered']);
        }

        return redirect()->route('planning.show', $training)
            ->with('success', 'Séance créée avec succès !');
    }

    /**
     * Affiche une séance
     */
    public function show(Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return redirect()->route('planning.index')->with('error', 'Séance non trouvée.');
        }

        $planning->load(['coach', 'participants']);
        
        // Membres non inscrits
        $registeredIds = $planning->participants->pluck('id')->toArray();
        $availableMembers = $club->activeMembers()
            ->whereNotIn('users.id', $registeredIds)
            ->get();

        return view('planning.show', compact('club', 'planning', 'availableMembers'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return redirect()->route('planning.index')->with('error', 'Séance non trouvée.');
        }

        $planning->load(['coach', 'participants']);
        $coaches = $club->coaches()->get();
        $members = $club->activeMembers()->get();

        return view('planning.edit', compact('club', 'planning', 'coaches', 'members'));
    }

    /**
     * Met à jour une séance
     */
    public function update(Request $request, Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return redirect()->route('planning.index')->with('error', 'Séance non trouvée.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['training', 'match', 'event', 'meeting'])],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'coach_id' => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'color' => ['nullable', 'string', 'max:7'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', Rule::in(['scheduled', 'ongoing', 'completed', 'cancelled'])],
            'participants' => ['nullable', 'array'],
            'participants.*' => ['exists:users,id'],
        ]);

        $planning->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'] ?? null,
            'address' => $validated['address'] ?? null,
            'coach_id' => $validated['coach_id'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'color' => $validated['color'] ?? $planning->color,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? $planning->status,
        ]);

        // Mettre à jour les participants
        if (isset($validated['participants'])) {
            $planning->participants()->sync(
                collect($validated['participants'])->mapWithKeys(fn($id) => [$id => ['status' => 'registered']])->toArray()
            );
        }

        return redirect()->route('planning.show', $planning)
            ->with('success', 'Séance mise à jour avec succès !');
    }

    /**
     * Supprime une séance
     */
    public function destroy(Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return redirect()->route('planning.index')->with('error', 'Séance non trouvée.');
        }

        $planning->delete();

        return redirect()->route('planning.index')
            ->with('success', 'Séance supprimée avec succès !');
    }

    /**
     * Ajoute un participant à une séance
     */
    public function addParticipant(Request $request, Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if ($planning->isFull()) {
            return back()->with('error', 'La séance est complète.');
        }

        $planning->participants()->attach($validated['user_id'], ['status' => 'registered']);

        return back()->with('success', 'Participant ajouté !');
    }

    /**
     * Retire un participant d'une séance
     */
    public function removeParticipant(Training $planning, User $user)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        $planning->participants()->detach($user->id);

        return back()->with('success', 'Participant retiré !');
    }

    /**
     * Met à jour le statut de présence d'un participant
     */
    public function updateAttendance(Request $request, Training $planning, User $user)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['registered', 'present', 'absent', 'excused', 'late'])],
        ]);

        $updateData = ['status' => $validated['status']];

        // Si présent ou en retard, enregistrer l'heure d'arrivée
        if (in_array($validated['status'], ['present', 'late']) && !$planning->participants()->where('user_id', $user->id)->first()?->pivot?->arrived_at) {
            $updateData['arrived_at'] = now();
        }

        $planning->participants()->updateExistingPivot($user->id, $updateData);

        return back()->with('success', 'Présence mise à jour !');
    }

    /**
     * Retourne la couleur par défaut selon le type
     */
    protected function getDefaultColor(string $type): string
    {
        return match($type) {
            'training' => '#10B981',
            'match' => '#3B82F6',
            'event' => '#8B5CF6',
            'meeting' => '#F59E0B',
            default => '#6B7280',
        };
    }

    /**
     * Duplique une séance
     */
    public function duplicate(Training $planning)
    {
        $club = $this->getCurrentClub();
        
        if (!$club || $planning->club_id !== $club->id) {
            return back()->with('error', 'Séance non trouvée.');
        }

        $newTraining = $planning->replicate();
        $newTraining->date = Carbon::parse($planning->date)->addWeek();
        $newTraining->status = Training::STATUS_SCHEDULED;
        $newTraining->save();

        // Copier les participants
        foreach ($planning->participants as $participant) {
            $newTraining->participants()->attach($participant->id, ['status' => 'registered']);
        }

        return redirect()->route('planning.edit', $newTraining)
            ->with('success', 'Séance dupliquée ! Vous pouvez modifier les détails.');
    }
}

