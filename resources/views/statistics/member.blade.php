@extends('layouts.dashboard')

@section('title', 'Stats ' . $member->name)
@section('description', 'Statistiques détaillées du membre')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('statistics.members') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="flex items-center gap-4">
                @php
                    $memberRole = $stats['role'] ?? null;
                @endphp
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-black text-white" style="background-color: {{ $memberRole->color ?? '#6B7280' }}">
                    {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $member->name }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        @if($memberRole)
                            <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold" style="background-color: {{ $memberRole->color }}15; color: {{ $memberRole->color }};">
                                {{ $memberRole->name }}
                            </span>
                        @endif
                        <span class="text-slate-400 text-sm">{{ $member->email }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Modifier le profil -->
            <a href="{{ route('members.edit', $member) }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="hidden sm:inline">Modifier</span>
            </a>
            
            <!-- Period Filter -->
            <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-200 shadow-sm">
                <a href="{{ route('statistics.member', ['member' => $member, 'period' => 'month']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'month' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Mois
                </a>
                <a href="{{ route('statistics.member', ['member' => $member, 'period' => 'year']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'year' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Année
                </a>
                <a href="{{ route('statistics.member', ['member' => $member, 'period' => 'all']) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'all' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Tout
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Taux de présence -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl {{ $stats['attendance_rate'] >= 80 ? 'bg-emerald-100' : ($stats['attendance_rate'] >= 50 ? 'bg-amber-100' : 'bg-red-100') }} flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $stats['attendance_rate'] >= 80 ? 'text-emerald-600' : ($stats['attendance_rate'] >= 50 ? 'text-amber-600' : 'text-red-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black {{ $stats['attendance_rate'] >= 80 ? 'text-emerald-600' : ($stats['attendance_rate'] >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $stats['attendance_rate'] }}%</div>
            <div class="text-sm text-slate-500">Taux de présence</div>
        </div>

        <!-- Séances -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['present_sessions'] }}</div>
            <div class="text-sm text-slate-500">Séances présent / {{ $stats['total_sessions'] }}</div>
        </div>

        <!-- Temps d'entraînement -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['total_training_hours'] }}<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500">Temps d'entraînement</div>
        </div>

        <!-- Série -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['current_streak'] }}</div>
            <div class="text-sm text-slate-500">Série actuelle</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Progression mensuelle -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Progression mensuelle</h2>
            
            <div class="h-48 flex items-end justify-between gap-3">
                @foreach($monthlyProgress as $month)
                    @php
                        $maxHours = collect($monthlyProgress)->max('hours') ?: 1;
                        $height = ($month['hours'] / $maxHours) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <span class="text-xs font-bold text-slate-900">{{ $month['hours'] }}h</span>
                        <div class="w-full bg-emerald-100 rounded-t-lg relative" style="height: {{ max($height, 8) }}%;">
                            <div class="absolute inset-0 bg-emerald-500 rounded-t-lg"></div>
                        </div>
                        <div class="text-center">
                            <span class="text-xs font-medium text-slate-900 block">{{ $month['month'] }}</span>
                            <span class="text-xs text-slate-400">{{ $month['sessions'] }} séances</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Résumé global -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 text-white">
            <h2 class="font-bold mb-6">Depuis le début</h2>
            
            <div class="space-y-6">
                <div>
                    <div class="text-4xl font-black">{{ $stats['all_time_hours'] }}<span class="text-xl">h</span></div>
                    <div class="text-slate-400">Temps total d'entraînement</div>
                </div>
                
                <div>
                    <div class="text-4xl font-black">{{ $stats['all_time_sessions'] }}</div>
                    <div class="text-slate-400">Séances participées</div>
                </div>
                
                @if($stats['last_presence'])
                    <div class="pt-4 border-t border-slate-700">
                        <div class="text-sm text-slate-400">Dernière présence</div>
                        <div class="font-semibold">{{ $stats['last_presence']->format('d/m/Y') }}</div>
                        <div class="text-slate-400 text-sm">{{ $stats['last_presence']->diffForHumans() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Historique des présences -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Historique des présences</h2>
        </div>
        
        @if($attendanceHistory->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($attendanceHistory as $attendance)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex flex-col items-center justify-center">
                                <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($attendance->date)->format('M') }}</span>
                                <span class="text-lg font-black text-slate-900">{{ \Carbon\Carbon::parse($attendance->date)->format('d') }}</span>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $attendance->title }}</div>
                                <div class="text-sm text-slate-500">
                                    {{ \Carbon\Carbon::parse($attendance->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($attendance->end_time)->format('H:i') }}
                                    @if($attendance->location)
                                        • {{ $attendance->location }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            @switch($attendance->attendance_status)
                                @case('present')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Présent
                                    </span>
                                    @break
                                @case('absent')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Absent
                                    </span>
                                    @break
                                @case('excused')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-sm font-bold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Excusé
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold">
                                        Inscrit
                                    </span>
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucune participation</h3>
                <p class="text-slate-500">Ce membre n'a pas encore participé à des séances</p>
            </div>
        @endif
    </div>
</div>
@endsection

