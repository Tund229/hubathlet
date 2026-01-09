@extends('layouts.dashboard-coach')

@section('title', 'Faire l\'appel')
@section('description', $training->title)

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <a href="{{ route('coach.trainings') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour
        </a>
    </div>

    <!-- Séance Info Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8" style="background: linear-gradient(135deg, {{ $training->type_color }}20 0%, {{ $training->type_color }}05 100%);">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                <div class="w-20 h-20 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg" style="background-color: {{ $training->type_color }};">
                    <span class="text-xs font-bold uppercase opacity-80">{{ $training->date->translatedFormat('M') }}</span>
                    <span class="text-3xl font-black leading-none">{{ $training->date->format('d') }}</span>
                    <span class="text-xs font-semibold opacity-80">{{ $training->date->translatedFormat('D') }}</span>
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-2xl font-black text-slate-900">{{ $training->title }}</h1>
                        <span class="px-3 py-1 rounded-full text-sm font-bold" style="background-color: {{ $training->type_color }}20; color: {{ $training->type_color }};">
                            {{ $training->type_label }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $training->status_color }}">
                            {{ $training->status_label }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-slate-600">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                            <span class="text-slate-400">({{ $training->duration_formatted }})</span>
                        </span>
                        
                        @if($training->location)
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $training->location }}
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    @if($training->status !== 'completed')
                    <form action="{{ route('coach.mark-all-present', $training) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-xl font-semibold text-sm hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/25">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Tous présents
                        </button>
                    </form>
                    
                    <form action="{{ route('coach.complete-training', $training) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-xl font-semibold text-sm hover:bg-slate-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Terminer
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Stats rapides -->
        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-slate-100 bg-slate-50/50">
            @php
                $presentCount = $training->participants->where('pivot.status', 'present')->count() + $training->participants->where('pivot.status', 'late')->count();
                $absentCount = $training->participants->where('pivot.status', 'absent')->count();
                $excusedCount = $training->participants->where('pivot.status', 'excused')->count();
                $pendingCount = $training->participants->where('pivot.status', 'pending')->count();
            @endphp
            
            <div class="p-4 text-center">
                <div class="text-2xl font-black text-emerald-600">{{ $presentCount }}</div>
                <div class="text-sm text-slate-500">Présents</div>
            </div>
            <div class="p-4 text-center">
                <div class="text-2xl font-black text-red-600">{{ $absentCount }}</div>
                <div class="text-sm text-slate-500">Absents</div>
            </div>
            <div class="p-4 text-center">
                <div class="text-2xl font-black text-amber-600">{{ $excusedCount }}</div>
                <div class="text-sm text-slate-500">Excusés</div>
            </div>
            <div class="p-4 text-center">
                <div class="text-2xl font-black text-slate-400">{{ $pendingCount }}</div>
                <div class="text-sm text-slate-500">En attente</div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">
        <!-- Liste des participants -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Liste des participants ({{ $training->participants->count() }})</h2>
                </div>
                
                @if($training->participants->isEmpty())
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-slate-500">Aucun participant inscrit</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($training->participants->sortBy('name') as $participant)
                        <div class="p-4 hover:bg-slate-50 transition-colors" data-participant="{{ $participant->id }}">
                            <div class="flex items-center gap-4">
                                <!-- Avatar -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 
                                    @if($participant->pivot->status === 'present') bg-emerald-500
                                    @elseif($participant->pivot->status === 'late') bg-amber-500
                                    @elseif($participant->pivot->status === 'absent') bg-red-500
                                    @elseif($participant->pivot->status === 'excused') bg-orange-400
                                    @else bg-slate-300
                                    @endif">
                                    {{ strtoupper(substr($participant->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $participant->name)[1] ?? '', 0, 1)) }}
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-slate-900">{{ $participant->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $participant->email }}</div>
                                </div>
                                
                                <!-- Boutons de présence -->
                                <div class="flex items-center gap-2 flex-wrap justify-end">
                                    <button type="button" onclick="updateAttendance({{ $training->id }}, {{ $participant->id }}, 'present')" 
                                        class="attendance-btn px-3 py-2 rounded-xl text-sm font-semibold transition-all {{ $participant->pivot->status === 'present' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }}">
                                        <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="hidden sm:inline">Présent</span>
                                    </button>
                                    
                                    <button type="button" onclick="updateAttendance({{ $training->id }}, {{ $participant->id }}, 'late')" 
                                        class="attendance-btn px-3 py-2 rounded-xl text-sm font-semibold transition-all {{ $participant->pivot->status === 'late' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/25' : 'bg-amber-100 text-amber-700 hover:bg-amber-200' }}">
                                        <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="hidden sm:inline">Retard</span>
                                    </button>
                                    
                                    <button type="button" onclick="updateAttendance({{ $training->id }}, {{ $participant->id }}, 'absent')" 
                                        class="attendance-btn px-3 py-2 rounded-xl text-sm font-semibold transition-all {{ $participant->pivot->status === 'absent' ? 'bg-red-500 text-white shadow-lg shadow-red-500/25' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                                        <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span class="hidden sm:inline">Absent</span>
                                    </button>
                                    
                                    <button type="button" onclick="updateAttendance({{ $training->id }}, {{ $participant->id }}, 'excused')" 
                                        class="attendance-btn px-3 py-2 rounded-xl text-sm font-semibold transition-all {{ $participant->pivot->status === 'excused' ? 'bg-orange-400 text-white shadow-lg shadow-orange-500/25' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">
                                        <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="hidden sm:inline">Excusé</span>
                                    </button>
                                    
                                    <!-- Retirer -->
                                    <form action="{{ route('coach.remove-participant', [$training, $participant]) }}" method="POST" class="inline" onsubmit="return confirm('Retirer ce participant ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Ajouter un participant -->
            @if($availablePlayers->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Ajouter un participant</h3>
                
                <form action="{{ route('coach.add-participant', $training) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div class="relative">
                            <select name="user_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                                <option value="">Sélectionner un joueur...</option>
                                @foreach($availablePlayers as $player)
                                <option value="{{ $player->id }}">{{ $player->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/25">
                            Ajouter à la séance
                        </button>
                    </div>
                </form>
            </div>
            @endif
            
            <!-- Légende -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Légende</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg"></div>
                        <span class="text-sm text-slate-600">Présent</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-500 rounded-lg"></div>
                        <span class="text-sm text-slate-600">En retard</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-500 rounded-lg"></div>
                        <span class="text-sm text-slate-600">Absent</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-400 rounded-lg"></div>
                        <span class="text-sm text-slate-600">Excusé</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-slate-300 rounded-lg"></div>
                        <span class="text-sm text-slate-600">En attente</span>
                    </div>
                </div>
            </div>
            
            <!-- Raccourcis clavier -->
            <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5">
                <h4 class="font-bold text-slate-700 text-sm mb-3">Conseils</h4>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Cliquez sur "Tous présents" pour marquer tout le monde d'un coup
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Terminez la séance pour enregistrer les statistiques
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function updateAttendance(trainingId, participantId, status) {
    fetch(`/coach/trainings/${trainingId}/participants/${participantId}/attendance`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'UI
            const row = document.querySelector(`[data-participant="${participantId}"]`);
            const avatar = row.querySelector('.rounded-xl.flex-shrink-0');
            const buttons = row.querySelectorAll('.attendance-btn');
            
            // Reset tous les boutons
            buttons.forEach(btn => {
                btn.classList.remove('bg-emerald-500', 'bg-amber-500', 'bg-red-500', 'bg-orange-400', 'text-white', 'shadow-lg', 'shadow-emerald-500/25', 'shadow-amber-500/25', 'shadow-red-500/25', 'shadow-orange-500/25');
                btn.classList.add('bg-emerald-100', 'text-emerald-700', 'hover:bg-emerald-200');
            });
            
            // Reset avatar
            avatar.classList.remove('bg-emerald-500', 'bg-amber-500', 'bg-red-500', 'bg-orange-400', 'bg-slate-300');
            
            // Appliquer le nouveau style
            const statusIndex = { 'present': 0, 'late': 1, 'absent': 2, 'excused': 3 };
            const colors = {
                'present': { bg: 'bg-emerald-500', shadow: 'shadow-emerald-500/25', inactive: 'bg-emerald-100 text-emerald-700' },
                'late': { bg: 'bg-amber-500', shadow: 'shadow-amber-500/25', inactive: 'bg-amber-100 text-amber-700' },
                'absent': { bg: 'bg-red-500', shadow: 'shadow-red-500/25', inactive: 'bg-red-100 text-red-700' },
                'excused': { bg: 'bg-orange-400', shadow: 'shadow-orange-500/25', inactive: 'bg-orange-100 text-orange-700' }
            };
            
            const activeBtn = buttons[statusIndex[status]];
            activeBtn.classList.remove('bg-emerald-100', 'bg-amber-100', 'bg-red-100', 'bg-orange-100', 'text-emerald-700', 'text-amber-700', 'text-red-700', 'text-orange-700', 'hover:bg-emerald-200', 'hover:bg-amber-200', 'hover:bg-red-200', 'hover:bg-orange-200');
            activeBtn.classList.add(colors[status].bg, 'text-white', 'shadow-lg', colors[status].shadow);
            
            // Mettre à jour l'avatar
            avatar.classList.add(colors[status].bg);
            
            // Mettre à jour les compteurs (optionnel - recharger pour être précis)
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}
</script>
@endsection

