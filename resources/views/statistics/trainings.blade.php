@extends('layouts.dashboard')

@section('title', 'Statistiques séances')
@section('description', 'Analyse des entraînements')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('statistics.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Stats séances</h1>
                <p class="text-slate-500 mt-1">Analyse des entraînements</p>
            </div>
        </div>
        
        <!-- Period Filter -->
        <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-200 shadow-sm">
            <a href="{{ route('statistics.trainings', ['period' => 'month']) }}" 
               class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'month' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                Mois
            </a>
            <a href="{{ route('statistics.trainings', ['period' => 'year']) }}" 
               class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'year' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                Année
            </a>
            <a href="{{ route('statistics.trainings', ['period' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'all' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                Tout
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</div>
            <div class="text-sm text-slate-500">Total séances</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-emerald-600">{{ $stats['completed'] }}</div>
            <div class="text-sm text-slate-500">Terminées</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-sm text-slate-500">Annulées</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-slate-900">{{ round($stats['total_duration'] / 60, 1) }}h</div>
            <div class="text-sm text-slate-500">Durée totale</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-slate-900">{{ $stats['avg_participants'] }}</div>
            <div class="text-sm text-slate-500">Moy. participants</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-emerald-600">{{ $stats['avg_attendance_rate'] }}%</div>
            <div class="text-sm text-slate-500">Moy. présence</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        
        <!-- Par jour de la semaine -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Répartition par jour</h2>
            
            <div class="space-y-3">
                @php
                    $maxDay = collect($dayStats)->max('count') ?: 1;
                @endphp
                @foreach($dayStats as $stat)
                    <div class="flex items-center gap-3">
                        <span class="w-8 text-sm font-semibold text-slate-600">{{ $stat['day'] }}</span>
                        <div class="flex-1 h-6 bg-slate-100 rounded-lg overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-lg flex items-center justify-end pr-2" style="width: {{ ($stat['count'] / $maxDay) * 100 }}%;">
                                @if($stat['count'] > 0)
                                    <span class="text-xs font-bold text-white">{{ $stat['count'] }}</span>
                                @endif
                            </div>
                        </div>
                        @if($stat['count'] === 0)
                            <span class="text-xs text-slate-400 w-6 text-right">0</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Par créneau horaire -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-900 mb-6">Répartition par horaire</h2>
            
            <div class="space-y-4">
                @php
                    $maxTime = collect($timeStats)->max('count') ?: 1;
                    $colors = ['#F59E0B', '#3B82F6', '#8B5CF6'];
                @endphp
                @foreach($timeStats as $index => $stat)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">{{ $stat['label'] }}</span>
                            <span class="text-sm font-bold text-slate-900">{{ $stat['count'] }} séances</span>
                        </div>
                        <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="width: {{ ($stat['count'] / $maxTime) * 100 }}%; background-color: {{ $colors[$index] ?? '#6B7280' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-4 bg-slate-50 rounded-xl">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-slate-600">
                        @php
                            $mostPopular = collect($timeStats)->sortByDesc('count')->first();
                        @endphp
                        Le créneau <strong>{{ strtolower($mostPopular['label']) }}</strong> est le plus populaire
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des séances terminées -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Séances terminées</h2>
        </div>
        
        @if($trainings->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Séance</th>
                            <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Type</th>
                            <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Participants</th>
                            <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Présence</th>
                            <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Durée</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($trainings as $training)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $training->date->format('d/m/Y') }}</div>
                                    <div class="text-sm text-slate-500">{{ $training->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('planning.show', $training) }}" class="font-semibold text-slate-900 hover:text-emerald-600">
                                        {{ $training->title }}
                                    </a>
                                    <div class="text-sm text-slate-500">
                                        {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center hidden sm:table-cell">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold" style="background-color: {{ $training->type_color }}15; color: {{ $training->type_color }};">
                                        {{ $training->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="font-bold text-slate-900">{{ $training->participants->count() }}</div>
                                    @if($training->max_participants)
                                        <div class="text-xs text-slate-500">/ {{ $training->max_participants }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-12 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $training->attendance_rate }}%;"></div>
                                        </div>
                                        <span class="text-sm font-bold text-slate-600">{{ $training->attendance_rate }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right hidden md:table-cell">
                                    <span class="font-semibold text-slate-900">{{ $training->duration_formatted }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($trainings->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $trainings->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucune séance terminée</h3>
                <p class="text-slate-500">Les séances terminées apparaîtront ici</p>
            </div>
        @endif
    </div>
</div>
@endsection

