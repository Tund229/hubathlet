@extends('layouts.dashboard-coach')

@section('title', 'Stats joueurs')
@section('description', 'Statistiques de vos joueurs')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Stats joueurs</h1>
            <p class="text-slate-500 mt-1">Suivez la progression et l'assiduité de vos joueurs</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500">
                Basé sur <span class="font-bold text-slate-900">{{ count($myTrainings) }}</span> séances
            </span>
        </div>
    </div>

    <!-- Stats globales -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $totalPlayers = count($playerStats);
            $avgAttendance = $totalPlayers > 0 ? round(collect($playerStats)->avg('attendance_rate')) : 0;
            $totalHours = round(collect($playerStats)->sum('total_hours'), 1);
            $bestPlayer = collect($playerStats)->first();
        @endphp
        
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $totalPlayers }}</div>
            <div class="text-sm text-slate-500 font-medium">Joueurs coachés</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $avgAttendance }}%</div>
            <div class="text-sm text-slate-500 font-medium">Présence moyenne</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $totalHours }}<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500 font-medium">Heures cumulées</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <div class="text-xl font-black text-slate-900 truncate">{{ $bestPlayer ? $bestPlayer['user']->name : '-' }}</div>
            <div class="text-sm text-slate-500 font-medium">Meilleure assiduité</div>
        </div>
    </div>

    <!-- Tableau des joueurs -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Classement des joueurs</h2>
        </div>
        
        @if(empty($playerStats))
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Aucune statistique disponible</h3>
                <p class="text-slate-500">Les statistiques apparaîtront après vos premières séances terminées</p>
            </div>
        @else
            <!-- Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Rang</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Joueur</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Séances</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Présence</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Temps total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Retards</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($playerStats as $index => $stat)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($index === 0)
                                    <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-amber-400/30">🥇</div>
                                @elseif($index === 1)
                                    <div class="w-8 h-8 bg-slate-400 rounded-full flex items-center justify-center text-white font-bold text-sm">🥈</div>
                                @elseif($index === 2)
                                    <div class="w-8 h-8 bg-amber-600 rounded-full flex items-center justify-center text-white font-bold text-sm">🥉</div>
                                @else
                                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 font-bold text-sm">{{ $index + 1 }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold">
                                        {{ strtoupper(substr($stat['user']->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $stat['user']->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $stat['user']->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $stat['user']->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    <span class="font-bold text-emerald-600">{{ $stat['present'] }}</span>
                                    <span class="text-slate-400">/</span>
                                    <span class="text-slate-600">{{ $stat['total'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-slate-100 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $stat['attendance_rate'] >= 80 ? 'bg-emerald-500' : ($stat['attendance_rate'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" 
                                             style="width: {{ $stat['attendance_rate'] }}%"></div>
                                    </div>
                                    <span class="font-bold {{ $stat['attendance_rate'] >= 80 ? 'text-emerald-600' : ($stat['attendance_rate'] >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                        {{ $stat['attendance_rate'] }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-slate-900">{{ $stat['total_hours'] }}h</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stat['late'] > 0)
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold">{{ $stat['late'] }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('statistics.member', $stat['user']) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-semibold hover:bg-slate-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Stats
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile -->
            <div class="lg:hidden divide-y divide-slate-100">
                @foreach($playerStats as $index => $stat)
                <div class="p-4">
                    <div class="flex items-center gap-4">
                        <!-- Rang -->
                        <div class="flex-shrink-0">
                            @if($index === 0)
                                <div class="w-10 h-10 bg-amber-400 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-amber-400/30">🥇</div>
                            @elseif($index === 1)
                                <div class="w-10 h-10 bg-slate-400 rounded-full flex items-center justify-center text-white font-bold">🥈</div>
                            @elseif($index === 2)
                                <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center text-white font-bold">🥉</div>
                            @else
                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 font-bold">{{ $index + 1 }}</div>
                            @endif
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900">{{ $stat['user']->name }}</div>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-sm text-slate-500">{{ $stat['present'] }}/{{ $stat['total'] }} séances</span>
                                <span class="text-sm font-bold {{ $stat['attendance_rate'] >= 80 ? 'text-emerald-600' : ($stat['attendance_rate'] >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                                    {{ $stat['attendance_rate'] }}%
                                </span>
                            </div>
                            <!-- Barre de progression -->
                            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $stat['attendance_rate'] >= 80 ? 'bg-emerald-500' : ($stat['attendance_rate'] >= 60 ? 'bg-amber-500' : 'bg-red-500') }}" 
                                     style="width: {{ $stat['attendance_rate'] }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Action -->
                        <a href="{{ route('statistics.member', $stat['user']) }}" class="p-2 bg-slate-100 rounded-lg text-slate-600 hover:bg-slate-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

