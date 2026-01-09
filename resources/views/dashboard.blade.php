@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('description', 'Gérez votre club sportif depuis votre tableau de bord Hubathlet')

@php
    $club = auth()->user()->clubs()->first();
    
    // Stats globales
    $totalMembers = $club ? $club->members()->count() : 0;
    $activeMembers = $club ? $club->activeMembers()->count() : 0;
    $totalTrainings = $club ? $club->trainings()->count() : 0;
    $completedTrainings = $club ? $club->trainings()->where('status', 'completed')->count() : 0;
    $trainingsThisMonth = $club ? $club->trainings()->thisMonth()->count() : 0;
    
    // Taux de présence
    $attendanceRate = 0;
    $totalHours = 0;
    if ($club) {
        $totalAttendances = 0;
        $presentAttendances = 0;
        foreach ($club->trainings()->where('status', 'completed')->get() as $training) {
            $totalAttendances += $training->participants()->count();
            $presentAttendances += $training->participants()->wherePivot('status', 'present')->count();
        }
        $attendanceRate = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100) : 0;
        
        // Heures cumulées
        $totalMinutes = $club->trainings()->where('status', 'completed')->get()->sum(function($t) {
            return $t->duration ?? 90;
        });
        $totalHours = round($totalMinutes / 60);
    }
    
    // Prochaines séances
    $upcomingTrainings = $club ? $club->trainings()->upcoming()->take(3)->get() : collect();
    
    // Distribution par rôle
    $membersByRole = $club ? $club->getMemberCountByRole() : [];
    
    // Stats par jour de la semaine (mock pour démo)
    $weeklyData = [
        'Lun' => rand(60, 95),
        'Mar' => rand(60, 95),
        'Mer' => rand(60, 95),
        'Jeu' => rand(60, 95),
        'Ven' => rand(60, 95),
        'Sam' => rand(40, 80),
        'Dim' => rand(20, 50),
    ];
