@extends('layouts.dashboard')

@section('title', $planning->title)
@section('description', 'Détails de la séance')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('planning.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ $planning->title }}</h1>
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ $planning->status_color }}">
                        {{ $planning->status_label }}
                    </span>
                </div>
                <p class="text-sm text-slate-500">{{ $planning->date->format('l d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('planning.duplicate', $planning) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="hidden sm:inline">Dupliquer</span>
                </button>
            </form>
            <a href="{{ route('planning.edit', $planning) }}" class="px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>
            <form action="{{ route('planning.destroy', $planning) }}" method="POST" onsubmit="return confirm('Supprimer cette séance ?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Informations -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card principale -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="h-2" style="background-color: {{ $planning->color }};"></div>
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-bold mb-3" style="background-color: {{ $planning->type_color }}15; color: {{ $planning->type_color }};">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $planning->type_icon }}" />
                                </svg>
                                {{ $planning->type_label }}
                            </span>
                            @if($planning->description)
                                <p class="text-slate-600">{{ $planning->description }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid sm:grid-cols-2 gap-6">
                        <!-- Date & Heure -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-slate-500 mb-1">Date & Heure</div>
                                <div class="font-bold text-slate-900">{{ $planning->date->format('l d F Y') }}</div>
                                <div class="text-slate-600">{{ \Carbon\Carbon::parse($planning->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($planning->end_time)->format('H:i') }}</div>
                                <div class="text-sm text-slate-500">Durée: {{ $planning->duration_formatted }}</div>
                            </div>
                        </div>
                        
                        <!-- Lieu -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-slate-500 mb-1">Lieu</div>
                                @if($planning->location)
                                    <div class="font-bold text-slate-900">{{ $planning->location }}</div>
                                    @if($planning->address)
                                        <div class="text-slate-600">{{ $planning->address }}</div>
                                    @endif
                                @else
                                    <div class="text-slate-400">Non défini</div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Coach -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-slate-500 mb-1">Coach responsable</div>
                                @if($planning->coach)
                                    <div class="font-bold text-slate-900">{{ $planning->coach->name }}</div>
                                    <div class="text-slate-600">{{ $planning->coach->email }}</div>
                                @else
                                    <div class="text-slate-400">Non assigné</div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Participants -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-slate-500 mb-1">Participants</div>
                                <div class="font-bold text-slate-900">
                                    {{ $planning->participants->count() }}
                                    @if($planning->max_participants)
                                        / {{ $planning->max_participants }}
                                    @endif
                                    inscrits
                                </div>
                                @if($planning->status === 'completed')
                                    <div class="text-emerald-600">{{ $planning->presentParticipants()->count() }} présents</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($planning->notes)
                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <div class="text-xs font-medium text-slate-500 mb-2">Notes internes</div>
                            <p class="text-slate-600 text-sm">{{ $planning->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Liste des participants -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-bold text-slate-900">Participants ({{ $planning->participants->count() }})</h2>
                    
                    @if($availableMembers->count() > 0 && !$planning->isFull())
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" @click="open = !open" class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-semibold hover:bg-emerald-100 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Ajouter
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-slate-200 z-50 max-h-64 overflow-y-auto">
                                @foreach($availableMembers as $member)
                                    <form action="{{ route('planning.add-participant', $planning) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <button type="submit" class="w-full px-4 py-3 flex items-center gap-3 hover:bg-slate-50 text-left">
                                            <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-medium text-slate-700">{{ $member->name }}</span>
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                @if($planning->participants->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($planning->participants as $participant)
                            <div class="p-4 flex items-center justify-between hover:bg-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                        {{ strtoupper(substr($participant->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $participant->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $participant->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $participant->email }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <!-- Status de présence -->
                                    @if($planning->status === 'completed' || $planning->status === 'ongoing')
                                        <form action="{{ route('planning.update-attendance', [$planning, $participant]) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="status" value="present"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center {{ $participant->pivot->status === 'present' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400 hover:bg-emerald-100 hover:text-emerald-600' }} transition-colors"
                                                title="Présent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <button type="submit" name="status" value="absent"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center {{ $participant->pivot->status === 'absent' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400 hover:bg-red-100 hover:text-red-600' }} transition-colors"
                                                title="Absent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            <button type="submit" name="status" value="excused"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center {{ $participant->pivot->status === 'excused' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-400 hover:bg-amber-100 hover:text-amber-600' }} transition-colors"
                                                title="Excusé">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700">
                                            Inscrit
                                        </span>
                                    @endif
                                    
                                    <!-- Retirer -->
                                    <form action="{{ route('planning.remove-participant', [$planning, $participant]) }}" method="POST" onsubmit="return confirm('Retirer ce participant ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <p class="text-slate-500">Aucun participant inscrit</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Actions rapides -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Actions rapides</h2>
                
                <div class="space-y-2">
                    @if($planning->status === 'scheduled')
                        <form action="{{ route('planning.update', $planning) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $planning->title }}">
                            <input type="hidden" name="type" value="{{ $planning->type }}">
                            <input type="hidden" name="date" value="{{ $planning->date->format('Y-m-d') }}">
                            <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($planning->start_time)->format('H:i') }}">
                            <input type="hidden" name="end_time" value="{{ \Carbon\Carbon::parse($planning->end_time)->format('H:i') }}">
                            <input type="hidden" name="status" value="ongoing">
                            <button type="submit" class="w-full px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Démarrer la séance
                            </button>
                        </form>
                        
                        <form action="{{ route('planning.update', $planning) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $planning->title }}">
                            <input type="hidden" name="type" value="{{ $planning->type }}">
                            <input type="hidden" name="date" value="{{ $planning->date->format('Y-m-d') }}">
                            <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($planning->start_time)->format('H:i') }}">
                            <input type="hidden" name="end_time" value="{{ \Carbon\Carbon::parse($planning->end_time)->format('H:i') }}">
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="w-full px-4 py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Annuler la séance
                            </button>
                        </form>
                    @elseif($planning->status === 'ongoing')
                        <form action="{{ route('planning.update', $planning) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $planning->title }}">
                            <input type="hidden" name="type" value="{{ $planning->type }}">
                            <input type="hidden" name="date" value="{{ $planning->date->format('Y-m-d') }}">
                            <input type="hidden" name="start_time" value="{{ \Carbon\Carbon::parse($planning->start_time)->format('H:i') }}">
                            <input type="hidden" name="end_time" value="{{ \Carbon\Carbon::parse($planning->end_time)->format('H:i') }}">
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Terminer la séance
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <!-- Statistiques de présence (si terminé) -->
            @if($planning->status === 'completed' && $planning->participants->count() > 0)
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Statistiques</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-slate-600">Taux de présence</span>
                                <span class="font-bold text-slate-900">{{ $planning->attendance_rate }}%</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $planning->attendance_rate }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="p-3 bg-emerald-50 rounded-xl">
                                <div class="text-lg font-black text-emerald-600">{{ $planning->presentParticipants()->count() }}</div>
                                <div class="text-xs text-emerald-600">Présents</div>
                            </div>
                            <div class="p-3 bg-red-50 rounded-xl">
                                <div class="text-lg font-black text-red-600">{{ $planning->participants()->wherePivot('status', 'absent')->count() }}</div>
                                <div class="text-xs text-red-600">Absents</div>
                            </div>
                            <div class="p-3 bg-amber-50 rounded-xl">
                                <div class="text-lg font-black text-amber-600">{{ $planning->participants()->wherePivot('status', 'excused')->count() }}</div>
                                <div class="text-xs text-amber-600">Excusés</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div id="toast-success" class="fixed bottom-4 right-4 z-50 bg-emerald-500 text-white px-6 py-4 rounded-xl shadow-lg shadow-emerald-500/25 flex items-center gap-3 animate-slide-up">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => document.getElementById('toast-success').remove(), 4000);
    </script>
@endif

@push('styles')
<style>
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out; }
    [x-cloak] { display: none !important; }
</style>
@endpush
@endsection

