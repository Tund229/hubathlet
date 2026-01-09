@extends('layouts.dashboard')

@section('title', 'Paramètres')
@section('description', 'Paramètres de votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('club.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Paramètres</h1>
                <p class="text-sm text-slate-500">Langue, fuseau horaire et fonctionnalités</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('club.index') }}" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors">
                Annuler
            </a>
            <button type="submit" form="settings-form" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer
            </button>
        </div>
    </div>

    <form id="settings-form" method="POST" action="{{ route('club.settings.update') }}">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-12 gap-6">
            <!-- Main Content - 8 cols -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Application name -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Nom de l'application</h2>
                            <p class="text-sm text-slate-500">Nom affiché lors de l'installation sur mobile (PWA)</p>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="app_name" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="app_name" id="app_name" 
                                value="{{ old('app_name', $club->settings->app_name ?? $club->name) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('app_name') border-red-500 @enderror"
                                placeholder="Ex: FC Paris Training">
                            @error('app_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="app_short_name" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nom court
                            </label>
                            <input type="text" name="app_short_name" id="app_short_name" 
                                value="{{ old('app_short_name', $club->settings->app_short_name ?? '') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('app_short_name') border-red-500 @enderror"
                                placeholder="Ex: FCP" maxlength="12">
                            <p class="text-xs text-slate-400 mt-1">12 caractères max</p>
                            @error('app_short_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Localisation</h2>
                            <p class="text-sm text-slate-500">Langue et fuseau horaire de l'application</p>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="language" class="block text-sm font-semibold text-slate-700 mb-2">
                                Langue <span class="text-red-500">*</span>
                            </label>
                            <select name="language" id="language" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('language') border-red-500 @enderror">
                                @foreach($languages as $code => $name)
                                    <option value="{{ $code }}" {{ old('language', $club->settings->language ?? 'fr') === $code ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="timezone" class="block text-sm font-semibold text-slate-700 mb-2">
                                Fuseau horaire <span class="text-red-500">*</span>
                            </label>
                            <select name="timezone" id="timezone" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('timezone') border-red-500 @enderror">
                                @foreach($timezones as $tz => $label)
                                    <option value="{{ $tz }}" {{ old('timezone', $club->settings->timezone ?? 'Europe/Paris') === $tz ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('timezone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Fonctionnalités</h2>
                            <p class="text-sm text-slate-500">Activez ou désactivez des fonctionnalités du club</p>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <!-- Public Registration -->
                        <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-900 text-sm">Inscription publique</span>
                                    <p class="text-xs text-slate-500">Auto-inscription des membres</p>
                                </div>
                            </div>
                            <div class="relative flex-shrink-0">
                                <input type="hidden" name="public_registration" value="0">
                                <input type="checkbox" name="public_registration" value="1" 
                                    {{ old('public_registration', $club->settings->public_registration ?? true) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>

                        <!-- Push Notifications -->
                        <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-colors group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold text-slate-900 text-sm">Notifications push</span>
                                    <p class="text-xs text-slate-500">Rappels de séances</p>
                                </div>
                            </div>
                            <div class="relative flex-shrink-0">
                                <input type="hidden" name="push_notifications" value="0">
                                <input type="checkbox" name="push_notifications" value="1" 
                                    {{ old('push_notifications', $club->settings->push_notifications ?? false) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-300 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Sidebar - 4 cols -->
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-6 space-y-6">
                    <!-- Preview PWA -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Aperçu PWA
                        </h3>
                        
                        <!-- Phone frame -->
                        <div class="mx-auto w-[180px]">
                            <div class="bg-slate-800 rounded-[24px] p-2 shadow-xl">
                                <!-- Screen -->
                                <div class="bg-white rounded-[18px] overflow-hidden">
                                    <!-- Status bar -->
                                    <div class="px-4 py-2 flex items-center justify-between bg-slate-100">
                                        <span class="text-slate-600 text-xs font-medium">9:41</span>
                                        <div class="flex items-center gap-1">
                                            <div class="w-3 h-3 rounded-full bg-slate-400"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Home screen -->
                                    <div class="p-4 bg-gradient-to-br from-slate-100 to-slate-200 min-h-[200px]">
                                        <!-- App Icon -->
                                        <div class="flex flex-col items-center">
                                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg mb-2" style="background-color: {{ $club->primary_color ?? '#10B981' }};">
                                                <span class="text-white text-lg font-black" id="preview-icon">{{ strtoupper(substr($club->settings->app_short_name ?? substr($club->name, 0, 2), 0, 2)) }}</span>
                                            </div>
                                            <span class="text-xs font-medium text-slate-700 text-center truncate max-w-[70px]" id="preview-app-name">{{ $club->settings->app_short_name ?? substr($club->name, 0, 8) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-xs text-slate-500 text-center mt-4">Apparence sur l'écran d'accueil</p>
                    </div>

                    <!-- Info cards -->
                    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-blue-900 text-sm mb-1">PWA (Progressive Web App)</h4>
                                <p class="text-xs text-blue-700">Votre application peut être installée directement depuis le navigateur, sans passer par les stores.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-900 text-sm mb-1">Fuseau horaire</h4>
                                <p class="text-xs text-amber-700">Tous les horaires de séances seront affichés selon ce fuseau.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick links -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
                        <h3 class="font-bold text-slate-900 text-sm mb-3">Autres paramètres</h3>
                        <div class="space-y-2">
                            <a href="{{ route('club.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Informations du club</span>
                            </a>
                            <a href="{{ route('club.customization') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Personnalisation</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile actions -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-100 z-40">
            <div class="flex gap-3">
                <a href="{{ route('club.index') }}" class="flex-1 px-4 py-3 text-center text-slate-600 rounded-xl font-semibold bg-slate-100 hover:bg-slate-200 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Live preview
document.getElementById('app_short_name').addEventListener('input', function() {
    const value = this.value || '{{ substr($club->name, 0, 2) }}';
    document.getElementById('preview-app-name').textContent = value.substring(0, 8);
    document.getElementById('preview-icon').textContent = value.substring(0, 2).toUpperCase();
});

document.getElementById('app_name').addEventListener('input', function() {
    if (!document.getElementById('app_short_name').value) {
        const value = this.value || '{{ $club->name }}';
        document.getElementById('preview-app-name').textContent = value.substring(0, 8);
        document.getElementById('preview-icon').textContent = value.substring(0, 2).toUpperCase();
    }
});
</script>

@if(session('success'))
    <div id="toast-success" class="fixed bottom-4 right-4 z-50 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg shadow-emerald-500/25 flex items-center gap-3 animate-slide-up">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => document.getElementById('toast-success').remove(), 4000);
    </script>
@endif

@push('styles')
<style>
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out; }
</style>
@endpush
@endsection
