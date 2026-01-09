@extends('layouts.dashboard')

@section('title', 'Ajouter un membre')
@section('description', 'Ajoutez un nouveau membre à votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('members.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Ajouter un membre</h1>
            <p class="text-slate-500 mt-1">Remplissez les informations du nouveau membre</p>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('members.store') }}" class="space-y-6">
        @csrf
        
        <!-- Role Selection -->
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-6">Type de membre</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($roles as $role)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role_id" value="{{ $role->id }}" class="peer sr-only" {{ old('role_id') == $role->id ? 'checked' : '' }} {{ $loop->first && !old('role_id') ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50 transition-all">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: {{ $role->color }}20;">
                                @if($role->slug == 'coach')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                @elseif($role->slug == 'player')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @elseif($role->slug == 'admin')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                @elseif($role->slug == 'moderator')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                @elseif($role->slug == 'parent')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="font-bold text-slate-900 text-sm">{{ $role->name }}</div>
                            <div class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $role->description }}</div>
                        </div>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('role_id')
                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Personal Info -->
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-6">Informations personnelles</h2>
            
            <div class="grid sm:grid-cols-2 gap-5">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('name') border-red-300 bg-red-50 @enderror"
                        placeholder="Jean Dupont"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('email') border-red-300 bg-red-50 @enderror"
                        placeholder="jean.dupont@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">
                        Téléphone
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('phone') border-red-300 bg-red-50 @enderror"
                        placeholder="06 12 34 56 78"
                    >
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Birth Date -->
                <div>
                    <label for="birth_date" class="block text-sm font-bold text-slate-700 mb-2">
                        Date de naissance
                    </label>
                    <input 
                        type="date" 
                        id="birth_date" 
                        name="birth_date" 
                        value="{{ old('birth_date') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('birth_date') border-red-300 bg-red-50 @enderror"
                    >
                    @error('birth_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Club Info -->
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-6">Informations club</h2>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Jersey Number -->
                <div>
                    <label for="jersey_number" class="block text-sm font-bold text-slate-700 mb-2">
                        Numéro de maillot
                    </label>
                    <input 
                        type="text" 
                        id="jersey_number" 
                        name="jersey_number" 
                        value="{{ old('jersey_number') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                        placeholder="10"
                    >
                </div>
                
                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-bold text-slate-700 mb-2">
                        Position / Poste
                    </label>
                    <input 
                        type="text" 
                        id="position" 
                        name="position" 
                        value="{{ old('position') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                        placeholder="Attaquant, Gardien..."
                    >
                </div>
                
                <!-- License Number -->
                <div>
                    <label for="license_number" class="block text-sm font-bold text-slate-700 mb-2">
                        Numéro de licence
                    </label>
                    <input 
                        type="text" 
                        id="license_number" 
                        name="license_number" 
                        value="{{ old('license_number') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                        placeholder="12345678"
                    >
                </div>
                
                <!-- Joined At -->
                <div>
                    <label for="joined_at" class="block text-sm font-bold text-slate-700 mb-2">
                        Date d'adhésion
                    </label>
                    <input 
                        type="date" 
                        id="joined_at" 
                        name="joined_at" 
                        value="{{ old('joined_at', date('Y-m-d')) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
                </div>
                
                <!-- Notes -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-2">
                        Notes internes
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all resize-none"
                        placeholder="Informations complémentaires..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('members.index') }}" class="px-6 py-3.5 bg-slate-100 text-slate-700 rounded-xl font-bold text-center hover:bg-slate-200 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-8 py-3.5 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Ajouter le membre
            </button>
        </div>
    </form>
</div>
@endsection

