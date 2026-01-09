@extends('layouts.dashboard-coach')

@section('title', 'Espace Coach')
@section('description', 'Gérez vos séances et suivez vos joueurs')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Bonjour, Coach {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-slate-500 mt-1">Voici le résumé de vos activités</p>
        </div>
        <a href="{{ route('planning.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Créer une séance
        </a>
    </div>

    <!-- Séances du jour -->
    @if($todayTrainings->count() > 0)
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold">Séances aujourd'hui</h2>
                <span class="ml-auto bg-white/20 backdrop-blur px-3 py-1 rounded-full text-sm font-bold">
                    {{ $todayTrainings->count() }} séance(s)
                </span>
            </div>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($todayTrainings as $training)
                <a href="{{ route('coach.attendance', $training) }}" class="bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition-colors">
                    <div class="font-bold mb-1">{{ $training->title }}</div>
                    <div class="text-sm text-white/80 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            @foreach($training->participants->take(3) as $p)
                            <div class="w-7 h-7 rounded-full bg-white/30 border-2 border-white/50 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($p->name, 0, 1)) }}
                            </div>
                            @endforeach
                            @if($training->participants->count() > 3)
                            <div class="w-7 h-7 rounded-full bg-white/30 border-2 border-white/50 flex items-center justify-center text-xs font-bold">
                                +{{ $training->participants->count() - 3 }}
                            </div>
                            @endif
                        </div>
                        <span class="text-sm font-semibold bg-white/20 px-2 py-1 rounded-lg">
                            Faire l'appel →
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $totalTrainings }}</div>
            <div class="text-sm text-slate-500 font-medium">Séances totales</div>
            <div class="text-xs text-slate-400 mt-1">{{ $completedTrainings }} terminées</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $attendanceRate }}%</div>
            <div class="text-sm text-slate-500 font-medium">Taux de présence</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                <div class="h-1.5 rounded-full {{ $attendanceRate >= 80 ? 'bg-emerald-500' : ($attendanceRate >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $attendanceRate }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $myPlayersCount }}</div>
            <div class="text-sm text-slate-500 font-medium">Joueurs coachés</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $totalHours }}<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500 font-medium">Heures données</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid lg:grid-cols-12 gap-6">
        <!-- Colonne gauche -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Actions rapides</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a href="{{ route('coach.trainings') }}" class="flex flex-col items-center gap-2 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-emerald-700 text-center">Mes séances</span>
                    </a>
                    
                    <a href="{{ route('planning.create') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-blue-700 text-center">Nouvelle séance</span>
                    </a>
                    
                    <a href="{{ route('coach.player-stats') }}" class="flex flex-col items-center gap-2 p-4 bg-violet-50 rounded-xl hover:bg-violet-100 transition-colors group">
                        <div class="w-10 h-10 bg-violet-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-violet-700 text-center">Stats joueurs</span>
                    </a>
                    
                    <a href="{{ route('members.index') }}" class="flex flex-col items-center gap-2 p-4 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors group">
                        <div class="w-10 h-10 bg-slate-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-700 text-center">Membres</span>
                    </a>
                </div>
            </div>

            <!-- Prochaines séances -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Prochaines séances</h2>
                    <a href="{{ route('coach.trainings') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Voir tout →</a>
                </div>
                
                @if($upcomingTrainings->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 mb-4">Aucune séance planifiée</p>
                        <a href="{{ route('planning.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-xl font-semibold text-sm hover:bg-emerald-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer une séance
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($upcomingTrainings as $training)
                        <a href="{{ route('coach.attendance', $training) }}" class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center text-white" style="background-color: {{ $training->type_color }};">
                                <span class="text-[10px] font-bold uppercase">{{ $training->date->translatedFormat('M') }}</span>
                                <span class="text-lg font-black leading-none">{{ $training->date->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900">{{ $training->title }}</div>
                                <div class="text-sm text-slate-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right hidden sm:block">
                                    <div class="text-sm font-bold text-slate-900">{{ $training->participants->count() }}</div>
                                    <div class="text-xs text-slate-500">inscrits</div>
                                </div>
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Carte coach -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center text-white text-xl font-black">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-white font-bold text-lg">{{ auth()->user()->name }}</div>
                            <div class="text-emerald-400 text-sm font-semibold">Coach</div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">Séances données</span>
                            <span class="text-white font-bold">{{ $completedTrainings }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">Heures cumulées</span>
                            <span class="text-white font-bold">{{ $totalHours }}h</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 text-sm">Taux présence moyen</span>
                            <span class="text-emerald-400 font-bold">{{ $attendanceRate }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100">
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-900 text-sm mb-1">Astuce Coach</h4>
                        <p class="text-sm text-amber-700">Pensez à faire l'appel juste avant ou après chaque séance pour maintenir des statistiques précises.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

