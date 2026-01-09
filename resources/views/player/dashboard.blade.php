@extends('layouts.dashboard-player')

@section('title', 'Mon espace')
@section('description', 'Tableau de bord joueur')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Salut, {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-slate-500 mt-1">Bienvenue dans ton espace joueur</p>
        </div>
    </div>

    <!-- Coming Soon -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/30">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 mb-3">Espace joueur en construction</h2>
        <p class="text-slate-500 max-w-md mx-auto mb-6">
            Ton espace personnel arrive bientôt ! Tu pourras consulter ton planning, tes statistiques et gérer ton profil.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-xl">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-emerald-700 font-semibold">Mon planning</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-xl">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-blue-700 font-semibold">Mes stats</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-violet-50 rounded-xl">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-violet-700 font-semibold">Mon profil</span>
            </div>
        </div>
    </div>
</div>
@endsection

