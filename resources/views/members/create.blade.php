@extends('layouts.dashboard')

@section('title', 'Ajouter un membre')
@section('description', 'Ajoutez un nouveau membre à votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('members.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Nouveau membre</h1>
                <p class="text-sm text-slate-500">Remplissez les informations</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('members.index') }}" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors">
                Annuler
            </a>
            <button type="submit" form="member-form" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer
            </button>
        </div>
    </div>

    <form id="member-form" method="POST" action="{{ route('members.store') }}">
        @csrf
        
        <div class="grid lg:grid-cols-12 gap-6">
            
            <!-- Colonne 1 : Rôle + Infos club -->
            <div class="lg:col-span-3 space-y-5">
                
                <!-- Role Selection -->
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Type de membre</h2>
                    
                    <div class="space-y-1.5">
                        @foreach($roles as $role)
                            <label class="relative flex items-center gap-2.5 p-2.5 rounded-xl border border-transparent cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                <input type="radio" name="role_id" value="{{ $role->id }}" data-slug="{{ $role->slug }}" data-color="{{ $role->color }}" class="sr-only peer role-radio" {{ old('role_id') == $role->id ? 'checked' : '' }} {{ $loop->first && !old('role_id') ? 'checked' : '' }}>
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $role->color }}15;">
                                    @if($role->slug == 'coach')
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    @elseif($role->slug == 'player')
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @elseif($role->slug == 'admin')
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    @elseif($role->slug == 'moderator')
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    @elseif($role->slug == 'parent')
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="font-semibold text-slate-700 text-sm role-name">{{ $role->name }}</span>
                                <div class="ml-auto w-4 h-4 rounded-full border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role_id')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Infos Club -->
                <div id="club-fields" class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm transition-all duration-300">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3" id="club-fields-title">Infos sportives</h2>
                    
                    <div class="space-y-3">
                        <!-- Jersey + Position -->
                        <div id="field-jersey" class="grid grid-cols-2 gap-2">
                            <div>
                                <label for="jersey_number" class="block text-xs font-medium text-slate-600 mb-1">N° Maillot</label>
                                <input type="text" id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}"
                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-center font-bold"
                                    placeholder="10">
                            </div>
                            <div>
                                <label for="joined_at" class="block text-xs font-medium text-slate-600 mb-1">Adhésion</label>
                                <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', date('Y-m-d')) }}"
                                    class="w-full px-2 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all">
                            </div>
                        </div>
                        
                        <!-- Position -->
                        <div id="field-position">
                            <label for="position" class="block text-xs font-medium text-slate-600 mb-1">Position</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                placeholder="Attaquant...">
                        </div>
                        
                        <!-- License -->
                        <div id="field-license">
                            <label for="license_number" class="block text-xs font-medium text-slate-600 mb-1">N° Licence</label>
                            <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                placeholder="LIC-2024-001">
                        </div>

                        <!-- Message pour les rôles sans champs sportifs -->
                        <div id="no-sport-fields" class="hidden text-center py-4">
                            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-slate-400" id="no-sport-message">Pas d'infos sportives</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne 2 : Infos personnelles + Notes -->
            <div class="lg:col-span-5 space-y-5">
                
                <!-- Personal Info -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Informations</h2>
                    
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-medium text-slate-600 mb-1">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('name') border-red-300 bg-red-50 @enderror"
                                placeholder="Jean Dupont">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Email -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="email" class="block text-xs font-medium text-slate-600 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('email') border-red-300 bg-red-50 @enderror"
                                    placeholder="email@exemple.com">
                            </div>
                            
                            <!-- Phone -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="phone" class="block text-xs font-medium text-slate-600 mb-1">Téléphone</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                    placeholder="06 12 34 56 78">
                            </div>
                        </div>
                        
                        <!-- Birth Date -->
                        <div id="field-birthdate">
                            <label for="birth_date" class="block text-xs font-medium text-slate-600 mb-1">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Notes internes</h2>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all resize-none text-sm"
                        placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <!-- Colonne 3 : Aperçu du profil -->
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-4">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aperçu</h2>
                    
                    <!-- Preview Card - Dossard pour joueur -->
                    <div id="preview-player" class="relative">
                        <!-- Maillot / Dossard -->
                        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl" id="jersey-card">
                            <!-- Background gradient -->
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700" id="jersey-bg"></div>
                            
                            <!-- Pattern overlay -->
                            <div class="absolute inset-0 opacity-10">
                                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                                    <defs>
                                        <pattern id="stripes" patternUnits="userSpaceOnUse" width="10" height="10" patternTransform="rotate(45)">
                                            <line x1="0" y="0" x2="0" y2="10" stroke="white" stroke-width="2"/>
                                        </pattern>
                                    </defs>
                                    <rect width="100" height="100" fill="url(#stripes)"/>
                                </svg>
                            </div>
                            
                            <!-- Jersey content -->
                            <div class="relative p-6 pt-8 pb-10">
                                <!-- Club badge -->
                                <div class="absolute top-4 left-4 w-10 h-10 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                
                                <!-- Jersey number -->
                                <div class="text-center mt-4">
                                    <div class="text-[80px] sm:text-[100px] font-black text-white leading-none tracking-tighter drop-shadow-lg" id="preview-number" style="text-shadow: 3px 3px 0 rgba(0,0,0,0.2);">
                                        ?
                                    </div>
                                </div>
                                
                                <!-- Name on jersey -->
                                <div class="text-center mt-2">
                                    <div class="inline-block bg-black/20 backdrop-blur-sm rounded-lg px-4 py-1.5">
                                        <span class="text-white font-black text-lg tracking-wider uppercase" id="preview-jersey-name">NOM</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Player info card -->
                        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-lg -mt-4 mx-3 relative z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center" id="preview-avatar" style="background-color: rgba(16, 185, 129, 0.15);">
                                    <span class="text-lg font-black" id="preview-initials" style="color: #10B981;">?</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-slate-900 truncate" id="preview-name">Nouveau membre</div>
                                    <div class="text-sm text-slate-500 truncate" id="preview-email">email@exemple.com</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold" id="preview-role-badge" style="background-color: rgba(16, 185, 129, 0.1); color: #10B981;">
                                    Joueur
                                </span>
                                <span class="text-xs text-slate-400" id="preview-position">—</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Card - Staff (non-joueur) -->
                    <div id="preview-staff" class="hidden">
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] p-6 relative overflow-hidden shadow-2xl">
                            <!-- Glow effect -->
                            <div class="absolute top-0 right-0 w-40 h-40 rounded-full blur-3xl" id="staff-glow" style="background-color: rgba(16, 185, 129, 0.2);"></div>
                            
                            <div class="relative">
                                <!-- Avatar -->
                                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4" id="staff-avatar" style="background-color: rgba(16, 185, 129, 0.2);">
                                    <span class="text-3xl font-black" id="staff-initials" style="color: #10B981;">?</span>
                                </div>
                                
                                <!-- Name -->
                                <div class="text-center">
                                    <div class="text-xl font-black text-white mb-1" id="staff-name">Nouveau membre</div>
                                    <div class="text-slate-400 text-sm mb-4" id="staff-email">email@exemple.com</div>
                                    
                                    <!-- Role badge -->
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold" id="staff-role-badge" style="background-color: rgba(16, 185, 129, 0.2); color: #10B981;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="staff-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span id="staff-role-text">Administrateur</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hint -->
                    <p class="text-xs text-slate-400 text-center">
                        Cet aperçu montre comment le profil apparaîtra
                    </p>
                </div>
            </div>
        </div>

        <!-- Mobile Actions -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 flex gap-3 z-40">
            <a href="{{ route('members.index') }}" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold text-center text-sm">
                Annuler
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer
            </button>
        </div>
        
        <div class="sm:hidden h-20"></div>
    </form>
