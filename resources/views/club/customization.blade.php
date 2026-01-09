@extends('layouts.dashboard')

@section('title', 'Personnalisation')
@section('description', 'Personnalisez les couleurs et l\'apparence de votre club')

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
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Personnalisation</h1>
                <p class="text-sm text-slate-500">Couleurs et apparence de l'application</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('club.index') }}" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors">
                Annuler
            </a>
            <button type="submit" form="customization-form" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer
            </button>
        </div>
    </div>

    <form id="customization-form" method="POST" action="{{ route('club.customization.update') }}">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Couleurs du club -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-900 mb-1">Couleurs du club</h2>
                    <p class="text-sm text-slate-500 mb-6">Définissez les couleurs principales de votre club</p>
                    
                    <div class="grid sm:grid-cols-3 gap-6">
                        <!-- Primary Color -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Couleur principale</label>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="color" name="primary_color" id="primary_color" 
                                        value="{{ old('primary_color', $club->primary_color ?? '#10B981') }}"
                                        class="w-14 h-14 rounded-xl cursor-pointer border-2 border-slate-200 overflow-hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="text" id="primary_color_text" 
                                        value="{{ old('primary_color', $club->primary_color ?? '#10B981') }}"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono uppercase"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        onchange="document.getElementById('primary_color').value = this.value">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Logo, badges, éléments clés</p>
                        </div>

                        <!-- Secondary Color -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Couleur secondaire</label>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="color" name="secondary_color" id="secondary_color" 
                                        value="{{ old('secondary_color', $club->secondary_color ?? '#1E293B') }}"
                                        class="w-14 h-14 rounded-xl cursor-pointer border-2 border-slate-200 overflow-hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="text" id="secondary_color_text" 
                                        value="{{ old('secondary_color', $club->secondary_color ?? '#1E293B') }}"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono uppercase"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        onchange="document.getElementById('secondary_color').value = this.value">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Textes, fonds sombres</p>
                        </div>

                        <!-- Accent Color -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Couleur d'accent</label>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="color" name="accent_color" id="accent_color" 
                                        value="{{ old('accent_color', $club->accent_color ?? '#3B82F6') }}"
                                        class="w-14 h-14 rounded-xl cursor-pointer border-2 border-slate-200 overflow-hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="text" id="accent_color_text" 
                                        value="{{ old('accent_color', $club->accent_color ?? '#3B82F6') }}"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono uppercase"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        onchange="document.getElementById('accent_color').value = this.value">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Boutons, liens, highlights</p>
                        </div>
                    </div>
                </div>

                <!-- Couleurs PWA -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-900">Application mobile (PWA)</h2>
                            <p class="text-sm text-slate-500">Couleurs affichées lors de l'installation sur mobile</p>
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        <!-- Theme Color -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Couleur du thème</label>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="color" name="app_theme_color" id="app_theme_color" 
                                        value="{{ old('app_theme_color', $club->settings->app_theme_color ?? '#10B981') }}"
                                        class="w-14 h-14 rounded-xl cursor-pointer border-2 border-slate-200 overflow-hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="text" id="app_theme_color_text" 
                                        value="{{ old('app_theme_color', $club->settings->app_theme_color ?? '#10B981') }}"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono uppercase"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        onchange="document.getElementById('app_theme_color').value = this.value">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Barre de navigation mobile</p>
                        </div>

                        <!-- Background Color -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Couleur de fond</label>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="color" name="app_background_color" id="app_background_color" 
                                        value="{{ old('app_background_color', $club->settings->app_background_color ?? '#FFFFFF') }}"
                                        class="w-14 h-14 rounded-xl cursor-pointer border-2 border-slate-200 overflow-hidden">
                                </div>
                                <div class="flex-1">
                                    <input type="text" id="app_background_color_text" 
                                        value="{{ old('app_background_color', $club->settings->app_background_color ?? '#FFFFFF') }}"
                                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-mono uppercase"
                                        pattern="^#[0-9A-Fa-f]{6}$"
                                        onchange="document.getElementById('app_background_color').value = this.value">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Splash screen au démarrage</p>
                        </div>
                    </div>
                </div>

                <!-- Presets -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-900 mb-1">Palettes suggérées</h2>
                    <p class="text-sm text-slate-500 mb-4">Cliquez pour appliquer une palette prédéfinie</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button type="button" onclick="applyPreset('#10B981', '#1E293B', '#3B82F6')" class="group p-3 rounded-xl border border-slate-200 hover:border-emerald-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-blue-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Émeraude</span>
                        </button>

                        <button type="button" onclick="applyPreset('#3B82F6', '#1E293B', '#10B981')" class="group p-3 rounded-xl border border-slate-200 hover:border-blue-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-emerald-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Ocean</span>
                        </button>

                        <button type="button" onclick="applyPreset('#EF4444', '#1E293B', '#F59E0B')" class="group p-3 rounded-xl border border-slate-200 hover:border-red-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-red-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-amber-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Feu</span>
                        </button>

                        <button type="button" onclick="applyPreset('#8B5CF6', '#1E293B', '#EC4899')" class="group p-3 rounded-xl border border-slate-200 hover:border-violet-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-violet-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-pink-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Violet</span>
                        </button>

                        <button type="button" onclick="applyPreset('#F59E0B', '#1E293B', '#EF4444')" class="group p-3 rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-amber-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-red-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Soleil</span>
                        </button>

                        <button type="button" onclick="applyPreset('#06B6D4', '#1E293B', '#8B5CF6')" class="group p-3 rounded-xl border border-slate-200 hover:border-cyan-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-cyan-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-violet-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Cyan</span>
                        </button>

                        <button type="button" onclick="applyPreset('#22C55E', '#1E293B', '#EAB308')" class="group p-3 rounded-xl border border-slate-200 hover:border-green-300 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-green-500"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-yellow-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Nature</span>
                        </button>

                        <button type="button" onclick="applyPreset('#1E293B', '#F8FAFC', '#10B981')" class="group p-3 rounded-xl border border-slate-200 hover:border-slate-400 hover:shadow-md transition-all">
                            <div class="flex gap-1 mb-2">
                                <span class="w-6 h-6 rounded-lg bg-slate-800"></span>
                                <span class="w-6 h-6 rounded-lg bg-slate-100 border border-slate-200"></span>
                                <span class="w-6 h-6 rounded-lg bg-emerald-500"></span>
                            </div>
                            <span class="text-xs font-medium text-slate-600">Classique</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Preview -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-6 space-y-6">
                    <!-- Phone Preview -->
                    <div class="bg-slate-100 rounded-2xl p-6">
                        <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-4 text-center">Aperçu mobile</p>
                        
                        <div class="mx-auto w-[200px]">
                            <!-- Phone frame -->
                            <div class="bg-slate-800 rounded-[24px] p-2 shadow-xl">
                                <!-- Status bar -->
                                <div class="rounded-t-[18px] px-4 py-2 flex items-center justify-between" id="preview-statusbar" style="background-color: {{ $club->settings->app_theme_color ?? '#10B981' }};">
                                    <span class="text-white text-xs font-medium">9:41</span>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4a2 2 0 012 2v12a2 2 0 01-2 2 2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17 4a2 2 0 012 2v12a2 2 0 01-2 2 2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                                    </div>
                                </div>
                                
                                <!-- Screen -->
                                <div class="bg-white rounded-b-[18px] overflow-hidden">
                                    <!-- App header -->
                                    <div class="p-3" id="preview-header" style="background-color: {{ $club->primary_color ?? '#10B981' }};">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold" id="preview-logo">{{ strtoupper(substr($club->name, 0, 2)) }}</span>
                                            </div>
                                            <span class="text-white font-bold text-sm truncate">{{ $club->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="p-3 space-y-2">
                                        <div class="h-3 rounded bg-slate-100 w-3/4"></div>
                                        <div class="h-3 rounded bg-slate-100 w-1/2"></div>
                                        
                                        <div class="mt-3 p-2 rounded-lg" id="preview-card" style="background-color: {{ $club->accent_color ?? '#3B82F6' }}15;">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full" id="preview-card-icon" style="background-color: {{ $club->accent_color ?? '#3B82F6' }};"></div>
                                                <div class="flex-1">
                                                    <div class="h-2 rounded bg-slate-200 w-full mb-1"></div>
                                                    <div class="h-2 rounded bg-slate-100 w-2/3"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 py-2 px-3 rounded-lg text-white text-xs font-bold text-center" id="preview-button" style="background-color: {{ $club->primary_color ?? '#10B981' }};">
                                            Bouton action
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-amber-900 text-sm mb-1">Conseil</h4>
                                <p class="text-sm text-amber-700">Choisissez des couleurs contrastées pour une meilleure lisibilité.</p>
                            </div>
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
// Sync color inputs with text inputs
const colorInputs = ['primary_color', 'secondary_color', 'accent_color', 'app_theme_color', 'app_background_color'];
colorInputs.forEach(id => {
    const colorInput = document.getElementById(id);
    const textInput = document.getElementById(id + '_text');
    
    if (colorInput && textInput) {
        colorInput.addEventListener('input', function() {
            textInput.value = this.value.toUpperCase();
            updatePreview();
        });
        textInput.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorInput.value = this.value;
                updatePreview();
            }
        });
    }
});

