@extends('layouts.app')

@section('title', 'Mot de passe oublié - Hubathlet')
@section('description', 'Réinitialisez votre mot de passe Hubathlet')

@section('content')
<div class="min-h-screen bg-slate-50 flex items-center justify-center relative overflow-hidden px-4 py-12">
    
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
    <div class="absolute top-[-20%] right-[-10%] w-[600px] h-[600px] bg-blue-100/40 rounded-full blur-[150px] animate-pulse-soft"></div>
    <div class="absolute bottom-[-20%] left-[-10%] w-[500px] h-[500px] bg-slate-200/30 rounded-full blur-[120px] animate-pulse-soft"></div>

    <div class="w-full max-w-md space-y-8 relative z-10">
        
        <!-- Logo -->
        <div class="text-center">
            <a href="/" class="inline-flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="text-2xl font-black text-slate-900 tracking-tighter">Hubathlet<span class="text-emerald-500">.</span></span>
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10 relative overflow-hidden">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full blur-[50px] -z-10"></div>
            
            <!-- Icon -->
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            
            <div class="text-center mb-8">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Mot de passe oublié ?</h2>
                <p class="text-slate-500 leading-relaxed">Pas de souci. Entrez votre email et nous vous enverrons un lien de réinitialisation.</p>
            </div>
            
            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
                    <div class="w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('status') }}</p>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

                <!-- Submit -->
                <button 
                    type="submit" 
                    class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-emerald-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-emerald-500/25 hover:-translate-y-0.5"
                >
                    Envoyer le lien
                </button>
            </form>
            
            <!-- Divider -->
            <div class="my-8 flex items-center gap-4">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-sm text-slate-400 font-medium">ou</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>
            
            <!-- Back to Login -->
            <a 
                href="{{ route('login') }}" 
                class="w-full flex items-center justify-center px-4 py-3.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold hover:border-slate-300 hover:bg-slate-100 transition-all gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la connexion
            </a>
        </div>

        <!-- Back to home -->
        <div class="text-center">
            <a href="/" class="text-sm text-slate-500 hover:text-slate-900 transition-colors inline-flex items-center gap-2 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
