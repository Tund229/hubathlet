@extends('layouts.dashboard-player')

@section('title', 'Tableau de bord')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    {{-- Header avec carte de bienvenue (compact) --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500 rounded-2xl p-4 md:p-5">
        {{-- Motif décoratif --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white rounded-full translate-y-16 -translate-x-16"></div>
        </div>
        
        <div class="relative flex items-center justify-between gap-4">
            {{-- Infos joueur --}}
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-xl md:text-2xl font-black shadow-lg">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-xl object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    @if($membership && $membership->pivot->jersey_number)
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-white rounded-lg flex items-center justify-center shadow-md">
                            <span class="text-emerald-600 font-black text-sm">{{ $membership->pivot->jersey_number }}</span>
                        </div>
                    @endif
                </div>
                
                {{-- Nom et infos --}}
                <div class="text-white min-w-0">
                    <h1 class="text-lg md:text-xl font-black truncate">{{ $user->name }}</h1>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        @if($membership && $membership->pivot->position)
                            <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md text-xs font-semibold">
                                {{ $membership->pivot->position }}
                            </span>
                        @endif
                        <span class="px-2 py-0.5 bg-white/20 backdrop-blur rounded-md text-xs font-semibold truncate">
                            {{ $club->name }}
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Date du jour --}}
            <div class="text-white text-right flex-shrink-0 hidden sm:block">
                <p class="text-emerald-100 text-xs">{{ now()->translatedFormat('l') }}</p>
                <p class="text-lg md:text-xl font-black">{{ now()->translatedFormat('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Prochaine séance avec Countdown --}}
    @if($nextTraining)
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-slate-800">Prochaine séance</h2>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: {{ $nextTraining->type_color }}20; color: {{ $nextTraining->type_color }}">
                    {{ $nextTraining->type_label }}
                </span>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                {{-- Infos séance --}}
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-slate-900">{{ $nextTraining->title }}</h3>
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-slate-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">{{ $nextTraining->date->translatedFormat('l d F') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($nextTraining->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($nextTraining->end_time)->format('H:i') }}</span>
                        </div>
                        @if($nextTraining->location)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span class="font-medium">{{ $nextTraining->location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Countdown --}}
                <div id="countdown-container" class="flex gap-3" data-target="{{ $nextTraining->date->format('Y-m-d') }}T{{ $nextTraining->start_time }}">
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <span id="countdown-days" class="text-2xl md:text-3xl font-black text-white">0</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mt-2">JOURS</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <span id="countdown-hours" class="text-2xl md:text-3xl font-black text-white">0</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mt-2">HEURES</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-violet-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <span id="countdown-minutes" class="text-2xl md:text-3xl font-black text-white">0</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mt-2">MIN</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats rapides --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Taux de présence --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                @if($attendanceRate >= 80)
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                @endif
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $attendanceRate ?? 0 }}%</p>
            <p class="text-sm text-slate-500 font-medium">Présence</p>
        </div>
        
        {{-- Heures d'entraînement --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $totalHours ?? 0 }}h</p>
            <p class="text-sm text-slate-500 font-medium">Entraînement</p>
        </div>
        
        {{-- Séances effectuées --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $presentCount ?? 0 }}</p>
            <p class="text-sm text-slate-500 font-medium">Séances</p>
        </div>
        
        {{-- Classement --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                @if(($rank ?? 0) <= 3 && ($rank ?? 0) > 0)
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $rank === 1 ? 'bg-yellow-100' : ($rank === 2 ? 'bg-slate-100' : 'bg-orange-100') }}">
                        <svg class="w-5 h-5 {{ $rank === 1 ? 'text-yellow-600' : ($rank === 2 ? 'text-slate-500' : 'text-orange-600') }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                        </svg>
                    </div>
                @endif
            </div>
            <p class="text-3xl font-black text-slate-900">{{ $rank ?? 0 }}<span class="text-lg text-slate-400">/{{ $totalPlayers ?? 0 }}</span></p>
            <p class="text-sm text-slate-500 font-medium">Classement</p>
        </div>
    </div>

    {{-- Grille principale --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Colonne gauche (2 colonnes) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Séances de la semaine - Mini calendrier --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h2 class="text-lg font-bold text-slate-800">Ma semaine</h2>
                    </div>
                    <a href="{{ route('player.schedule') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                        Voir tout →
                    </a>
                </div>
                
                {{-- Jours de la semaine --}}
                <div class="grid grid-cols-7 gap-2">
                    @php
                        $startOfWeek = now()->startOfWeek();
                        $daysOfWeek = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                    @endphp
                    
                    @foreach($daysOfWeek as $index => $dayName)
                        @php
                            $currentDay = $startOfWeek->copy()->addDays($index);
                            $dayTrainings = $weekTrainings->filter(fn($t) => $t->date->isSameDay($currentDay));
                            $isToday = $currentDay->isToday();
                            $isPast = $currentDay->isPast() && !$isToday;
                        @endphp
                        
                        <div class="text-center">
                            <p class="text-xs font-bold text-slate-400 mb-2">{{ $dayName }}</p>
                            <div class="relative {{ $isToday ? 'bg-emerald-500 text-white' : ($isPast ? 'bg-slate-50 text-slate-400' : 'bg-slate-100 text-slate-700') }} rounded-2xl p-3 min-h-[80px] flex flex-col items-center">
                                <span class="text-lg font-black">{{ $currentDay->format('d') }}</span>
                                
                                @if($dayTrainings->count() > 0)
                                    <div class="mt-2 space-y-1 w-full">
                                        @foreach($dayTrainings->take(2) as $training)
                                            <div class="w-full h-1.5 rounded-full" style="background-color: {{ $training->type_color }}"></div>
                                        @endforeach
                                        @if($dayTrainings->count() > 2)
                                            <p class="text-[10px] font-bold">+{{ $dayTrainings->count() - 2 }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Légende --}}
                @if($weekTrainings->count() > 0)
                    <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-slate-100">
                        @foreach($weekTrainings->unique('type') as $training)
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $training->type_color }}"></div>
                                <span class="text-xs font-medium text-slate-600">{{ $training->type_label }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Séances du jour avec pointage rapide --}}
            @if($todayTrainings->count() > 0)
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-6 border border-emerald-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-800">Pointage du jour</h2>
                                <p class="text-sm text-emerald-600 font-medium">{{ $todayTrainings->count() }} séance(s) - Cliquez pour pointer</p>
                            </div>
                        </div>
                        <a href="{{ route('attendance.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                            Historique →
                        </a>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($todayTrainings as $training)
                            @php
                                $myStatus = $training->pivot->status ?? 'registered';
                                $alreadyCheckedIn = in_array($myStatus, ['present', 'late', 'absent']);
                                
                                // Configuration des badges
                                $badgeConfig = [
                                    'present' => ['class' => 'bg-emerald-100 text-emerald-700', 'text' => 'Présent', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'late' => ['class' => 'bg-amber-100 text-amber-700', 'text' => 'En retard', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'absent' => ['class' => 'bg-red-100 text-red-700', 'text' => 'Absent', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ];
                                $badge = $badgeConfig[$myStatus] ?? null;
                            @endphp
                            
                            <div class="bg-white rounded-2xl p-4 shadow-sm">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 flex-1 min-w-0">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $training->type_color }}20">
                                            <svg class="w-6 h-6" style="color: {{ $training->type_color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-slate-800 truncate">{{ $training->title }}</h3>
                                            <p class="text-sm text-slate-500">
                                                {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                                @if($training->location) • {{ $training->location }} @endif
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        @if($alreadyCheckedIn && $badge)
                                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold {{ $badge['class'] }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $badge['icon'] }}" />
                                                </svg>
                                                {{ $badge['text'] }}
                                            </span>
                                        @else
                                            <form action="{{ route('attendance.check-in', $training) }}" method="POST" class="check-in-form">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all hover:scale-105 shadow-lg shadow-emerald-200 check-in-btn">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Pointer
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('planning.show', $training) }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Prochaines séances --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h2 class="text-lg font-bold text-slate-800">Prochaines séances</h2>
                    </div>
                    <a href="{{ route('player.schedule') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">
                        Planning complet →
                    </a>
                </div>
                
                @if($upcomingTrainings->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingTrainings as $training)
                            <a href="{{ route('planning.show', $training) }}" class="flex items-center gap-4 p-4 rounded-2xl hover:bg-slate-50 transition-colors group">
                                {{-- Date badge --}}
                                <div class="w-14 h-14 rounded-xl bg-slate-100 flex flex-col items-center justify-center">
                                    <span class="text-xs font-bold text-slate-500 uppercase">{{ $training->date->translatedFormat('M') }}</span>
                                    <span class="text-xl font-black text-slate-800">{{ $training->date->format('d') }}</span>
                                </div>
                                
                                {{-- Infos --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $training->type_color }}"></span>
                                        <h3 class="font-bold text-slate-800 truncate">{{ $training->title }}</h3>
                                    </div>
                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $training->date->translatedFormat('l') }} • {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }}
                                        @if($training->location) • {{ $training->location }} @endif
                                    </p>
                                </div>
                                
                                {{-- Arrow --}}
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Aucune séance à venir</p>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Colonne droite --}}
        <div class="space-y-6">
            
            {{-- Objectif de présence --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                    <h2 class="text-lg font-bold text-slate-800">Objectif Présence</h2>
                </div>
                
                <div class="relative">
                    {{-- Cercle de progression --}}
                    <div class="relative w-40 h-40 mx-auto">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            {{-- Cercle de fond --}}
                            <circle cx="50" cy="50" r="45" stroke="#e2e8f0" stroke-width="8" fill="none" />
                            {{-- Cercle de progression --}}
                            <circle cx="50" cy="50" r="45" 
                                stroke="{{ ($goalProgress ?? 0) >= 100 ? '#10b981' : '#3b82f6' }}" 
                                stroke-width="8" 
                                fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="{{ 2 * 3.14159 * 45 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 45 * (1 - min(($goalProgress ?? 0), 100) / 100) }}"
                                class="transition-all duration-1000" />
                        </svg>
                        {{-- Texte central --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black text-slate-900">{{ $attendanceRate ?? 0 }}%</span>
                            <span class="text-sm text-slate-500">/ {{ $presenceGoal ?? 90 }}%</span>
                        </div>
                    </div>
                    
                    {{-- Message --}}
                    <div class="text-center mt-4">
                        @if(($goalProgress ?? 0) >= 100)
                            <div class="flex items-center justify-center gap-2 text-emerald-600 font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Objectif atteint !
                            </div>
                        @elseif(($goalProgress ?? 0) >= 80)
                            <div class="flex items-center justify-center gap-2 text-blue-600 font-bold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Presque là !
                            </div>
                        @else
                            <p class="text-slate-600 font-medium">Continue comme ça !</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Streak --}}
            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-3xl p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Série en cours</p>
                        <p class="text-4xl font-black mt-1">{{ $streak ?? 0 }}</p>
                        <p class="text-orange-100 text-sm">séances consécutives</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Dernières activités --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-bold text-slate-800">Historique récent</h2>
                </div>
                
                @if($recentActivities->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentActivities as $activity)
                            @php
                                $statusConfig = [
                                    'present' => ['svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />', 'color' => 'emerald', 'text' => 'Présent'],
                                    'late' => ['svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />', 'color' => 'amber', 'text' => 'En retard'],
                                    'absent' => ['svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />', 'color' => 'red', 'text' => 'Absent'],
                                    'excused' => ['svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />', 'color' => 'blue', 'text' => 'Excusé'],
                                ];
                                $status = $statusConfig[$activity->pivot->status] ?? ['svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />', 'color' => 'slate', 'text' => 'Inconnu'];
                            @endphp
                            
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
                                <div class="w-8 h-8 bg-{{ $status['color'] }}-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-{{ $status['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $status['svg'] !!}</svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $activity->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $activity->date->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="text-xs font-bold text-{{ $status['color'] }}-600 bg-{{ $status['color'] }}-100 px-2 py-1 rounded-full">
                                    {{ $status['text'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-slate-500 py-4">Aucune activité récente</p>
                @endif
            </div>
            
            </div>
    </div>
</div>

{{-- Script Countdown + Check-in --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown
    const container = document.getElementById('countdown-container');
    if (container) {
        const targetDate = new Date(container.dataset.target);
        
        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;
            
            if (isNaN(diff) || diff <= 0) {
                document.getElementById('countdown-days').textContent = '0';
                document.getElementById('countdown-hours').textContent = '0';
                document.getElementById('countdown-minutes').textContent = '0';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById('countdown-days').textContent = isNaN(days) ? '0' : days;
            document.getElementById('countdown-hours').textContent = isNaN(hours) ? '0' : hours;
            document.getElementById('countdown-minutes').textContent = isNaN(minutes) ? '0' : minutes;
        }
        
        updateCountdown();
        setInterval(updateCountdown, 60000);
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
                        'present': {
                            class: 'bg-emerald-100 text-emerald-700',
                            text: 'Présent',
                            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        },
                        'late': {
                            class: 'bg-amber-100 text-amber-700',
                            text: 'En retard',
                            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        },
                        'absent': {
                            class: 'bg-red-100 text-red-700',
                            text: 'Absent',
                            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                        }
                    };
                    
                    const config = statusConfig[data.status] || statusConfig['present'];
                    
                    // Remplacer le bouton par le badge de statut
                    const badge = document.createElement('span');
                    badge.className = `inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold ${config.class}`;
                    badge.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">${config.icon}</svg>${config.text}`;
                    
                    this.replaceWith(badge);
                    
                    // Animation
                    badge.style.transform = 'scale(0.8)';
                    badge.style.opacity = '0';
                    setTimeout(() => {
                        badge.style.transition = 'all 0.3s ease-out';
                        badge.style.transform = 'scale(1)';
                        badge.style.opacity = '1';
                    }, 50);
                    
                    // Afficher le message si absent
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
                console.error('Erreur:', error);
            });
        });
    });
});
</script>
@endsection
