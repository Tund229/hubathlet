@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('description', 'Gérez votre club sportif depuis votre tableau de bord Hubathlet')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Stats Grid - Bento Style -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Membres -->
        <div class="bg-white rounded-2xl sm:rounded-[1.5rem] p-5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md hover:shadow-slate-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">+12%</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">24</div>
            <div class="text-sm text-slate-500 font-medium">Membres actifs</div>
        </div>

        <!-- Entraînements -->
        <div class="bg-white rounded-2xl sm:rounded-[1.5rem] p-5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md hover:shadow-slate-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Cette sem.</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">8</div>
            <div class="text-sm text-slate-500 font-medium">Entraînements</div>
        </div>

        <!-- Présences -->
        <div class="bg-white rounded-2xl sm:rounded-[1.5rem] p-5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md hover:shadow-slate-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">87%</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">156</div>
            <div class="text-sm text-slate-500 font-medium">Présences ce mois</div>
        </div>

        <!-- Heures -->
        <div class="bg-white rounded-2xl sm:rounded-[1.5rem] p-5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md hover:shadow-slate-200/50 transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">+24h</span>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">312<span class="text-lg">h</span></div>
            <div class="text-sm text-slate-500 font-medium">Heures cumulées</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-slate-900">Actions rapides</h2>
                    <span class="text-xs text-slate-400">Raccourcis</span>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('members.create') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium hover:bg-emerald-100 transition-colors group">
                        <svg class="w-4 h-4 text-emerald-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Ajouter membre
                    </a>
                    
                    <button class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors group">
                        <svg class="w-4 h-4 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Créer séance
                    </button>
                    
                    <button class="inline-flex items-center gap-2 px-3 py-2 bg-amber-50 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-100 transition-colors group">
                        <svg class="w-4 h-4 text-amber-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Faire l'appel
                    </button>
                    
                    <button class="inline-flex items-center gap-2 px-3 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-medium hover:bg-purple-100 transition-colors group">
                        <svg class="w-4 h-4 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        Message
                    </button>
                    
                    <button class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors group">
                        <svg class="w-4 h-4 text-slate-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Stats
                    </button>
                </div>
            </div>

            <!-- Upcoming Sessions -->
            <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Prochaines séances</h2>
                    <a href="#" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Voir tout</a>
                </div>
                
                <div class="space-y-3">
                    <!-- Session Item -->
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                        <div class="w-14 h-14 bg-emerald-500 rounded-xl flex flex-col items-center justify-center text-white">
                            <span class="text-xs font-bold uppercase">Jan</span>
                            <span class="text-lg font-black leading-none">10</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900">Entraînement Seniors</div>
                            <div class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                19:00 - 21:00
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">M</div>
                                <div class="w-8 h-8 bg-purple-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">A</div>
                                <div class="w-8 h-8 bg-amber-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">+6</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Session Item -->
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                        <div class="w-14 h-14 bg-blue-500 rounded-xl flex flex-col items-center justify-center text-white">
                            <span class="text-xs font-bold uppercase">Jan</span>
                            <span class="text-lg font-black leading-none">11</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900">Entraînement U15</div>
                            <div class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                17:30 - 19:00
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">L</div>
                                <div class="w-8 h-8 bg-red-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">S</div>
                                <div class="w-8 h-8 bg-slate-400 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">+4</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Session Item -->
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer">
                        <div class="w-14 h-14 bg-purple-500 rounded-xl flex flex-col items-center justify-center text-white">
                            <span class="text-xs font-bold uppercase">Jan</span>
                            <span class="text-lg font-black leading-none">12</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-900">Musculation</div>
                            <div class="text-sm text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                10:00 - 12:00
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 bg-pink-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">J</div>
                                <div class="w-8 h-8 bg-cyan-500 rounded-full border-2 border-white flex items-center justify-center text-xs font-bold text-white">+3</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            
            <!-- Club Info Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-white mb-1">Mon Club</h3>
                    <p class="text-emerald-100 text-sm mb-4">Configurez votre club</p>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-white/80 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Logo & couleurs</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/80 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Informations</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/60 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>PWA & QR Code</span>
                        </div>
                    </div>
                    
                    <button class="mt-6 w-full bg-white text-emerald-600 py-3 rounded-xl font-bold text-sm hover:bg-emerald-50 transition-colors">
                        Configurer mon club
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 border border-slate-100 shadow-sm">
                <h2 class="text-lg font-black text-slate-900 tracking-tight mb-6">Activité récente</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-900 font-medium">Nouveau membre inscrit</p>
                            <p class="text-xs text-slate-500">Marie Dupont a rejoint le club</p>
                            <p class="text-xs text-slate-400 mt-1">Il y a 2 heures</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-900 font-medium">Appel effectué</p>
                            <p class="text-xs text-slate-500">Entraînement Seniors - 18/24</p>
                            <p class="text-xs text-slate-400 mt-1">Hier à 21:15</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-slate-900 font-medium">Séance créée</p>
                            <p class="text-xs text-slate-500">Musculation - Samedi 10:00</p>
                            <p class="text-xs text-slate-400 mt-1">Il y a 2 jours</p>
                        </div>
                    </div>
                </div>
                
                <button class="mt-6 w-full py-3 text-sm font-semibold text-slate-600 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                    Voir toute l'activité
                </button>
            </div>

            <!-- Plan Status -->
            <div class="bg-slate-900 rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/20 rounded-full blur-2xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-emerald-400 bg-emerald-400/10 px-3 py-1 rounded-full">ESSAI GRATUIT</span>
                        <span class="text-xs text-slate-500">12 jours restants</span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-white mb-2">Passez à la version Pro</h3>
                    <p class="text-sm text-slate-400 mb-4">Débloquez toutes les fonctionnalités pour votre club.</p>
                    
                    <div class="w-full bg-slate-800 rounded-full h-2 mb-4">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 14%"></div>
                    </div>
                    
                    <button class="w-full bg-emerald-500 text-white py-3 rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors">
                        Voir les offres
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Section - Mobile Only -->
    <div class="lg:hidden">
        <!-- Mobile Quick Stats -->
        <div class="bg-white rounded-[1.5rem] p-6 border border-slate-100 shadow-sm">
            <h3 class="text-lg font-black text-slate-900 mb-4">Cette semaine</h3>
            <div class="flex items-center justify-between">
                <div class="text-center">
                    <div class="text-2xl font-black text-slate-900">8</div>
                    <div class="text-xs text-slate-500">Séances</div>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="text-center">
                    <div class="text-2xl font-black text-emerald-600">87%</div>
                    <div class="text-xs text-slate-500">Présence</div>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="text-center">
                    <div class="text-2xl font-black text-slate-900">16h</div>
                    <div class="text-xs text-slate-500">Entraînées</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
