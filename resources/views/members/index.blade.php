@extends('layouts.dashboard')

@section('title', 'Membres')
@section('description', 'Gérez les membres de votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Membres</h1>
            <p class="text-slate-500 mt-1">Gérez les membres de votre club</p>
        </div>
        <a href="{{ route('members.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Ajouter un membre</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm">
            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $stats['total'] }}</div>
            <div class="text-sm text-slate-500 font-medium">Total</div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm">
            <div class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $stats['active'] }}</div>
            <div class="text-sm text-slate-500 font-medium">Actifs</div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm">
            <div class="text-2xl sm:text-3xl font-black text-amber-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-slate-500 font-medium">En attente</div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm">
            <div class="text-2xl sm:text-3xl font-black text-blue-600">{{ $stats['players'] }}</div>
            <div class="text-sm text-slate-500 font-medium">Joueurs</div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm col-span-2 sm:col-span-1">
            <div class="text-2xl sm:text-3xl font-black text-purple-600">{{ $stats['coaches'] }}</div>
            <div class="text-sm text-slate-500 font-medium">Coaches</div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-[1.5rem] p-4 sm:p-6 border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('members.index') }}" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Rechercher un membre..."
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                >
            </div>
            
            <!-- Role Filter -->
            <select name="role" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-slate-700 font-medium">
                <option value="">Tous les rôles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            
            <!-- Status Filter -->
            <select name="status" class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-slate-700 font-medium">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
            </select>
            
            <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-colors">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        @if($members->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Aucun membre</h3>
                <p class="text-slate-500 mb-6">Commencez par ajouter des membres à votre club.</p>
                <a href="{{ route('members.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter un membre
                </a>
            </div>
        @else
            <!-- Mobile View -->
            <div class="sm:hidden divide-y divide-slate-100">
                @foreach($members as $member)
                    @php
                        $role = \App\Models\Role::find($member->pivot->role_id);
                    @endphp
                    <a href="{{ route('members.edit', $member) }}" class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg" style="background-color: {{ $role->color ?? '#6B7280' }}">
                            {{ $member->initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900 truncate">{{ $member->name }}</div>
                            <div class="text-sm text-slate-500 truncate">{{ $role->name ?? 'Membre' }}</div>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @if($member->pivot->status == 'active')
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Actif</span>
                            @elseif($member->pivot->status == 'pending')
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">En attente</span>
                            @elseif($member->pivot->status == 'inactive')
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">Inactif</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">Suspendu</span>
                            @endif
                            @if($member->pivot->jersey_number)
                                <span class="text-xs text-slate-400">#{{ $member->pivot->jersey_number }}</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Desktop View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Membre</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rôle</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Adhésion</th>
                            <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($members as $member)
                            @php
                                $role = \App\Models\Role::find($member->pivot->role_id);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" style="background-color: {{ $role->color ?? '#6B7280' }}">
                                            {{ $member->initials }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $member->name }}</div>
                                            @if($member->pivot->jersey_number)
                                                <div class="text-sm text-slate-500">#{{ $member->pivot->jersey_number }} {{ $member->pivot->position ? '• ' . $member->pivot->position : '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold" style="background-color: {{ $role->color ?? '#6B7280' }}20; color: {{ $role->color ?? '#6B7280' }}">
                                        {{ $role->name ?? 'Membre' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900">{{ $member->email }}</div>
                                    @if($member->phone)
                                        <div class="text-sm text-slate-500">{{ $member->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($member->pivot->status == 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Actif
                                        </span>
                                    @elseif($member->pivot->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            En attente
                                        </span>
                                    @elseif($member->pivot->status == 'inactive')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                            Inactif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Suspendu
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900">
                                        {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('d/m/Y') : '—' }}
                                    </div>
                                    @if($member->pivot->license_number)
                                        <div class="text-xs text-slate-500">Licence: {{ $member->pivot->license_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('members.edit', $member) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-emerald-100 hover:text-emerald-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce membre ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-red-100 hover:text-red-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($members->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $members->links() }}
                </div>
            @endif
        @endif
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
        setTimeout(() => {
            document.getElementById('toast-success').remove();
        }, 4000);
    </script>
@endif

@push('styles')
<style>
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
</style>
@endpush
@endsection