// Update preview
function updatePreview() {
    const primary = document.getElementById('primary_color').value;
    const secondary = document.getElementById('secondary_color').value;
    const accent = document.getElementById('accent_color').value;
    const theme = document.getElementById('app_theme_color').value;
    
    // Update preview elements
    document.getElementById('preview-statusbar').style.backgroundColor = theme;
    document.getElementById('preview-header').style.backgroundColor = primary;
    document.getElementById('preview-button').style.backgroundColor = primary;
    document.getElementById('preview-card').style.backgroundColor = accent + '15';
    document.getElementById('preview-card-icon').style.backgroundColor = accent;
}

// Apply preset
function applyPreset(primary, secondary, accent) {
    document.getElementById('primary_color').value = primary;
    document.getElementById('primary_color_text').value = primary;
    document.getElementById('secondary_color').value = secondary;
    document.getElementById('secondary_color_text').value = secondary;
    document.getElementById('accent_color').value = accent;
    document.getElementById('accent_color_text').value = accent;
    document.getElementById('app_theme_color').value = primary;
    document.getElementById('app_theme_color_text').value = primary;
    updatePreview();
}
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
    input[type="color"] {
        -webkit-appearance: none;
        border: none;
        padding: 0;
    }
    input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }
    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 0.75rem;
    }
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out; }
</style>
@endpush
@endsection

