@extends('layouts.app')

@section('title', 'Connexion - Hubathlet')
@section('description', 'Connectez-vous à votre compte Hubathlet')

@section('content')
<div class="min-h-screen bg-slate-50 flex relative overflow-hidden">
    
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
    <div class="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] bg-emerald-200/40 rounded-full blur-[150px] animate-pulse-soft"></div>
    <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-blue-100/30 rounded-full blur-[120px] animate-pulse-soft"></div>

    <!-- Left Panel - Branding (Hidden on mobile) -->
    <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] bg-slate-900 relative overflow-hidden items-center justify-center p-12">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-30 mix-blend-soft-light"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-500/10 rounded-full blur-[80px]"></div>
        
        <!-- Content -->
        <div class="relative z-10 max-w-lg">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-3 mb-12 group">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-2xl font-black text-white tracking-tighter">Hubathlet<span class="text-emerald-400">.</span></span>
            </a>
            
            <!-- Title -->
            <h1 class="text-5xl xl:text-6xl font-black text-white leading-[0.95] tracking-tighter mb-6">
                Bon retour <br>parmi nous<span class="text-emerald-400">.</span>
            </h1>
            <p class="text-xl text-slate-400 leading-relaxed mb-12">
                Accédez à votre tableau de bord et pilotez votre club en toute simplicité.
            </p>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 animate-float">
                    <div class="text-3xl font-black text-white mb-1">+500</div>
                    <div class="text-sm text-slate-400 font-medium">Clubs actifs</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 animate-float-delayed">
                    <div class="text-3xl font-black text-emerald-400 mb-1">98%</div>
                    <div class="text-sm text-slate-400 font-medium">Satisfaction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center p-6 sm:p-12 relative z-10">
        <div class="w-full max-w-md space-y-8">
            
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="/" class="inline-flex items-center space-x-3 group">
                    <div class="w-11 h-11 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-slate-900 tracking-tighter">Hubathlet<span class="text-emerald-500">.</span></span>
                </a>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10 relative overflow-hidden">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full blur-[50px] -z-10"></div>
                
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Connexion</h2>
                    <p class="text-slate-500">Accédez à votre espace club</p>
                </div>
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">
                            Adresse email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            autofocus
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-slate-900 placeholder-slate-400 @error('email') border-red-300 bg-red-50 @enderror"
                            placeholder="vous@exemple.com"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">
                                Mot de passe
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                                Oublié ?
                            </a>
                        </div>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="w-full px-4 py-3.5 pr-12 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white outline-none transition-all text-slate-900 placeholder-slate-400 @error('password') border-red-300 bg-red-50 @enderror"
                                placeholder="••••••••"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                aria-label="Afficher/Masquer le mot de passe"
                            >
                                <svg id="password-eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="password-eye-slash" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 rounded-md border-slate-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0"
                        >
                        <label for="remember" class="ml-3 block text-sm text-slate-600 font-medium">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- Submit -->
                    <button 
                        type="submit" 
                        class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-emerald-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-emerald-500/25 hover:-translate-y-0.5 relative overflow-hidden group"
                    >
                        <span class="relative z-10">Se connecter</span>
                    </button>
                </form>
                
                <!-- Divider -->
                <div class="my-8 flex items-center gap-4">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-sm text-slate-400 font-medium">Pas encore de compte ?</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>
                
                <!-- Register Link -->
                <a 
                    href="{{ route('register') }}" 
                    class="w-full flex items-center justify-center px-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700 transition-all"
                >
                    Créer un compte gratuitement
                </a>
            </div>

            <!-- Back to home -->
            <div class="text-center">
                <a href="/" class="text-sm text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center gap-2 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const eye = document.getElementById(fieldId + '-eye');
        const eyeSlash = document.getElementById(fieldId + '-eye-slash');
        
        if (field.type === 'password') {
            field.type = 'text';
            eye.classList.add('hidden');
            eyeSlash.classList.remove('hidden');
        } else {
            field.type = 'password';
            eye.classList.remove('hidden');
            eyeSlash.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
