@extends('layouts.dashboard')

@section('title', 'Mon club')
@section('description', 'Gérez les informations de votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Mon club</h1>
            <p class="text-slate-500 mt-1">Gérez les informations de votre club</p>
        </div>
        <a href="{{ route('club.edit') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Modifier
        </a>
    </div>

    <!-- Club Card -->
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] p-6 sm:p-8 relative overflow-hidden">
        <!-- Glow effects -->
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-[80px]" style="background-color: {{ $club->primary_color ?? '#10B981' }}30;"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full blur-[60px]" style="background-color: {{ $club->accent_color ?? '#3B82F6' }}20;"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                <!-- Logo -->
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl flex items-center justify-center overflow-hidden" style="background-color: {{ $club->primary_color ?? '#10B981' }}20;">
                    @if($club->logo)
                        <img src="{{ Storage::url($club->logo) }}" alt="{{ $club->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl sm:text-5xl font-black" style="color: {{ $club->primary_color ?? '#10B981' }};">
                            {{ strtoupper(substr($club->name, 0, 2)) }}
                        </span>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="flex-1">
                    <h2 class="text-2xl sm:text-3xl font-black text-white mb-2">{{ $club->name }}</h2>
                    <div class="flex flex-wrap items-center gap-3 text-slate-400">
                        @if($club->city)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $club->city }}{{ $club->country ? ', ' . $club->country : '' }}
                            </span>
                        @endif
                        @if($club->email)
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $club->email }}
                            </span>
                        @endif
                    </div>
                    
                    <!-- Plan badge -->
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold {{ $club->plan === 'premium' ? 'bg-amber-500/20 text-amber-400' : 'bg-emerald-500/20 text-emerald-400' }}">
                            @if($club->plan === 'premium')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                                Premium
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Plan Gratuit
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['members'] }}</div>
            <div class="text-sm text-slate-500">Membres total</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['active_members'] }}</div>
            <div class="text-sm text-slate-500">Membres actifs</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['trainings'] }}</div>
            <div class="text-sm text-slate-500">Séances créées</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-slate-900">{{ $stats['trainings_this_month'] }}</div>
            <div class="text-sm text-slate-500">Séances ce mois</div>
        </div>
    </div>

    <!-- Quick links -->
    <div class="grid sm:grid-cols-3 gap-4">
        <a href="{{ route('club.edit') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4 group-hover:bg-emerald-200 transition-colors">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Informations</h3>
            <p class="text-sm text-slate-500">Modifiez le nom, contact et logo du club</p>
        </a>

        <a href="{{ route('club.customization') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-violet-200 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center mb-4 group-hover:bg-violet-200 transition-colors">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Personnalisation</h3>
            <p class="text-sm text-slate-500">Couleurs et apparence de l'application</p>
        </a>

        <a href="{{ route('club.settings') }}" class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all group">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Paramètres</h3>
            <p class="text-sm text-slate-500">Langue, fuseau horaire et fonctionnalités</p>
        </a>
    </div>

    <!-- Club details -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="font-bold text-slate-900">Informations du club</h2>
        </div>
        
        <div class="divide-y divide-slate-100">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Nom du club</div>
                <div class="font-semibold text-slate-900">{{ $club->name }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Email</div>
                <div class="font-semibold text-slate-900">{{ $club->email ?: '—' }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Téléphone</div>
                <div class="font-semibold text-slate-900">{{ $club->phone ?: '—' }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Ville</div>
                <div class="font-semibold text-slate-900">{{ $club->city ?: '—' }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Pays</div>
                <div class="font-semibold text-slate-900">{{ $club->country ?: '—' }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Identifiant unique</div>
                <div class="font-mono text-sm text-slate-600 bg-slate-100 px-2 py-1 rounded">{{ $club->slug }}</div>
            </div>
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">Membre depuis</div>
                <div class="font-semibold text-slate-900">{{ $club->created_at->format('d/m/Y') }}</div>
            </div>
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
</style>
@endpush
@endsection