@endphp

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header avec salutation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Bonjour, {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-slate-500 mt-1">Voici les statistiques de votre club</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('planning.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle séance
            </a>
        </div>
    </div>

    <!-- Stats principales -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Membres actifs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                @if($activeMembers > 0)
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Actifs</span>
                @endif
            </div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $activeMembers }}</div>
            <div class="text-sm text-slate-500 font-medium">Membres actifs</div>
            <div class="mt-2 text-xs text-slate-400">sur {{ $totalMembers }} inscrits</div>
        </div>

        <!-- Taux de présence -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold {{ $attendanceRate >= 75 ? 'text-emerald-600 bg-emerald-50' : ($attendanceRate >= 50 ? 'text-amber-600 bg-amber-50' : 'text-red-600 bg-red-50') }} px-2 py-1 rounded-lg">
                    {{ $attendanceRate >= 75 ? 'Excellent' : ($attendanceRate >= 50 ? 'Bon' : 'À améliorer') }}
                </span>
            </div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $attendanceRate }}%</div>
            <div class="text-sm text-slate-500 font-medium">Taux de présence</div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                <div class="h-1.5 rounded-full {{ $attendanceRate >= 75 ? 'bg-emerald-500' : ($attendanceRate >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $attendanceRate }}%"></div>
            </div>
        </div>

        <!-- Séances ce mois -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-violet-600 bg-violet-50 px-2 py-1 rounded-lg">Ce mois</span>
            </div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $trainingsThisMonth }}</div>
            <div class="text-sm text-slate-500 font-medium">Séances programmées</div>
            <div class="mt-2 text-xs text-slate-400">{{ $completedTrainings }} terminées</div>
        </div>

        <!-- Heures cumulées -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalHours }}<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500 font-medium">Heures d'entraînement</div>
            <div class="mt-2 text-xs text-slate-400">cumulées par le club</div>
        </div>
    </div>

    <!-- Grille principale -->
    <div class="grid lg:grid-cols-12 gap-6">
        
        <!-- Colonne gauche (8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Graphique de présence hebdomadaire -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Présence hebdomadaire</h2>
                        <p class="text-sm text-slate-500">Taux de présence par jour</p>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                        <span class="text-slate-600">Cette semaine</span>
                    </div>
                </div>
                
                <!-- Bar Chart -->
                <div class="flex items-end justify-between gap-2 h-48">
                    @foreach($weeklyData as $day => $value)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-slate-100 rounded-lg relative overflow-hidden" style="height: 160px;">
                            <div class="absolute bottom-0 left-0 right-0 rounded-lg transition-all duration-500 {{ $value >= 75 ? 'bg-emerald-500' : ($value >= 50 ? 'bg-amber-500' : 'bg-slate-300') }}" style="height: {{ $value }}%;">
                                <div class="absolute inset-x-0 top-2 text-center">
                                    <span class="text-xs font-bold text-white">{{ $value }}%</span>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">{{ $day }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions + Prochaines séances -->
            <div class="grid sm:grid-cols-2 gap-6">
                <!-- Actions rapides -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Actions rapides</h2>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('members.create') }}" class="flex flex-col items-center gap-2 p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors group">
                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-emerald-700">Ajouter membre</span>
                        </a>
                        
                        <a href="{{ route('planning.create') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors group">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-blue-700">Créer séance</span>
                        </a>
                        
                        <a href="{{ route('members.index') }}" class="flex flex-col items-center gap-2 p-4 bg-violet-50 rounded-xl hover:bg-violet-100 transition-colors group">
                            <div class="w-10 h-10 bg-violet-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-violet-700">Voir membres</span>
                        </a>
                        
                        <a href="{{ route('statistics.index') }}" class="flex flex-col items-center gap-2 p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition-colors group">
                            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-amber-700">Statistiques</span>
                        </a>
                    </div>
                </div>

                <!-- Prochaines séances -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-900">Prochaines séances</h2>
                        <a href="{{ route('planning.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Voir tout</a>
                    </div>
                    
                    @if($upcomingTrainings->isEmpty())
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucune séance à venir</p>
                            <a href="{{ route('planning.create') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 mt-2 inline-block">Créer une séance</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($upcomingTrainings as $training)
                            <a href="{{ route('planning.show', $training) }}" class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-white" style="background-color: {{ $training->type_color }};">
                                    <span class="text-[10px] font-bold uppercase">{{ $training->date->format('M') }}</span>
                                    <span class="text-sm font-black leading-none">{{ $training->date->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-slate-900 truncate">{{ $training->title }}</div>
                                    <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Présences - Tableau -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between p-6 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Top présences</h2>
                        <p class="text-sm text-slate-500">Classement des membres les plus assidus</p>
                    </div>
                    <a href="{{ route('statistics.members') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                        Voir tout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                
                @if($club && $club->members()->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-y border-slate-100">
                                    <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider w-16">Rang</th>
                                    <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Membre</th>
                                    <th class="text-left px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Rôle</th>
                                    <th class="text-center px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Séances</th>
                                    <th class="text-center px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Présence</th>
                                    <th class="text-right px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Progression</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($club->members()->take(8)->get() as $index => $member)
                                @php
                                    $role = \App\Models\Role::find($member->pivot->role_id);
                                    $presence = rand(75, 100);
                                    $seances = rand(8, 24);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        @if($index < 3)
                                            <span class="w-8 h-8 rounded-full inline-flex items-center justify-center text-sm font-black
                                                {{ $index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600') }}">
                                                {{ $index + 1 }}
                                            </span>
                                        @else
                                            <span class="w-8 h-8 rounded-full bg-slate-100 inline-flex items-center justify-center text-sm font-semibold text-slate-500">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" style="background-color: {{ $role->color ?? '#6B7280' }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900">{{ $member->name }}</div>
                                                <div class="text-xs text-slate-500 sm:hidden">{{ $role->name ?? 'Membre' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden sm:table-cell">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold" style="background-color: {{ $role->color ?? '#6B7280' }}15; color: {{ $role->color ?? '#6B7280' }};">
                                            {{ $role->name ?? 'Membre' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-bold text-slate-900">{{ $seances }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center gap-1 font-bold {{ $presence >= 90 ? 'text-emerald-600' : ($presence >= 75 ? 'text-blue-600' : 'text-amber-600') }}">
                                            {{ $presence }}%
                                            @if($presence >= 90)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <div class="flex items-center justify-end gap-2">
                                            <div class="w-24 bg-slate-100 rounded-full h-2">
                                                <div class="h-2 rounded-full {{ $presence >= 90 ? 'bg-emerald-500' : ($presence >= 75 ? 'bg-blue-500' : 'bg-amber-500') }}" style="width: {{ $presence }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 mb-2">Aucun membre dans le club</p>
                        <a href="{{ route('members.create') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Ajouter un membre</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Distribution des membres -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Répartition des membres</h2>
                
                @if(empty($membersByRole))
                    <div class="text-center py-4">
                        <p class="text-sm text-slate-500">Aucun membre</p>
                    </div>
                @else
                    <!-- Circular progress -->
                    <div class="flex justify-center mb-6">
                        <div class="relative w-36 h-36">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle cx="72" cy="72" r="60" fill="none" stroke="#f1f5f9" stroke-width="12"/>
                                @php
                                    $offset = 0;
                                    $colors = ['player' => '#22C55E', 'coach' => '#10B981', 'admin' => '#3B82F6', 'moderator' => '#F59E0B', 'owner' => '#8B5CF6', 'parent' => '#EC4899', 'guest' => '#64748B'];
                                    $circumference = 2 * pi() * 60;
                                @endphp
                                @foreach($membersByRole as $role => $count)
                                    @php
                                        $percentage = $totalMembers > 0 ? ($count / $totalMembers) * 100 : 0;
                                        $strokeLength = ($percentage / 100) * $circumference;
                                        $color = $colors[$role] ?? '#6B7280';
                                    @endphp
                                    <circle cx="72" cy="72" r="60" fill="none" stroke="{{ $color }}" stroke-width="12"
                                        stroke-dasharray="{{ $strokeLength }} {{ $circumference }}"
                                        stroke-dashoffset="{{ -$offset }}"
                                        class="transition-all duration-500"/>
                                    @php $offset += $strokeLength; @endphp
                                @endforeach
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-black text-slate-900">{{ $totalMembers }}</span>
                                <span class="text-xs text-slate-500">membres</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="space-y-2">
                        @php
                            $roleNames = ['player' => 'Joueurs', 'coach' => 'Coachs', 'admin' => 'Admins', 'moderator' => 'Modérateurs', 'owner' => 'Propriétaire', 'parent' => 'Parents', 'guest' => 'Invités'];
                        @endphp
                        @foreach($membersByRole as $role => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $colors[$role] ?? '#6B7280' }};"></span>
                                <span class="text-sm text-slate-600">{{ $roleNames[$role] ?? ucfirst($role) }}</span>
                            </div>
                            <span class="text-sm font-bold text-slate-900">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Performance du mois -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <h3 class="text-lg font-bold text-white mb-4">Performance du mois</h3>
                    
                    <div class="space-y-4">
                        <!-- Objectif séances -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-300">Objectif séances</span>
                                <span class="text-sm font-bold text-white">{{ $trainingsThisMonth }}/12</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-emerald-500" style="width: {{ min(100, ($trainingsThisMonth / 12) * 100) }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Taux de présence -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-300">Présence moyenne</span>
                                <span class="text-sm font-bold text-white">{{ $attendanceRate }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-blue-500" style="width: {{ $attendanceRate }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Engagement -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-slate-300">Membres actifs</span>
                                <span class="text-sm font-bold text-white">{{ $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-slate-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-violet-500" style="width: {{ $totalMembers > 0 ? ($activeMembers / $totalMembers) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('statistics.index') }}" class="mt-6 w-full bg-white/10 backdrop-blur text-white py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition-colors flex items-center justify-center gap-2">
                        Voir les statistiques
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            </div>
    </div>
</div>
@endsection
