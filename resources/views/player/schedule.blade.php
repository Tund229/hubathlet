@extends('layouts.dashboard-player')

@section('title', 'Mon planning')
@section('description', 'Consultez votre planning d\'entraînements')

@push('styles')
<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    /* FullCalendar Custom Styles */
    .fc {
        --fc-border-color: #e2e8f0;
        --fc-today-bg-color: #f0fdf4;
        --fc-page-bg-color: transparent;
        font-family: 'Inter', sans-serif;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }
    .fc .fc-button {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        text-transform: capitalize;
    }
    .fc .fc-button:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #10b981;
        border-color: #10b981;
        color: white;
    }
    .fc .fc-daygrid-day-number {
        font-weight: 600;
        color: #64748b;
        padding: 8px;
    }
    .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background-color: #10b981;
        color: white;
        border-radius: 9999px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fc-event {
        border-radius: 0.5rem !important;
        border: none !important;
        padding: 4px 8px !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .fc-event:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .fc .fc-daygrid-event-dot {
        display: none;
    }
    .fc-h-event .fc-event-main {
        color: white;
    }
    .fc .fc-col-header-cell-cushion {
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 12px 0;
    }
    .fc .fc-scrollgrid {
        border-radius: 1rem;
        overflow: hidden;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc .fc-timegrid-slot-label-cushion {
        font-size: 0.75rem;
        color: #94a3b8;
    }
    .fc-direction-ltr .fc-timegrid-slot-label-frame {
        text-align: right;
    }
    
    @media (max-width: 640px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 0.75rem;
        }
        .fc .fc-toolbar-title {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Mon planning</h1>
            <p class="text-slate-500 mt-1">Consulte tes prochains entraînements</p>
        </div>
        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl">
            <button onclick="setView('list')" id="btn-list" class="px-3 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
            </button>
            <button onclick="setView('calendar')" id="btn-calendar" class="px-3 py-2 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:text-slate-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats rapides -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm text-center">
            <div class="text-2xl font-black text-emerald-600">{{ $stats['upcoming'] ?? 0 }}</div>
            <div class="text-xs text-slate-500">À venir</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm text-center">
            <div class="text-2xl font-black text-blue-600">{{ $stats['this_week'] ?? 0 }}</div>
            <div class="text-xs text-slate-500">Cette semaine</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm text-center">
            <div class="text-2xl font-black text-violet-600">{{ $stats['this_month'] ?? 0 }}</div>
            <div class="text-xs text-slate-500">Ce mois</div>
        </div>
    </div>

    <!-- Vue Calendrier -->
    <div id="calendar-view" class="hidden">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6">
            <div id="calendar"></div>
        </div>
        
        <!-- Légende -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mt-4">
            <div class="flex flex-wrap items-center gap-4">
                <span class="text-sm font-bold text-slate-700">Légende :</span>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-sm text-slate-600">Entraînement</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-sm text-slate-600">Match</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-violet-500"></div>
                    <span class="text-sm text-slate-600">Événement</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <span class="text-sm text-slate-600">Réunion</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue Liste -->
    <div id="list-view">
        <!-- Séances du jour -->
        @if($todayTrainings->count() > 0)
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white mb-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-bold">Aujourd'hui</span>
                </div>
                <div class="space-y-3">
                    @foreach($todayTrainings as $training)
                        <a href="{{ route('planning.show', $training) }}" class="block bg-white/10 backdrop-blur rounded-xl p-4 hover:bg-white/20 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-bold">{{ $training->title }}</div>
                                    <div class="text-emerald-200 text-sm">{{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}</div>
                                </div>
                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold bg-white/20">
                                    {{ $training->type_label }}
                                </span>
                            </div>
                            @if($training->location)
                                <div class="flex items-center gap-1 mt-2 text-emerald-200 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
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
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-6">
            <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl inline-flex">
                <a href="{{ route('player.schedule', ['filter' => 'upcoming']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ ($filter ?? 'upcoming') === 'upcoming' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    À venir
                </a>
                <a href="{{ route('player.schedule', ['filter' => 'week']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ ($filter ?? '') === 'week' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semaine
                </a>
                <a href="{{ route('player.schedule', ['filter' => 'month']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ ($filter ?? '') === 'month' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Mois
                </a>
                <a href="{{ route('player.schedule', ['filter' => 'past']) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ ($filter ?? '') === 'past' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Passées
                </a>
            </div>
        </div>

        <!-- Liste des séances -->
        <div class="space-y-3">
            @forelse($trainings as $training)
                @php
                    $isToday = $training->date->isToday();
                    $isPast = $training->date->isPast() && !$isToday;
                    $myStatus = $training->pivot->status ?? 'registered';
                @endphp
                <a href="{{ route('planning.show', $training) }}" 
                   class="block bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all {{ $isPast ? 'opacity-60' : '' }}">
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center gap-4">
                            <!-- Date -->
                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center {{ $isToday ? 'bg-emerald-500 text-white' : 'bg-slate-100' }}">
                                <span class="text-xs font-medium {{ $isToday ? 'text-emerald-200' : 'text-slate-500' }}">{{ $training->date->translatedFormat('M') }}</span>
                                <span class="text-xl font-black">{{ $training->date->format('d') }}</span>
                            </div>
                            
                            <!-- Infos -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $training->type_color }};"></span>
                                    <h3 class="font-bold text-slate-900 truncate">{{ $training->title }}</h3>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    </span>
                                    @if($training->location)
                                        <span class="hidden sm:flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $training->location }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Mon statut -->
                            <div class="flex items-center gap-3">
                                @if($isPast && $training->status === 'completed')
                                    @php
                                        $statusConfig = match($myStatus) {
                                            'present' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'label' => 'Présent'],
                                            'late' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'label' => 'Retard'],
                                            'absent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Absent'],
                                            'excused' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Excusé'],
                                            default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'label' => 'Inscrit'],
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-lg text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                @endif
                                
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
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
                    <p class="text-slate-500">Pas de séance prévue pour cette période</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($trainings->hasPages())
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mt-6">
                {{ $trainings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js'></script>

<script>
    let calendar = null;
    let currentView = localStorage.getItem('playerScheduleView') || 'list';
    
    const events = @json($calendarEvents ?? []);
    
    document.addEventListener('DOMContentLoaded', function() {
        initCalendar();
        setView(currentView);
    });
    
    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine',
                list: 'Liste'
            },
            events: events,
            eventClick: function(info) {
                window.location.href = info.event.url;
            },
            eventDidMount: function(info) {
                info.el.title = `${info.event.title}\n${info.event.extendedProps.time || ''}\n${info.event.extendedProps.location || ''}`;
            },
            height: 'auto',
            navLinks: true,
            editable: false,
            dayMaxEvents: 3,
            moreLinkText: 'autres',
            noEventsText: 'Aucune séance à afficher',
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        });
        
        calendar.render();
    }
    
    function setView(view) {
        currentView = view;
        localStorage.setItem('playerScheduleView', view);
        
        const listView = document.getElementById('list-view');
        const calendarView = document.getElementById('calendar-view');
        const btnList = document.getElementById('btn-list');
        const btnCalendar = document.getElementById('btn-calendar');
        
        if (view === 'list') {
            listView.classList.remove('hidden');
            calendarView.classList.add('hidden');
            btnList.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            btnList.classList.remove('text-slate-600');
            btnCalendar.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            btnCalendar.classList.add('text-slate-600');
        } else {
            listView.classList.add('hidden');
            calendarView.classList.remove('hidden');
            btnCalendar.classList.add('bg-white', 'text-slate-900', 'shadow-sm');
            btnCalendar.classList.remove('text-slate-600');
            btnList.classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
            btnList.classList.add('text-slate-600');
            
            if (calendar) {
                setTimeout(() => calendar.updateSize(), 100);
            }
        }
    }
</script>
@endpush
