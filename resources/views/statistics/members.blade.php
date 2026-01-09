@extends('layouts.dashboard')

@section('title', 'Statistiques membres')
@section('description', 'Performances individuelles des membres')

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
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Stats membres</h1>
                <p class="text-slate-500 mt-1">Performances individuelles</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Period Filter -->
            <div class="flex items-center gap-1 p-1 bg-white rounded-xl border border-slate-200 shadow-sm">
                <a href="{{ route('statistics.members', ['period' => 'month', 'sort' => $sort]) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'month' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Mois
                </a>
                <a href="{{ route('statistics.members', ['period' => 'year', 'sort' => $sort]) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'year' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Année
                </a>
                <a href="{{ route('statistics.members', ['period' => 'all', 'sort' => $sort]) }}" 
                   class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $period === 'all' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                    Tout
                </a>
            </div>
            
            <!-- Sort -->
            <select onchange="window.location.href=this.value" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20">
                <option value="{{ route('statistics.members', ['period' => $period, 'sort' => 'attendance']) }}" {{ $sort === 'attendance' ? 'selected' : '' }}>Tri: Présence</option>
                <option value="{{ route('statistics.members', ['period' => $period, 'sort' => 'training_time']) }}" {{ $sort === 'training_time' ? 'selected' : '' }}>Tri: Temps</option>
                <option value="{{ route('statistics.members', ['period' => $period, 'sort' => 'name']) }}" {{ $sort === 'name' ? 'selected' : '' }}>Tri: Nom</option>
            </select>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-slate-900">{{ $members->count() }}</div>
            <div class="text-sm text-slate-500">Membres actifs</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-emerald-600">{{ $members->where('attendance_rate', '>=', 80)->count() }}</div>
            <div class="text-sm text-slate-500">Assidus (+80%)</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-amber-600">{{ $members->where('attendance_rate', '<', 80)->where('attendance_rate', '>=', 50)->count() }}</div>
            <div class="text-sm text-slate-500">Réguliers (50-80%)</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <div class="text-2xl font-black text-red-600">{{ $members->where('attendance_rate', '<', 50)->count() }}</div>
            <div class="text-sm text-slate-500">Occasionnels (-50%)</div>
        </div>
    </div>

    <!-- Members list -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Membre</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Rôle</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Séances</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider hidden md:table-cell">Temps</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Présence</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($members as $index => $member)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $index < 3 ? ($index === 0 ? 'bg-amber-100 text-amber-600' : ($index === 1 ? 'bg-slate-200 text-slate-600' : 'bg-orange-100 text-orange-600')) : 'bg-slate-100 text-slate-500' }}">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                        {{ strtoupper(substr($member['name'], 0, 1)) }}{{ strtoupper(substr(explode(' ', $member['name'])[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $member['name'] }}</div>
                                        <div class="text-sm text-slate-500 hidden sm:block">{{ $member['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                @if($member['role'])
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold" style="background-color: {{ $member['role']->color }}15; color: {{ $member['role']->color }};">
                                        {{ $member['role']->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="font-bold text-slate-900">{{ $member['present_sessions'] }}</div>
                                <div class="text-xs text-slate-500">/ {{ $member['total_sessions'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                <div class="font-bold text-slate-900">{{ $member['total_training_hours'] }}h</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $member['attendance_rate'] >= 80 ? 'bg-emerald-500' : ($member['attendance_rate'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $member['attendance_rate'] }}%;"></div>
                                    </div>
                                    <span class="text-sm font-bold {{ $member['attendance_rate'] >= 80 ? 'text-emerald-600' : ($member['attendance_rate'] >= 50 ? 'text-amber-600' : 'text-red-600') }}">{{ $member['attendance_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('statistics.member', $member['id']) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-sm font-semibold hover:bg-emerald-100 hover:text-emerald-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Détails
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($members->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Aucun membre</h3>
                <p class="text-slate-500">Ajoutez des membres pour voir leurs statistiques</p>
            </div>
        @endif
    </div>
</div>
@endsection

