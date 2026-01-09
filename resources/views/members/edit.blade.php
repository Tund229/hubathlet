@extends('layouts.dashboard')

@section('title', 'Modifier ' . $member->name)
@section('description', 'Modifier les informations du membre')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('members.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Modifier le membre</h1>
            <p class="text-slate-500 mt-1">{{ $member->name }}</p>
        </div>
        
        @php
            $currentRole = \App\Models\Role::find($membership->pivot->role_id);
        @endphp
        
        @if($currentRole && $currentRole->slug !== 'owner')
            <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce membre du club ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="hidden sm:inline">Retirer</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Member Card Preview -->
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full blur-[80px]" style="background-color: {{ $currentRole->color ?? '#6B7280' }}30;"></div>
        
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl flex items-center justify-center text-white font-black text-2xl sm:text-3xl" style="background-color: {{ $currentRole->color ?? '#6B7280' }}">
                {{ $member->initials }}
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-white">{{ $member->name }}</div>
                <div class="text-slate-400 mt-1">{{ $member->email }}</div>
                <div class="flex items-center gap-3 mt-3">
                    <span class="px-3 py-1 rounded-lg text-sm font-bold" style="background-color: {{ $currentRole->color ?? '#6B7280' }}30; color: {{ $currentRole->color ?? '#6B7280' }}">
                        {{ $currentRole->name ?? 'Membre' }}
                    </span>
                    @if($membership->pivot->jersey_number)
                        <span class="text-white/60 font-bold">#{{ $membership->pivot->jersey_number }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('members.update', $member) }}" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Role Selection -->
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-6">Rôle</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($roles as $role)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role_id" value="{{ $role->id }}" class="peer sr-only" {{ $membership->pivot->role_id == $role->id ? 'checked' : '' }}>
                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 hover:bg-slate-50 transition-all">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: {{ $role->color }}20;">
                                @if($role->slug == 'owner')
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                @elseif($role->slug == 'coach')
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
                                @else
                                    <svg class="w-5 h-5" style="color: {{ $role->color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="font-bold text-slate-900 text-sm">{{ $role->name }}</div>
                        </div>
                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center transition-all">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </label>
                @endforeach
            </div>
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
                        value="{{ old('name', $member->name) }}"
                        required
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('name') border-red-300 bg-red-50 @enderror"
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
                        value="{{ old('email', $member->email) }}"
                        required
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all @error('email') border-red-300 bg-red-50 @enderror"
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
                        value="{{ old('phone', $member->phone) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
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
                        value="{{ old('birth_date', $member->birth_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
                </div>
            </div>
        </div>

        <!-- Club Info -->
        <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
            <h2 class="text-lg font-black text-slate-900 mb-6">Informations club</h2>
            
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-bold text-slate-700 mb-2">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
                        <option value="active" {{ old('status', $membership->pivot->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="pending" {{ old('status', $membership->pivot->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="inactive" {{ old('status', $membership->pivot->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspended" {{ old('status', $membership->pivot->status) == 'suspended' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                </div>
                
                <!-- Jersey Number -->
                <div>
                    <label for="jersey_number" class="block text-sm font-bold text-slate-700 mb-2">
                        Numéro de maillot
                    </label>
                    <input 
                        type="text" 
                        id="jersey_number" 
                        name="jersey_number" 
                        value="{{ old('jersey_number', $membership->pivot->jersey_number) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
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
                        value="{{ old('position', $membership->pivot->position) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
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
                        value="{{ old('license_number', $membership->pivot->license_number) }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
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
                        value="{{ old('joined_at', $membership->pivot->joined_at ? \Carbon\Carbon::parse($membership->pivot->joined_at)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
                </div>
                
                <!-- License Expires At -->
                <div>
                    <label for="license_expires_at" class="block text-sm font-bold text-slate-700 mb-2">
                        Expiration licence
                    </label>
                    <input 
                        type="date" 
                        id="license_expires_at" 
                        name="license_expires_at" 
                        value="{{ old('license_expires_at', $membership->pivot->license_expires_at ? \Carbon\Carbon::parse($membership->pivot->license_expires_at)->format('Y-m-d') : '') }}"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all"
                    >
                </div>
                
                <!-- Notes -->
                <div class="sm:col-span-2 lg:col-span-3">
                    <label for="notes" class="block text-sm font-bold text-slate-700 mb-2">
                        Notes internes
                    </label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all resize-none"
                    >{{ old('notes', $membership->pivot->notes) }}</textarea>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection

