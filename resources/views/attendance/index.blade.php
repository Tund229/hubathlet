@extends('layouts.dashboard')

@section('title', 'Présences')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $isStaff ? 'Présences' : 'Mes présences' }}</h1>
                <p class="text-sm text-slate-500">Suivi des séances</p>
            </div>
        </div>
        
        @if($isStaff)
            <a href="{{ route('attendance.export') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exporter
            </a>
        @endif
    </div>

    {{-- Stats du jour (compactes) --}}
    @if($isStaff)
        <div class="grid grid-cols-4 gap-3">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-slate-900">{{ $todayStats['total_trainings'] }}</p>
                <p class="text-xs text-slate-500">Séances</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-slate-900">{{ $todayStats['total_participants'] }}</p>
                <p class="text-xs text-slate-500">Attendus</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ $todayStats['present'] }}</p>
                <p class="text-xs text-slate-500">Présents</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-red-500">{{ $todayStats['absent'] }}</p>
                <p class="text-xs text-slate-500">Absents</p>
            </div>
        </div>
    @else
        @php
            $myTodayTrainings = $todayTrainings->filter(fn($t) => $t->participants->contains('id', auth()->id()));
            $myTodayPresent = $myTodayTrainings->filter(fn($t) => in_array($t->participants->where('id', auth()->id())->first()?->pivot?->status, ['present', 'late']))->count();
            $myTodayPending = $myTodayTrainings->filter(fn($t) => $t->participants->where('id', auth()->id())->first()?->pivot?->status === 'registered')->count();
        @endphp
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-slate-900">{{ $myTodayTrainings->count() }}</p>
                <p class="text-xs text-slate-500">Séances du jour</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-emerald-600">{{ $myTodayPresent }}</p>
                <p class="text-xs text-slate-500">Pointés</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $myTodayPending }}</p>
                <p class="text-xs text-slate-500">En attente</p>
            </div>
        </div>
    @endif

    {{-- Séances du jour (pointage rapide) --}}
    @php
        // Pour les joueurs, filtrer uniquement leurs séances
        $displayTodayTrainings = $isStaff 
            ? $todayTrainings 
            : $todayTrainings->filter(fn($t) => $t->participants->contains('id', auth()->id()));
    @endphp
    
    @if($displayTodayTrainings->count() > 0)
        <div class="bg-white rounded-2xl p-4 border border-slate-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-bold text-slate-800">{{ $isStaff ? 'Séances du jour' : 'Mes séances' }}</h2>
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">{{ $displayTodayTrainings->count() }} séance(s)</span>
            </div>
            
            <div class="space-y-2">
                @foreach($displayTodayTrainings as $training)
                    @php
                        $myParticipation = $training->participants->where('id', auth()->id())->first();
                        $myStatus = $myParticipation ? $myParticipation->pivot->status : null;
                        $canCheckIn = $myParticipation && !in_array($myStatus, ['present', 'late']);
                    @endphp
                    
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: {{ $training->type_color }}20">
                                <svg class="w-5 h-5" style="color: {{ $training->type_color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-slate-800 text-sm truncate">{{ $training->title }}</h3>
                                <p class="text-xs text-slate-500">
                                    {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    @if($isStaff) • {{ $training->participants->where('pivot.status', 'present')->count() }}/{{ $training->participants->count() }} @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex-shrink-0 ml-3">
                            @if($myParticipation)
                                @if(in_array($myStatus, ['present', 'late']))
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-bold {{ $myStatus === 'present' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $myStatus === 'present' ? 'OK' : 'Retard' }}
                                    </span>
                                @else
                                    <form action="{{ route('attendance.check-in', $training) }}" method="POST" class="check-in-form">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition-colors check-in-btn">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Pointer
                                        </button>
                                    </form>
                                @endif
                            @endif
                            
                            @if($isStaff)
                                <a href="{{ route('planning.show', $training) }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600 ml-2">
                                    Gérer
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Filtre période --}}
            <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
                <a href="{{ route('attendance.index', ['filter' => 'today']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'today' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Aujourd'hui
                </a>
                <a href="{{ route('attendance.index', ['filter' => 'week']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'week' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semaine
                </a>
                <a href="{{ route('attendance.index', ['filter' => 'month']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'month' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Mois
                </a>
                <a href="{{ route('attendance.index', ['filter' => 'all']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'all' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Tout
                </a>
            </div>
            
            @if($isStaff && $trainingsList->count() > 0)
                {{-- Filtre séance --}}
                <select onchange="window.location.href=this.value" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="{{ route('attendance.index', ['filter' => $filter]) }}">Toutes les séances</option>
                    @foreach($trainingsList as $t)
                        <option value="{{ route('attendance.index', ['filter' => $filter, 'training' => $t->id]) }}" {{ $trainingId == $t->id ? 'selected' : '' }}>
                            {{ $t->date->format('d/m') }} - {{ $t->title }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>

    {{-- Liste des présences par séance --}}
    <div class="space-y-4">
        @forelse($trainings as $training)
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                {{-- Header de la séance --}}
                <div class="p-4 border-b border-slate-100 bg-slate-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $training->type_color }}20">
                                <svg class="w-6 h-6" style="color: {{ $training->type_color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-slate-800">{{ $training->title }}</h3>
                                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold" style="background-color: {{ $training->type_color }}20; color: {{ $training->type_color }}">
                                        {{ $training->type_label }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-500">
                                    {{ $training->date->translatedFormat('l d F Y') }} • 
                                    {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    @if($training->location) • {{ $training->location }} @endif
                                </p>
                            </div>
                        </div>
                        
                        @if($isStaff)
                            <div class="flex items-center gap-4">
                                {{-- Stats rapides (visible staff uniquement) --}}
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="inline-flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $training->participants->whereIn('pivot.status', ['present', 'late'])->count() }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $training->participants->where('pivot.status', 'absent')->count() }}
                                    </span>
                                    <span class="text-slate-400">
                                        / {{ $training->participants->count() }}
                                    </span>
                                </div>
                                
                                <form action="{{ route('attendance.mark-all-present', $training) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-lg hover:bg-emerald-200 transition-colors">
                                        Tous présents
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Liste des participants (staff voit tout, joueur voit seulement lui-même) --}}
                <div class="divide-y divide-slate-100">
                    @php
                        // Si joueur, filtrer pour ne montrer que sa propre participation
                        $visibleParticipants = $isStaff 
                            ? $training->participants 
                            : $training->participants->where('id', auth()->id());
                    @endphp
                    
                    @forelse($visibleParticipants as $participant)
                        @php
                            $status = $participant->pivot->status;
                            $statusConfig = [
                                'registered' => ['label' => 'En attente', 'class' => 'bg-slate-100 text-slate-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                'present' => ['label' => 'Présent', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                'late' => ['label' => 'En retard', 'class' => 'bg-amber-100 text-amber-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                'absent' => ['label' => 'Absent', 'class' => 'bg-red-100 text-red-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                                'excused' => ['label' => 'Excusé', 'class' => 'bg-blue-100 text-blue-700', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'],
                            ];
                            $statusData = $statusConfig[$status] ?? $statusConfig['registered'];
                            $isMe = $participant->id === auth()->id();
                        @endphp
                        
                        <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors {{ $isMe && !$isStaff ? 'bg-emerald-50/50' : '' }}">
                            <div class="flex items-center gap-4">
                                {{-- Avatar --}}
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                    @if($participant->avatar)
                                        <img src="{{ Storage::url($participant->avatar) }}" alt="{{ $participant->name }}" class="w-full h-full rounded-xl object-cover">
                                    @else
                                        {{ strtoupper(substr($participant->name, 0, 2)) }}
                                    @endif
                                </div>
                                
                                {{-- Infos --}}
                                <div>
                                    <p class="font-semibold text-slate-800">
                                        {{ $participant->name }}
                                        @if($isMe && !$isStaff)
                                            <span class="text-emerald-600">(Moi)</span>
                                        @endif
                                    </p>
                                    @if($participant->pivot->arrived_at)
                                        <p class="text-xs text-slate-500">
                                            Pointé à {{ \Carbon\Carbon::parse($participant->pivot->arrived_at)->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                @if($isStaff)
                                    {{-- Sélecteur de statut --}}
                                    <select 
                                        onchange="updateAttendance({{ $training->id }}, {{ $participant->id }}, this.value)"
                                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 {{ $statusData['class'] }}"
                                    >
                                        <option value="registered" {{ $status === 'registered' ? 'selected' : '' }}>En attente</option>
                                        <option value="present" {{ $status === 'present' ? 'selected' : '' }}>Présent</option>
                                        <option value="late" {{ $status === 'late' ? 'selected' : '' }}>En retard</option>
                                        <option value="absent" {{ $status === 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="excused" {{ $status === 'excused' ? 'selected' : '' }}>Excusé</option>
                                    </select>
                                @else
                                    {{-- Badge de statut --}}
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-bold {{ $statusData['class'] }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusData['icon'] !!}</svg>
                                        {{ $statusData['label'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500">
                            {{ $isStaff ? 'Aucun participant inscrit' : 'Vous n\'êtes pas inscrit à cette séance' }}
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-slate-100">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucune séance</h3>
                <p class="text-slate-500">Pas de séance pour cette période</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($trainings->hasPages())
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            {{ $trainings->withQueryString()->links() }}
        </div>
    @endif
</div>

{{-- Script pour mise à jour AJAX --}}
<script>
function updateAttendance(trainingId, participantId, status) {
    fetch(`/attendance/${trainingId}/participant/${participantId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optionnel: afficher une notification
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// Check-in AJAX
document.querySelectorAll('.check-in-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('.check-in-btn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Configuration des statuts
                const statusConfig = {
                    'present': { class: 'bg-emerald-500', text: 'Présent', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
                    'late': { class: 'bg-amber-500', text: 'En retard', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' },
                    'absent': { class: 'bg-red-500', text: 'Absent', icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' }
                };
                
                const config = statusConfig[data.status] || statusConfig['present'];
                
                btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
                btn.classList.add(config.class);
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${config.icon}" /></svg> ${config.text}`;
                
                // Alerte si absent
                if (data.status === 'absent') {
                    alert(data.message);
                }
            } else {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                alert(data.message || 'Erreur lors du pointage');
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Pointer';
        });
    });
});
</script>
@endsection