</div>

@push('scripts')
<script>
    // Configuration des champs par rôle
    const roleConfig = {
        'player': { jersey: true, position: true, license: true, birthdate: true, isPlayer: true },
        'coach': { jersey: false, position: false, license: true, birthdate: false, isPlayer: false },
        'admin': { jersey: false, position: false, license: false, birthdate: false, isPlayer: false },
        'moderator': { jersey: false, position: false, license: false, birthdate: false, isPlayer: false },
        'parent': { jersey: false, position: false, license: false, birthdate: false, isPlayer: false },
        'guest': { jersey: false, position: false, license: false, birthdate: false, isPlayer: false }
    };

    // Éléments DOM
    const elements = {
        fieldJersey: document.getElementById('field-jersey'),
        fieldPosition: document.getElementById('field-position'),
        fieldLicense: document.getElementById('field-license'),
        fieldBirthdate: document.getElementById('field-birthdate'),
        noSportFields: document.getElementById('no-sport-fields'),
        noSportMessage: document.getElementById('no-sport-message'),
        clubFieldsTitle: document.getElementById('club-fields-title'),
        
        // Preview player
        previewPlayer: document.getElementById('preview-player'),
        jerseyBg: document.getElementById('jersey-bg'),
        previewNumber: document.getElementById('preview-number'),
        previewJerseyName: document.getElementById('preview-jersey-name'),
        previewAvatar: document.getElementById('preview-avatar'),
        previewInitials: document.getElementById('preview-initials'),
        previewName: document.getElementById('preview-name'),
        previewEmail: document.getElementById('preview-email'),
        previewRoleBadge: document.getElementById('preview-role-badge'),
        previewPosition: document.getElementById('preview-position'),
        
        // Preview staff
        previewStaff: document.getElementById('preview-staff'),
        staffGlow: document.getElementById('staff-glow'),
        staffAvatar: document.getElementById('staff-avatar'),
        staffInitials: document.getElementById('staff-initials'),
        staffName: document.getElementById('staff-name'),
        staffEmail: document.getElementById('staff-email'),
        staffRoleBadge: document.getElementById('staff-role-badge'),
        staffRoleText: document.getElementById('staff-role-text')
    };

    // Inputs
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const jerseyInput = document.getElementById('jersey_number');
    const positionInput = document.getElementById('position');

    function getInitials(name) {
        if (!name) return '?';
        return name.trim().split(' ').slice(0, 2).map(w => w[0]?.toUpperCase() || '').join('');
    }

    function getLastName(name) {
        if (!name) return 'NOM';
        const parts = name.trim().split(' ');
        return parts[parts.length - 1].toUpperCase();
    }

    function updateFieldsForRole(slug, color, roleName) {
        const config = roleConfig[slug] || roleConfig['player'];
        
        // Mise à jour du titre
        const titles = {
            'player': 'Infos sportives',
            'coach': 'Infos coach',
            'admin': 'Infos admin',
            'moderator': 'Infos modérateur',
            'parent': 'Infos parent',
            'guest': 'Infos invité'
        };
        elements.clubFieldsTitle.textContent = titles[slug] || 'Infos';
        
        // Afficher/masquer les champs
        const hasAnyField = config.jersey || config.position || config.license;
        
        if (hasAnyField) {
            elements.noSportFields.classList.add('hidden');
            elements.fieldJersey.classList.toggle('hidden', !config.jersey);
            elements.fieldPosition.classList.toggle('hidden', !config.position);
            elements.fieldLicense.classList.toggle('hidden', !config.license);
        } else {
            elements.noSportFields.classList.remove('hidden');
            elements.fieldJersey.classList.add('hidden');
            elements.fieldPosition.classList.add('hidden');
            elements.fieldLicense.classList.add('hidden');
        }
        
        elements.fieldBirthdate.classList.toggle('hidden', !config.birthdate);
        
        // Afficher le bon aperçu
        if (config.isPlayer) {
            elements.previewPlayer.classList.remove('hidden');
            elements.previewStaff.classList.add('hidden');
            
            // Mettre à jour les couleurs du maillot
            elements.jerseyBg.style.background = `linear-gradient(135deg, ${color}, ${color}dd, ${color}bb)`;
            elements.previewAvatar.style.backgroundColor = color + '15';
            elements.previewInitials.style.color = color;
            elements.previewRoleBadge.style.backgroundColor = color + '15';
            elements.previewRoleBadge.style.color = color;
            elements.previewRoleBadge.textContent = roleName;
        } else {
            elements.previewPlayer.classList.add('hidden');
            elements.previewStaff.classList.remove('hidden');
            
            // Mettre à jour les couleurs staff
            elements.staffGlow.style.backgroundColor = color + '30';
            elements.staffAvatar.style.backgroundColor = color + '20';
            elements.staffInitials.style.color = color;
            elements.staffRoleBadge.style.backgroundColor = color + '20';
            elements.staffRoleBadge.style.color = color;
            elements.staffRoleText.textContent = roleName;
        }
    }

    function updatePreview() {
        const name = nameInput.value || 'Nouveau membre';
        const email = emailInput.value || 'email@exemple.com';
        const jersey = jerseyInput.value || '?';
        const position = positionInput.value || '—';
        const initials = getInitials(nameInput.value);
        const lastName = getLastName(nameInput.value);
        
        // Player preview
        elements.previewNumber.textContent = jersey;
        elements.previewJerseyName.textContent = lastName;
        elements.previewInitials.textContent = initials;
        elements.previewName.textContent = name;
        elements.previewEmail.textContent = email;
        elements.previewPosition.textContent = position;
        
        // Staff preview
        elements.staffInitials.textContent = initials;
        elements.staffName.textContent = name;
        elements.staffEmail.textContent = email;
    }

    // Event listeners
    nameInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);
    jerseyInput.addEventListener('input', updatePreview);
    positionInput.addEventListener('input', updatePreview);
    
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            const slug = radio.dataset.slug;
            const color = radio.dataset.color;
            const roleName = radio.closest('label').querySelector('.role-name').textContent;
            updateFieldsForRole(slug, color, roleName);
            updatePreview();
        });
    });

    // Initialisation
    document.addEventListener('DOMContentLoaded', () => {
        const checkedRadio = document.querySelector('.role-radio:checked');
        if (checkedRadio) {
            const slug = checkedRadio.dataset.slug;
            const color = checkedRadio.dataset.color;
            const roleName = checkedRadio.closest('label').querySelector('.role-name').textContent;
            updateFieldsForRole(slug, color, roleName);
        }
        updatePreview();
    });
</script>
@endpush
@endsection
