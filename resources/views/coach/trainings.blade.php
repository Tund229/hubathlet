@extends('layouts.dashboard-coach')

@section('title', 'Mes séances')
@section('description', 'Gérez vos séances d\'entraînement')

@push('styles')
@include('components.calendar-styles')
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Mes séances</h1>
            <p class="text-slate-500 mt-1">Gérez vos entraînements et suivez les présences</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Toggle Vue -->
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
            
            <a href="{{ route('planning.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Nouvelle séance</span>
            </a>
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
        <!-- Filtres -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Période</label>
                    <select name="period" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                        <option value="">Toutes les séances</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="upcoming" {{ request('period') == 'upcoming' ? 'selected' : '' }}>À venir</option>
                    </select>
                </div>
                
                <div class="flex-1">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Type</label>
                    <select name="type" onchange="this.form.submit()" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 appearance-none">
                        <option value="">Tous les types</option>
                        @foreach($trainingTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <a href="{{ route('coach.trainings') }}" class="px-4 py-3 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl font-semibold transition-colors">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des séances -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mt-6">
            @if($trainings->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Aucune séance trouvée</h3>
                    <p class="text-slate-500 mb-6">Créez votre première séance d'entraînement</p>
                    <a href="{{ route('planning.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer une séance
                    </a>
                </div>
            @else
                <!-- Desktop -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Séance</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Horaire</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Participants</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($trainings as $training)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-white text-xs" style="background-color: {{ $training->type_color }};">
                                            <span class="font-bold uppercase">{{ $training->date->translatedFormat('M') }}</span>
                                            <span class="text-base font-black leading-none">{{ $training->date->format('d') }}</span>
                                        </div>
                                        <span class="text-sm text-slate-500">{{ $training->date->translatedFormat('l') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $training->title }}</div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background-color: {{ $training->type_color }}20; color: {{ $training->type_color }};">
                                        {{ $training->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $training->duration_formatted }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex -space-x-2">
                                        @foreach($training->participants->take(4) as $p)
                                        <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                        @endforeach
                                        @if($training->participants->count() > 4)
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-500">
                                            +{{ $training->participants->count() - 4 }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $training->participants->count() }} participant(s)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $training->status_color }}">
                                        {{ $training->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('coach.attendance', $training) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition-colors shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            Appel
                                        </a>
                                        <a href="{{ route('planning.edit', $training) }}" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Mobile -->
                <div class="lg:hidden divide-y divide-slate-100">
                    @foreach($trainings as $training)
                    <a href="{{ route('coach.attendance', $training) }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                        <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center text-white" style="background-color: {{ $training->type_color }};">
                            <span class="text-[10px] font-bold uppercase opacity-80">{{ $training->date->translatedFormat('M') }}</span>
                            <span class="text-lg font-black leading-none">{{ $training->date->format('d') }}</span>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900">{{ $training->title }}</div>
                            <div class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($training->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($training->end_time)->format('H:i') }}
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $training->status_color }}">{{ $training->status_label }}</span>
                                <span class="text-xs text-slate-400">{{ $training->participants->count() }} participants</span>
                            </div>
                        </div>
                        
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($trainings->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $trainings->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.calendar-scripts')

<script>
    let calendar = null;
    let currentView = localStorage.getItem('coachTrainingsView') || 'list';
    
    const events = @json($calendarEvents ?? []);
    
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            calendar = initCalendarWithTooltips(calendarEl, events);
        }
        setView(currentView);
    });
    
    function setView(view) {
        currentView = view;
        localStorage.setItem('coachTrainingsView', view);
        
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
