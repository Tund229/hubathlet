@extends('layouts.dashboard')

@section('title', 'Nouvelle séance')
@section('description', 'Créer une nouvelle séance')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('planning.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Nouvelle séance</h1>
                <p class="text-sm text-slate-500">Planifiez un entraînement ou événement</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('planning.index') }}" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors">
                Annuler
            </a>
            <button type="submit" form="training-form" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Créer
            </button>
        </div>
    </div>

    <form id="training-form" method="POST" action="{{ route('planning.store') }}">
        @csrf
        
        <div class="grid lg:grid-cols-12 gap-6">
            
            <!-- Colonne principale -->
            <div class="lg:col-span-8 space-y-5">
                
                <!-- Type de séance -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Type de séance</h2>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="training" class="sr-only peer type-radio" {{ old('type', 'training') === 'training' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50 transition-all text-center">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-900 text-sm">Entraînement</div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="match" class="sr-only peer type-radio" {{ old('type') === 'match' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-slate-50 transition-all text-center">
                                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-900 text-sm">Match</div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="event" class="sr-only peer type-radio" {{ old('type') === 'event' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-violet-500 peer-checked:bg-violet-50 hover:bg-slate-50 transition-all text-center">
                                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-900 text-sm">Événement</div>
                            </div>
                        </label>
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="meeting" class="sr-only peer type-radio" {{ old('type') === 'meeting' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 hover:bg-slate-50 transition-all text-center">
                                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="font-bold text-slate-900 text-sm">Réunion</div>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations générales -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Informations</h2>
                    
                    <div class="space-y-4">
                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-xs font-medium text-slate-600 mb-1">
                                Titre <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('title') border-red-300 bg-red-50 @enderror"
                                placeholder="Ex: Entraînement technique">
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs font-medium text-slate-600 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all resize-none"
                                placeholder="Description de la séance...">{{ old('description') }}</textarea>
                        </div>
                        
                        <!-- Date et horaires -->
                        <div class="grid sm:grid-cols-3 gap-4">
                            <div>
                                <label for="date" class="block text-xs font-medium text-slate-600 mb-1">
                                    Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('date') border-red-300 bg-red-50 @enderror">
                                @error('date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="start_time" class="block text-xs font-medium text-slate-600 mb-1">
                                    Début <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', '18:00') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('start_time') border-red-300 bg-red-50 @enderror">
                                @error('start_time')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="end_time" class="block text-xs font-medium text-slate-600 mb-1">
                                    Fin <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', '20:00') }}" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('end_time') border-red-300 bg-red-50 @enderror">
                                @error('end_time')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Lieu -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="location" class="block text-xs font-medium text-slate-600 mb-1">Lieu</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                    placeholder="Ex: Gymnase Municipal">
                            </div>
                            
                            <div>
                                <label for="address" class="block text-xs font-medium text-slate-600 mb-1">Adresse</label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                    placeholder="Ex: 12 rue du Sport, 75001 Paris">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Participants -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Participants</h2>
                        <button type="button" id="select-all-btn" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                            Tout sélectionner
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 max-h-64 overflow-y-auto">
                        @foreach($members as $member)
                            <label class="relative flex items-center gap-2 p-2.5 rounded-xl border border-transparent cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                <input type="checkbox" name="participants[]" value="{{ $member->id }}" class="sr-only peer participant-checkbox" {{ in_array($member->id, old('participants', [])) ? 'checked' : '' }}>
                                <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-slate-700 truncate flex-1">{{ $member->name }}</span>
                                <div class="w-4 h-4 rounded border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    
                    @if($members->isEmpty())
                        <div class="text-center py-6 text-slate-500 text-sm">
                            Aucun membre actif dans le club
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Colonne latérale -->
            <div class="lg:col-span-4 space-y-5">
                
                <!-- Options -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Options</h2>
                    
                    <div class="space-y-4">
                        <!-- Coach -->
                        <div>
                            <label for="coach_id" class="block text-xs font-medium text-slate-600 mb-1">Coach responsable</label>
                            <select id="coach_id" name="coach_id"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all">
                                <option value="">— Aucun —</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->id }}" {{ old('coach_id') == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Max participants -->
                        <div>
                            <label for="max_participants" class="block text-xs font-medium text-slate-600 mb-1">Places max.</label>
                            <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants') }}" min="1"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                                placeholder="Illimité">
                        </div>
                        
                        <!-- Couleur -->
                        <div>
                            <label for="color" class="block text-xs font-medium text-slate-600 mb-1">Couleur</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="color" name="color" value="{{ old('color', '#10B981') }}"
                                    class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer">
                                <input type="text" id="color-text" value="{{ old('color', '#10B981') }}"
                                    class="flex-1 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-sm font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes internes -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Notes internes</h2>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all resize-none text-sm"
                        placeholder="Notes visibles uniquement par le staff...">{{ old('notes') }}</textarea>
                </div>

                <!-- Aperçu -->
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Aperçu</h2>
                    
                    <div class="rounded-xl border border-slate-200 overflow-hidden" id="preview-card">
                        <div class="h-2" id="preview-color-bar" style="background-color: #10B981;"></div>
                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700" id="preview-type">Entraînement</span>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-1" id="preview-title">Titre de la séance</h3>
                            <div class="flex items-center gap-3 text-sm text-slate-500">
                                <span id="preview-date">{{ now()->format('d/m/Y') }}</span>
                                <span id="preview-time">18:00 - 20:00</span>
                            </div>
                            <div class="text-sm text-slate-500 mt-1" id="preview-location" style="display: none;">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span id="preview-location-text"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Actions -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 flex gap-3 z-40">
            <a href="{{ route('planning.index') }}" class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-bold text-center text-sm">
                Annuler
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Créer
            </button>
        </div>
        
        <div class="sm:hidden h-20"></div>
    </form>
</div>

@push('scripts')
<script>
    const typeConfig = {
        'training': { label: 'Entraînement', color: '#10B981', bgClass: 'bg-emerald-100 text-emerald-700' },
        'match': { label: 'Match', color: '#3B82F6', bgClass: 'bg-blue-100 text-blue-700' },
        'event': { label: 'Événement', color: '#8B5CF6', bgClass: 'bg-violet-100 text-violet-700' },
        'meeting': { label: 'Réunion', color: '#F59E0B', bgClass: 'bg-amber-100 text-amber-700' }
    };

    // Elements
    const titleInput = document.getElementById('title');
    const dateInput = document.getElementById('date');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const locationInput = document.getElementById('location');
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color-text');
    
    const previewTitle = document.getElementById('preview-title');
    const previewDate = document.getElementById('preview-date');
    const previewTime = document.getElementById('preview-time');
    const previewLocation = document.getElementById('preview-location');
    const previewLocationText = document.getElementById('preview-location-text');
    const previewColorBar = document.getElementById('preview-color-bar');
    const previewType = document.getElementById('preview-type');

    // Type change
    document.querySelectorAll('.type-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            const config = typeConfig[radio.value];
            colorInput.value = config.color;
            colorTextInput.value = config.color;
            previewColorBar.style.backgroundColor = config.color;
            previewType.textContent = config.label;
            previewType.className = `px-2 py-0.5 rounded text-xs font-bold ${config.bgClass}`;
        });
    });

    // Live preview
    titleInput.addEventListener('input', () => {
        previewTitle.textContent = titleInput.value || 'Titre de la séance';
    });

    dateInput.addEventListener('change', () => {
        const date = new Date(dateInput.value);
        previewDate.textContent = date.toLocaleDateString('fr-FR');
    });

    function updateTimePreview() {
        previewTime.textContent = `${startTimeInput.value} - ${endTimeInput.value}`;
    }
    startTimeInput.addEventListener('change', updateTimePreview);
    endTimeInput.addEventListener('change', updateTimePreview);

    locationInput.addEventListener('input', () => {
        if (locationInput.value) {
            previewLocation.style.display = 'block';
            previewLocationText.textContent = locationInput.value;
        } else {
            previewLocation.style.display = 'none';
        }
    });

    // Color sync
    colorInput.addEventListener('input', () => {
        colorTextInput.value = colorInput.value;
        previewColorBar.style.backgroundColor = colorInput.value;
    });
    colorTextInput.addEventListener('input', () => {
        if (/^#[0-9A-Fa-f]{6}$/.test(colorTextInput.value)) {
            colorInput.value = colorTextInput.value;
            previewColorBar.style.backgroundColor = colorTextInput.value;
        }
    });

    // Select all participants
    document.getElementById('select-all-btn').addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.participant-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    });
</script>
@endpush
@endsection

