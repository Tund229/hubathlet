<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClubSettingsController extends Controller
{
    /**
     * Récupère le club courant de l'utilisateur
     */
    protected function getCurrentClub()
    {
        return auth()->user()->clubs()->first();
    }

    /**
     * Page principale - Mon club
     */
    public function index()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        // Créer les settings si inexistants
        if (!$club->settings) {
            $club->settings()->create([
                'app_name' => $club->name,
                'timezone' => 'Europe/Paris',
                'language' => 'fr',
            ]);
            $club->refresh();
        }

        // Stats du club
        $stats = [
            'members' => $club->members()->count(),
            'active_members' => $club->activeMembers()->count(),
            'trainings' => $club->trainings()->count(),
            'trainings_this_month' => $club->trainings()->thisMonth()->count(),
        ];

        return view('club.index', compact('club', 'stats'));
    }

    /**
     * Modifier les informations du club
     */
    public function edit()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        return view('club.edit', compact('club'));
    }

    /**
     * Mettre à jour les informations du club
     */
    public function update(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        // Upload logo si fourni
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($club->logo) {
                Storage::disk('public')->delete($club->logo);
            }
            
            $path = $request->file('logo')->store('clubs/logos', 'public');
            $validated['logo'] = $path;
        }

        // Mettre à jour le slug si le nom change
        if ($validated['name'] !== $club->name) {
            $validated['slug'] = Club::generateSlug($validated['name']);
        }

        $club->update($validated);

        return redirect()->route('club.index')->with('success', 'Informations du club mises à jour !');
    }

    /**
     * Page de personnalisation (couleurs, thème)
     */
    public function customization()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        // Créer les settings si inexistants
        if (!$club->settings) {
            $club->settings()->create([
                'app_name' => $club->name,
                'timezone' => 'Europe/Paris',
                'language' => 'fr',
            ]);
            $club->refresh();
        }

        return view('club.customization', compact('club'));
    }

    /**
     * Mettre à jour la personnalisation
     */
    public function updateCustomization(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $validated = $request->validate([
            'primary_color' => ['nullable', 'string', 'max:7'],
            'secondary_color' => ['nullable', 'string', 'max:7'],
            'accent_color' => ['nullable', 'string', 'max:7'],
            'app_theme_color' => ['nullable', 'string', 'max:7'],
            'app_background_color' => ['nullable', 'string', 'max:7'],
        ]);

        // Mettre à jour le club
        $club->update([
            'primary_color' => $validated['primary_color'] ?? $club->primary_color,
            'secondary_color' => $validated['secondary_color'] ?? $club->secondary_color,
            'accent_color' => $validated['accent_color'] ?? $club->accent_color,
        ]);

        // Mettre à jour les settings
        if ($club->settings) {
            $club->settings->update([
                'app_theme_color' => $validated['app_theme_color'] ?? $club->settings->app_theme_color,
                'app_background_color' => $validated['app_background_color'] ?? $club->settings->app_background_color,
            ]);
        }

        return redirect()->route('club.customization')->with('success', 'Personnalisation mise à jour !');
    }

    /**
     * Page des paramètres
     */
    public function settings()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        // Créer les settings si inexistants
        if (!$club->settings) {
            $club->settings()->create([
                'app_name' => $club->name,
                'timezone' => 'Europe/Paris',
                'language' => 'fr',
            ]);
            $club->refresh();
        }

        $timezones = ClubSetting::getTimezones();
        $languages = ClubSetting::getLanguages();

        return view('club.settings', compact('club', 'timezones', 'languages'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateSettings(Request $request)
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_short_name' => ['nullable', 'string', 'max:50'],
            'timezone' => ['required', 'string'],
            'language' => ['required', 'string', 'max:5'],
            'push_notifications' => ['boolean'],
            'public_registration' => ['boolean'],
        ]);

        // Créer ou mettre à jour les settings
        $club->settings()->updateOrCreate(
            ['club_id' => $club->id],
            [
                'app_name' => $validated['app_name'],
                'app_short_name' => $validated['app_short_name'] ?? null,
                'timezone' => $validated['timezone'],
                'language' => $validated['language'],
                'push_notifications' => $request->boolean('push_notifications'),
                'public_registration' => $request->boolean('public_registration'),
            ]
        );

        return redirect()->route('club.settings')->with('success', 'Paramètres mis à jour !');
    }

    /**
     * Supprimer le logo
     */
    public function deleteLogo()
    {
        $club = $this->getCurrentClub();
        
        if (!$club) {
            return redirect()->route('dashboard')->with('error', 'Aucun club trouvé.');
        }

        if ($club->logo) {
            Storage::disk('public')->delete($club->logo);
            $club->update(['logo' => null]);
        }

        return redirect()->route('club.edit')->with('success', 'Logo supprimé !');
    }
}

