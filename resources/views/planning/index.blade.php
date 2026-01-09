@extends('layouts.dashboard')

@section('title', 'Planning')
@section('description', 'Gérez vos entraînements et événements')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Planning</h1>
            <p class="text-slate-500 mt-1">Gérez vos entraînements et événements</p>
        </div>
        <a href="{{ route('planning.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nouvelle séance
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $stats['upcoming'] }}</div>
                    <div class="text-xs text-slate-500">À venir</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $stats['this_week'] }}</div>
                    <div class="text-xs text-slate-500">Cette semaine</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $stats['total_month'] }}</div>
                    <div class="text-xs text-slate-500">Ce mois</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $stats['completed_month'] }}</div>
                    <div class="text-xs text-slate-500">Terminées</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Séances du jour -->
    @if($todayTrainings->count() > 0)
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold">Aujourd'hui</span>
                <span class="text-emerald-200 text-sm">{{ now()->format('l d F') }}</span>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($todayTrainings as $training)
                    <a href="{{ route('planning.show', $training) }}" class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition-colors">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold">{{ $training->title }}</div>
                                <div class="text-emerald-200 text-sm">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold" style="background-color: {{ $training->type_color }}30;">
                                {{ $training->type_label }}
                            </span>
                        </div>
                        @if($training->location)
                            <div class="flex items-center gap-1 mt-2 text-emerald-200 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $training->location }}
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Filtre période -->
            <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
                <a href="{{ route('planning.index', ['filter' => 'upcoming', 'type' => $type]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'upcoming' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    À venir
                </a>
                <a href="{{ route('planning.index', ['filter' => 'week', 'type' => $type]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'week' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semaine
                </a>
                <a href="{{ route('planning.index', ['filter' => 'month', 'type' => $type]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'month' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Mois
                </a>
                <a href="{{ route('planning.index', ['filter' => 'past', 'type' => $type]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $filter === 'past' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Passées
                </a>
            </div>

            <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

            <!-- Filtre type -->
            <div class="flex items-center gap-2">
                <a href="{{ route('planning.index', ['filter' => $filter, 'type' => '']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $type === '' ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Tous
                </a>
                <a href="{{ route('planning.index', ['filter' => $filter, 'type' => 'training']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $type === 'training' ? 'bg-emerald-500 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Entraînements
                </a>
                <a href="{{ route('planning.index', ['filter' => $filter, 'type' => 'match']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $type === 'match' ? 'bg-blue-500 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Matchs
                </a>
                <a href="{{ route('planning.index', ['filter' => $filter, 'type' => 'event']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all hidden sm:inline-flex {{ $type === 'event' ? 'bg-violet-500 text-white' : 'bg-violet-50 text-violet-700 hover:bg-violet-100' }}">
                    Événements
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des séances -->
    <div class="space-y-3">
        @forelse($trainings as $training)
            @php
                $isToday = $training->date->isToday();
                $isPast = $training->isPast();
            @endphp
            <a href="{{ route('planning.show', $training) }}" 
               class="block bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all {{ $isPast ? 'opacity-60' : '' }}">
                <div class="p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <!-- Date -->
                        <div class="flex items-center gap-4 sm:w-32">
                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center {{ $isToday ? 'bg-emerald-500 text-white' : 'bg-slate-100' }}">
                                <span class="text-xs font-medium {{ $isToday ? 'text-emerald-200' : 'text-slate-500' }}">{{ $training->date->format('M') }}</span>
                                <span class="text-xl font-black">{{ $training->date->format('d') }}</span>
                            </div>
                            <div class="sm:hidden">
                                <div class="font-bold text-slate-900">{{ $training->title }}</div>
                                <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</div>
                            </div>
                        </div>
                        
                        <!-- Infos -->
                        <div class="flex-1 hidden sm:block">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $training->type_color }};"></span>
                                <h3 class="font-bold text-slate-900">{{ $training->title }}</h3>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold" style="background-color: {{ $training->type_color }}15; color: {{ $training->type_color }};">
                                    {{ $training->type_label }}
                                </span>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $training->status_color }}">
                                    {{ $training->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 mt-2 text-sm text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    <span class="text-slate-400">({{ $training->duration_formatted }})</span>
                                </span>
                                @if($training->location)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        </svg>
                                        {{ $training->location }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Participants & Actions -->
                        <div class="flex items-center justify-between sm:justify-end gap-4">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    @foreach($training->participants->take(3) as $participant)
                                        <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ strtoupper(substr($participant->name, 0, 1)) }}
                                        </div>
                                    @endforeach
                                    @if($training->participants->count() > 3)
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600">
                                            +{{ $training->participants->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                                <span class="text-sm text-slate-500">
                                    {{ $training->participants->count() }}
                                    @if($training->max_participants)
                                        / {{ $training->max_participants }}
                                    @endif
                                </span>
                            </div>
                            
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Mobile info -->
                    <div class="sm:hidden mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $training->type_color }};"></span>
                            <span class="text-xs font-medium" style="color: {{ $training->type_color }};">{{ $training->type_label }}</span>
                        </div>
                        @if($training->location)
                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $training->location }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucune séance</h3>
                <p class="text-slate-500 mb-6">Commencez par créer votre première séance</p>
                <a href="{{ route('planning.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer une séance
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($trainings->hasPages())
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            {{ $trainings->withQueryString()->links() }}
        </div>
    @endif
</div>

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

