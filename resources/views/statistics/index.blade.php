@extends('layouts.dashboard')

@section('title', 'Statistiques')
@section('description', 'Vue d\'ensemble des statistiques du club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Statistiques</h1>
            <p class="text-slate-500 mt-1">Vue d'ensemble de l'activité du club</p>
        </div>
        
        <!-- Period Filter -->
        <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-200 shadow-sm">
            <a href="{{ route('statistics.index', ['period' => 'week']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $period === 'week' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                Semaine
            </a>
            <a href="{{ route('statistics.index', ['period' => 'month']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $period === 'month' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                Mois
            </a>
            <a href="{{ route('statistics.index', ['period' => 'year']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $period === 'year' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                Année
            </a>
            <a href="{{ route('statistics.index', ['period' => 'all']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ $period === 'all' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
                Tout
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Membres actifs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $globalStats['total_members'] }}</div>
            <div class="text-sm text-slate-500">Membres actifs</div>
            <div class="flex items-center gap-2 mt-2 text-xs">
                <span class="text-emerald-600 font-semibold">{{ $globalStats['active_players'] }} joueurs</span>
                <span class="text-slate-300">•</span>
                <span class="text-violet-600 font-semibold">{{ $globalStats['active_coaches'] }} coachs</span>
            </div>
        </div>

        <!-- Entraînements -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $globalStats['total_trainings'] }}</div>
            <div class="text-sm text-slate-500">Séances programmées</div>
            <div class="flex items-center gap-2 mt-2 text-xs">
                <span class="text-emerald-600 font-semibold">{{ $globalStats['completed_trainings'] }} terminées</span>
            </div>
        </div>

        <!-- Heures d'entraînement -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $globalStats['total_training_hours'] }}<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500">Heures d'entraînement</div>
        </div>

        <!-- Taux de présence -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $globalStats['avg_attendance_rate'] }}<span class="text-lg">%</span></div>
            <div class="text-sm text-slate-500">Taux de présence moyen</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Évolution des entraînements -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-bold text-slate-900">Évolution des séances</h2>
                <a href="{{ route('statistics.trainings') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                    Voir détails →
                </a>
            </div>
            
            <div class="h-48 flex items-end justify-between gap-2">
                @foreach($trainingEvolution as $data)
                    @php
                        $maxValue = collect($trainingEvolution)->max('value') ?: 1;
                        $height = ($data['value'] / $maxValue) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <span class="text-xs font-bold text-slate-900">{{ $data['value'] }}</span>
                        <div class="w-full bg-emerald-100 rounded-t-lg transition-all hover:bg-emerald-200" style="height: {{ max($height, 5) }}%;">
                            <div class="w-full h-full bg-emerald-500 rounded-t-lg" style="height: {{ $height }}%;"></div>
                        </div>
                        <span class="text-xs text-slate-500">{{ $data['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Par type de séance</h2>
            
            <div class="space-y-4">
                @php
                    $totalTypes = collect($typeStats)->sum('count') ?: 1;
                @endphp
                @foreach($typeStats as $stat)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-slate-700">{{ $stat['label'] }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ $stat['count'] }}</span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all" style="width: {{ ($stat['count'] / $totalTypes) * 100 }}%; background-color: {{ $stat['color'] }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        
        <!-- Statistiques de présence -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Présences</h2>
            
            <div class="flex items-center justify-center mb-6">
                <!-- Cercle de progression -->
                <div class="relative w-40 h-40">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#f1f5f9" stroke-width="12"/>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#10B981" stroke-width="12" 
                            stroke-dasharray="{{ $attendanceStats['rate'] * 2.51 }} 251" stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-slate-900">{{ $attendanceStats['rate'] }}%</span>
                        <span class="text-xs text-slate-500">de présence</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 text-center">
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <div class="text-xl font-black text-emerald-600">{{ $attendanceStats['present'] }}</div>
                    <div class="text-xs text-emerald-600">Présents</div>
                </div>
                <div class="p-3 bg-red-50 rounded-xl">
                    <div class="text-xl font-black text-red-600">{{ $attendanceStats['absent'] }}</div>
                    <div class="text-xs text-red-600">Absents</div>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <div class="text-xl font-black text-amber-600">{{ $attendanceStats['excused'] }}</div>
                    <div class="text-xs text-amber-600">Excusés</div>
                </div>
            </div>
        </div>

        <!-- Répartition des rôles -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Répartition des membres</h2>
            
            @if(count($roleDistribution) > 0)
                <div class="space-y-3">
                    @php
                        $totalMembers = collect($roleDistribution)->sum('count') ?: 1;
                    @endphp
                    @foreach($roleDistribution as $role)
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color: {{ $role['color'] }}20;">
                                <span class="text-lg font-black" style="color: {{ $role['color'] }};">{{ $role['count'] }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-medium text-slate-700">{{ $role['role'] }}</span>
                                    <span class="text-sm text-slate-500">{{ round(($role['count'] / $totalMembers) * 100) }}%</span>
                                </div>
                                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="width: {{ ($role['count'] / $totalMembers) * 100 }}%; background-color: {{ $role['color'] }};"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-500">
                    Aucune donnée disponible
                </div>
            @endif
        </div>
    </div>

    <!-- Top membres -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-900">Top membres</h2>
                <p class="text-sm text-slate-500">Par temps d'entraînement</p>
            </div>
            <a href="{{ route('statistics.members') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                Voir tous →
            </a>
        </div>
        
        @if($topMembers->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($topMembers as $index => $member)
                    <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                        <!-- Rank -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-slate-200 text-slate-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-slate-100 text-slate-500')) }}">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                            {{ strtoupper(substr($member->user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->user->name)[1] ?? '', 0, 1)) }}
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-slate-900 truncate">{{ $member->user->name }}</div>
                            <div class="flex items-center gap-2 text-sm">
                                @if($member->role)
                                    <span class="px-2 py-0.5 rounded text-xs font-bold" style="background-color: {{ $member->role->color }}15; color: {{ $member->role->color }};">
                                        {{ $member->role->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="text-right">
                            <div class="font-black text-slate-900">{{ $member->total_training_hours }}h</div>
                            <div class="text-xs text-slate-500">{{ $member->present_sessions }} séances</div>
                        </div>
                        
                        <!-- Attendance -->
                        <div class="w-20 hidden sm:block">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-12 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $member->attendance_rate }}%;"></div>
                                </div>
                                <span class="text-sm font-semibold text-slate-600 w-10 text-right">{{ $member->attendance_rate }}%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-slate-500">
                Aucune donnée disponible
            </div>
        @endif
    </div>

    <!-- Quick links -->
    <div class="grid sm:grid-cols-3 gap-4">
        <a href="{{ route('statistics.members') }}" class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white hover:shadow-lg hover:shadow-emerald-500/25 transition-all">
            <svg class="w-8 h-8 mb-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <div class="font-bold text-lg">Stats membres</div>
            <div class="text-emerald-200 text-sm">Voir les performances individuelles</div>
        </a>
        
        <a href="{{ route('statistics.trainings') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white hover:shadow-lg hover:shadow-blue-500/25 transition-all">
            <svg class="w-8 h-8 mb-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div class="font-bold text-lg">Stats séances</div>
            <div class="text-blue-200 text-sm">Analyser les entraînements</div>
        </a>
        
        <a href="{{ route('planning.index') }}" class="bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl p-5 text-white hover:shadow-lg hover:shadow-violet-500/25 transition-all">
            <svg class="w-8 h-8 mb-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <div class="font-bold text-lg">Nouvelle séance</div>
            <div class="text-violet-200 text-sm">Planifier un entraînement</div>
        </a>
    </div>
</div>
@endsection

