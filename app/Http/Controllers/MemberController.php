<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Récupère le club de l'utilisateur connecté
     */
    protected function getCurrentClub()
    {
        $user = auth()->user();
        return $user->getPrimaryClub() ?? $user->ownedClubs()->first();
    }

    /**
     * Affiche la liste des membres
     */
    public function index(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $query = $club->members()->with('clubs');
        
        // Filtre par rôle
        if ($request->filled('role')) {
            $role = Role::where('slug', $request->role)->first();
            if ($role) {
                $query->wherePivot('role_id', $role->id);
            }
        }
        
        // Filtre par statut
        if ($request->filled('status')) {
            $query->wherePivot('status', $request->status);
        }
        
        // Recherche par nom ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('name')->paginate(20);
        $roles = Role::orderBy('level', 'desc')->get();
        
        // Stats
        $stats = [
            'total' => $club->members()->count(),
            'active' => $club->activeMembers()->count(),
            'pending' => $club->pendingMembers()->count(),
            'players' => $club->players()->count(),
            'coaches' => $club->coaches()->count(),
        ];

        return view('members.index', compact('club', 'members', 'roles', 'stats'));
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

        $roles = Role::where('slug', '!=', Role::OWNER)
            ->orderBy('level', 'desc')
            ->get();

        return view('members.create', compact('club', 'roles'));
    }

    /**
     * Enregistre un nouveau membre
     */
    public function store(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'role_id' => 'required|exists:roles,id',
            'jersey_number' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:50',
            'joined_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Vérifie si l'utilisateur existe déjà
        $user = User::where('email', $validated['email'])->first();
        
        if (!$user) {
            // Crée un nouvel utilisateur avec un mot de passe temporaire
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'password' => Hash::make(Str::random(16)), // Mot de passe temporaire
            ]);
        }

        // Vérifie si l'utilisateur n'est pas déjà membre du club
        if ($club->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà membre du club.'])->withInput();
        }

        // Ajoute l'utilisateur au club
        $club->members()->attach($user->id, [
            'role_id' => $validated['role_id'],
            'jersey_number' => $validated['jersey_number'],
            'position' => $validated['position'],
            'license_number' => $validated['license_number'],
            'joined_at' => $validated['joined_at'] ?? now(),
            'notes' => $validated['notes'],
            'status' => 'active',
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Membre ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un membre
     */
    public function show(User $member)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $membership = $club->members()->where('user_id', $member->id)->first();
        
        if (!$membership) {
            return redirect()->route('members.index')->with('error', 'Membre non trouvé.');
        }

        $role = Role::find($membership->pivot->role_id);

        return view('members.show', compact('club', 'member', 'membership', 'role'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(User $member)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $membership = $club->members()->where('user_id', $member->id)->first();
        
        if (!$membership) {
            return redirect()->route('members.index')->with('error', 'Membre non trouvé.');
        }

        $roles = Role::where('slug', '!=', Role::OWNER)
            ->orderBy('level', 'desc')
            ->get();

        // Si c'est le propriétaire, on l'ajoute à la liste
        if ($membership->pivot->role_id == Role::findBySlug(Role::OWNER)?->id) {
            $roles = Role::orderBy('level', 'desc')->get();
        }

        return view('members.edit', compact('club', 'member', 'membership', 'roles'));
    }

    /**
     * Met à jour un membre
     */
    public function update(Request $request, User $member)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $membership = $club->members()->where('user_id', $member->id)->first();
        
        if (!$membership) {
            return redirect()->route('members.index')->with('error', 'Membre non trouvé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'role_id' => 'required|exists:roles,id',
            'jersey_number' => 'nullable|string|max:10',
            'position' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:50',
            'joined_at' => 'nullable|date',
            'license_expires_at' => 'nullable|date',
            'status' => 'required|in:active,inactive,suspended,pending',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Met à jour l'utilisateur
        $member->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'birth_date' => $validated['birth_date'],
        ]);

        // Met à jour la relation avec le club
        $club->members()->updateExistingPivot($member->id, [
            'role_id' => $validated['role_id'],
            'jersey_number' => $validated['jersey_number'],
            'position' => $validated['position'],
            'license_number' => $validated['license_number'],
            'joined_at' => $validated['joined_at'],
            'license_expires_at' => $validated['license_expires_at'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Membre mis à jour avec succès.');
    }

    /**
     * Supprime un membre du club
     */
    public function destroy(User $member)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        // Empêche la suppression du propriétaire
        if ($member->isOwnerOf($club)) {
            return back()->with('error', 'Impossible de supprimer le propriétaire du club.');
        }

        // Détache le membre du club (ne supprime pas l'utilisateur)
        $club->members()->detach($member->id);

        return redirect()->route('members.index')
            ->with('success', 'Membre retiré du club.');
    }

    /**
     * Change le statut d'un membre
     */
    public function updateStatus(Request $request, User $member)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return response()->json(['error' => 'Club non trouvé'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended,pending',
        ]);

        $club->members()->updateExistingPivot($member->id, [
            'status' => $validated['status'],
        ]);

        return response()->json(['success' => true, 'status' => $validated['status']]);
    }
}

