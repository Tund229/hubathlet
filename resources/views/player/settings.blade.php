@extends('layouts.dashboard-player')

@section('title', 'Paramètres')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Paramètres</h1>
            <p class="text-sm text-slate-500">Sécurité et notifications</p>
        </div>
    </div>

    {{-- Tabs navigation --}}
    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl w-fit">
        <button onclick="showTab('security')" id="tab-security" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-slate-900 shadow-sm">
            Sécurité
        </button>
        <button onclick="showTab('notifications')" id="tab-notifications" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:text-slate-900">
            Notifications
        </button>
    </div>

    {{-- Contenu --}}
    <div class="space-y-6">
            
            {{-- Sécurité - Mot de passe --}}
            <div id="security" class="section-content bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h2 class="font-bold text-slate-800 mb-4">Modifier le mot de passe</h2>
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('player.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    {{-- Mot de passe actuel --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mot de passe actuel</label>
                        <div class="relative">
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('current_password') border-red-500 @enderror">
                            <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-5 h-5 eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="w-5 h-5 eye-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nouveau mot de passe --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nouveau mot de passe</label>
                            <div class="relative">
                                <input type="password" name="password" required minlength="8"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('password') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword(this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg class="w-5 h-5 eye-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg class="w-5 h-5 eye-hide hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Confirmation --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                        </div>
                    </div>
                    
                    {{-- Indicateur de force --}}
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-600 mb-2">Le mot de passe doit contenir :</p>
                        <ul class="text-xs text-slate-500 space-y-1">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Au moins 8 caractères
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Une lettre majuscule
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Un chiffre
                            </li>
                        </ul>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                            Mettre à jour le mot de passe
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Notifications --}}
            <div id="notifications" class="section-content hidden bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <h2 class="font-bold text-slate-800 mb-4">Préférences de notification</h2>
                
                <div class="space-y-3">
                    {{-- Rappels de séances --}}
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <h3 class="font-semibold text-slate-800 text-sm">Rappels de séances</h3>
                            <p class="text-xs text-slate-500">Rappel avant chaque séance</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    
                    {{-- Notifications email --}}
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <h3 class="font-semibold text-slate-800 text-sm">Notifications email</h3>
                            <p class="text-xs text-slate-500">Notifications importantes par email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    
                    {{-- Nouvelles du club --}}
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <h3 class="font-semibold text-slate-800 text-sm">Actualités du club</h3>
                            <p class="text-xs text-slate-500">Annonces et nouvelles</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-10 h-5 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
            </div>
    </div>
</div>

<script>
function togglePassword(button) {
    const input = button.parentElement.querySelector('input');
    const showIcon = button.querySelector('.eye-show');
    const hideIcon = button.querySelector('.eye-hide');
    
    if (input.type === 'password') {
        input.type = 'text';
        showIcon.classList.add('hidden');
        hideIcon.classList.remove('hidden');
    } else {
        input.type = 'password';
        showIcon.classList.remove('hidden');
        hideIcon.classList.add('hidden');
    }
}

function showTab(tabName) {
    // Hide all sections
    document.querySelectorAll('.section-content').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(tabName).classList.remove('hidden');
    
    // Update tab buttons
    document.getElementById('tab-security').classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
    document.getElementById('tab-security').classList.add('text-slate-600');
    document.getElementById('tab-notifications').classList.remove('bg-white', 'text-slate-900', 'shadow-sm');
    document.getElementById('tab-notifications').classList.add('text-slate-600');
    
    document.getElementById('tab-' + tabName).classList.remove('text-slate-600');
    document.getElementById('tab-' + tabName).classList.add('bg-white', 'text-slate-900', 'shadow-sm');
}
</script>
@endsection
