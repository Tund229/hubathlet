@extends('layouts.dashboard-player')

@section('title', 'Mes statistiques')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Mes statistiques</h1>
            <p class="text-sm text-slate-500">Progression et performances</p>
        </div>
    </div>

    {{-- Stats principales (compact) --}}
    <div class="grid grid-cols-4 gap-3">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $attendanceRate ?? 0 }}%</p>
            <p class="text-xs text-slate-500">Présence</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <p class="text-2xl font-bold text-slate-900">{{ $totalHours ?? 0 }}h</p>
            <p class="text-xs text-slate-500">Temps total</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <p class="text-2xl font-bold text-slate-900">{{ $presentCount ?? 0 }}<span class="text-sm text-slate-400">/{{ $totalTrainings ?? 0 }}</span></p>
            <p class="text-xs text-slate-500">Séances</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100 text-center">
            <p class="text-2xl font-bold text-slate-900">{{ $teamAverage ?? 0 }}%</p>
            <p class="text-xs text-slate-500">Moy. équipe</p>
        </div>
    </div>

    {{-- Grille graphiques --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-800 text-sm mb-3">Évolution de la présence</h3>
            <div class="h-48">
                <canvas id="presenceChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-800 text-sm mb-3">Heures d'entraînement</h3>
            <div class="h-48">
                <canvas id="hoursChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Répartition des statuts + Par type --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        
        {{-- Répartition des statuts --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-800 text-sm mb-3">Répartition</h3>
            
            @if(($totalTrainings ?? 0) > 0)
                <div class="flex items-center gap-4">
                    <div class="w-28 h-28 flex-shrink-0">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex-1 space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Présent</span>
                            <span class="font-bold">{{ $presentCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Retard</span>
                            <span class="font-bold">{{ $lateCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Absent</span>
                            <span class="font-bold">{{ $absentCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-slate-500 text-sm">Aucune donnée</div>
            @endif
        </div>
        
        {{-- Stats par type de séance --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="font-semibold text-slate-800 text-sm mb-3">Par type</h3>
            
            <div class="space-y-2">
                @php
                    $typeConfig = [
                        'training' => ['label' => 'Entraînements', 'color' => 'emerald'],
                        'match' => ['label' => 'Matchs', 'color' => 'blue'],
                        'event' => ['label' => 'Événements', 'color' => 'violet'],
                        'meeting' => ['label' => 'Réunions', 'color' => 'amber'],
                    ];
                @endphp
                
                @foreach($statsByType ?? [] as $type => $stats)
                    @php $config = $typeConfig[$type] ?? ['label' => $type, 'color' => 'slate']; @endphp
                    
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 w-24 truncate">{{ $config['label'] }}</span>
                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-{{ $config['color'] }}-500 rounded-full" style="width: {{ $stats['rate'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-700 w-10 text-right">{{ $stats['rate'] ?? 0 }}%</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Historique récent --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <h3 class="font-semibold text-slate-800 text-sm mb-3">Historique récent</h3>
        
        @if(($recentTrainings ?? collect())->count() > 0)
            <div class="space-y-2">
                @foreach($recentTrainings->take(5) as $training)
                    @php
                        $statusConfig = [
                            'present' => ['label' => 'OK', 'class' => 'bg-emerald-100 text-emerald-700'],
                            'late' => ['label' => 'Retard', 'class' => 'bg-amber-100 text-amber-700'],
                            'absent' => ['label' => 'Absent', 'class' => 'bg-red-100 text-red-700'],
                            'excused' => ['label' => 'Excusé', 'class' => 'bg-blue-100 text-blue-700'],
                        ];
                        $status = $statusConfig[$training->pivot->status] ?? ['label' => '?', 'class' => 'bg-slate-100 text-slate-700'];
                    @endphp
                    
                    <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="text-xs text-slate-500 w-16 flex-shrink-0">{{ $training->date->format('d/m') }}</span>
                            <span class="text-sm text-slate-800 truncate">{{ $training->title }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded text-xs font-bold {{ $status['class'] }} flex-shrink-0">{{ $status['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 text-slate-500 text-sm">Aucune participation</div>
        @endif
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution de la présence
    const presenceCtx = document.getElementById('presenceChart');
    if (presenceCtx) {
        new Chart(presenceCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($monthlyStats ?? [])) !!},
                datasets: [{
                    label: 'Taux de présence',
                    data: {!! json_encode(array_values($monthlyStats ?? [])) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f1f5f9' },
                        ticks: { 
                            callback: function(value) { return value + '%'; },
                            font: { weight: 600 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 600 } }
                    }
                }
            }
        });
    }
    
    // Graphique des heures
    const hoursCtx = document.getElementById('hoursChart');
    if (hoursCtx) {
        new Chart(hoursCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($monthlyHours ?? [])) !!},
                datasets: [{
                    label: 'Heures',
                    data: {!! json_encode(array_values($monthlyHours ?? [])) !!},
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { 
                            callback: function(value) { return value + 'h'; },
                            font: { weight: 600 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 600 } }
                    }
                }
            }
        });
    }
    
    // Graphique donut des statuts
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        const presentOnly = {{ ($presentCount ?? 0) - ($lateCount ?? 0) }};
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Présent', 'En retard', 'Excusé', 'Absent'],
                datasets: [{
                    data: [presentOnly > 0 ? presentOnly : 0, {{ $lateCount ?? 0 }}, {{ $excusedCount ?? 0 }}, {{ $absentCount ?? 0 }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endsection
