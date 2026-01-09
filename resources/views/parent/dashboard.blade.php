@extends('layouts.dashboard-parent')

@section('title', 'Espace Parent')
@section('description', 'Suivez l\'activité de votre enfant')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Bonjour {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-slate-500 mt-1">Suivez l'activité sportive de votre enfant</p>
        </div>
    </div>

    <!-- Coming Soon -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-pink-400 to-pink-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-pink-500/30">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 mb-3">Espace parent en construction</h2>
        <p class="text-slate-500 max-w-md mx-auto mb-6">
            Votre espace dédié arrive bientôt ! Vous pourrez suivre l'activité, les présences et les progrès de votre enfant.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-pink-50 rounded-xl">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-pink-700 font-semibold">Mes enfants</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 rounded-xl">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-blue-700 font-semibold">Planning</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-xl">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-emerald-700 font-semibold">Présences</span>
            </div>
        </div>
    </div>
</div>
@endsection

