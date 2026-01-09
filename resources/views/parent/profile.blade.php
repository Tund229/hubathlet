@extends('layouts.dashboard-parent')

@section('title', 'Mon profil')
@section('description', 'Gérez votre profil parent')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Mon profil</h1>
            <p class="text-slate-500 mt-1">Gérez vos informations personnelles</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900 mb-2">Bientôt disponible</h3>
        <p class="text-slate-500">Cette fonctionnalité arrive très bientôt !</p>
    </div>
</div>
@endsection

