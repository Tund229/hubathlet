@extends('layouts.dashboard-player')

@section('title', 'Mon profil')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Mon profil</h1>
            <p class="text-sm text-slate-500">Informations personnelles</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Colonne gauche - Carte membre --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Carte de membre visuelle (compact) --}}
            <div class="relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-4 shadow-lg">
                {{-- Contenu --}}
                <div class="flex items-center gap-4">
                    {{-- Avatar --}}
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xl font-black shadow-lg flex-shrink-0">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-xl object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-emerald-400 text-xs font-bold uppercase">{{ $club->name }}</p>
                        <h2 class="text-white font-bold text-lg truncate">{{ $user->name }}</h2>
                        <p class="text-white/60 text-sm">
                            @if($membership && $membership->pivot->position)
                                {{ $membership->pivot->position }}
                            @else
                                Joueur
                            @endif
                        </p>
                    </div>
                    
                    {{-- Numéro de maillot --}}
                    @if($membership && $membership->pivot->jersey_number)
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-black text-xl">{{ $membership->pivot->jersey_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- Stats rapides --}}
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div>
                        <p class="text-lg font-bold text-emerald-600">{{ $attendanceRate }}%</p>
                        <p class="text-xs text-slate-500">Présence</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">{{ $totalParticipations }}</p>
                        <p class="text-xs text-slate-500">Séances</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-800">
                            @if($membership && $membership->pivot->joined_at)
                                {{ \Carbon\Carbon::parse($membership->pivot->joined_at)->translatedFormat('m/Y') }}
                            @else
                                {{ $user->created_at->translatedFormat('m/Y') }}
                            @endif
                        </p>
                        <p class="text-xs text-slate-500">Membre</p>
                    </div>
                </div>
                
                <a href="{{ route('player.stats') }}" class="mt-3 w-full flex items-center justify-center gap-2 px-3 py-2 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-lg hover:bg-emerald-100 transition-colors">
                    Mes statistiques
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
        
        {{-- Colonne droite - Formulaire --}}
        <div class="lg:col-span-2 space-y-4">
            
            {{-- Informations personnelles --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h2 class="font-bold text-slate-800 mb-4">Informations personnelles</h2>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('player.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nom complet --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Téléphone --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('phone') border-red-500 @enderror"
                                placeholder="+33 6 12 34 56 78">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Date de naissance --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Date de naissance</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('birth_date') border-red-500 @enderror">
                            @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Informations club (lecture seule) --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-800">Informations club</h2>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">Lecture seule</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Club --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Club</label>
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            @if($club->logo)
                                <img src="{{ Storage::url($club->logo) }}" alt="{{ $club->name }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <span class="text-emerald-600 font-bold">{{ strtoupper(substr($club->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <span class="font-semibold text-slate-800">{{ $club->name }}</span>
                        </div>
                    </div>
                    
                    {{-- Rôle --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Rôle</label>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            @if($role)
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-bold" style="background-color: {{ $role->color }}20; color: {{ $role->color }}">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $role->color }}"></span>
                                    {{ $role->name }}
                                </span>
                            @else
                                <span class="text-slate-600">Joueur</span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Position --}}
                    @if($membership && $membership->pivot->position)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Position</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-semibold text-slate-800">{{ $membership->pivot->position }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Numéro de maillot --}}
                    @if($membership && $membership->pivot->jersey_number)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro de maillot</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="inline-flex items-center justify-center w-10 h-10 bg-emerald-600 text-white font-black rounded-lg">
                                    {{ $membership->pivot->jersey_number }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Licence --}}
                    @if($membership && $membership->pivot->license_number)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Numéro de licence</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-mono font-semibold text-slate-800">{{ $membership->pivot->license_number }}</span>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Date d'adhésion --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Membre depuis</label>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="font-semibold text-slate-800">
                                @if($membership && $membership->pivot->joined_at)
                                    {{ \Carbon\Carbon::parse($membership->pivot->joined_at)->translatedFormat('d F Y') }}
                                @else
                                    {{ $user->created_at->translatedFormat('d F Y') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-amber-800 font-medium">Besoin de modifier ces informations ?</p>
                            <p class="text-xs text-amber-600 mt-1">Contactez votre coach ou administrateur du club pour mettre à jour vos informations.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Avatar --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                <h2 class="text-lg font-bold text-slate-800 mb-6">Photo de profil</h2>
                
                <div class="flex items-center gap-6">
                    {{-- Aperçu actuel --}}
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-black shadow-lg">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-2xl object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <p class="text-slate-600 text-sm mb-4">
                            Ajoutez une photo pour personnaliser votre profil et votre carte de membre.
                        </p>
                        <label class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl cursor-pointer hover:bg-slate-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Changer la photo
                            <input type="file" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
