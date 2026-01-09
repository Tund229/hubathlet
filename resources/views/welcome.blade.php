<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hubathlet - La plateforme sportive intelligente pour clubs modernes. Gestion d'entraînements, adhérents et statistiques dans une application unique et personnalisée.">
    
    <title>Hubathlet - Plateforme sportive intelligente pour clubs modernes</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .gradient-mesh {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out forwards;
    }

    /* Smooth scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    </style>
</head>
<body class="antialiased bg-white text-slate-900 font-sans">
    <!-- Navigation - Premium Glassmorphism -->
    <nav class="fixed top-0 w-full bg-white/70 backdrop-blur-2xl border-b border-slate-100/50 z-50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-18 py-4">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-black text-slate-900 tracking-tighter">
                            Hubathlet<span class="text-emerald-500">.</span>
                        </span>
                    </a>
                </div>
                <div class="hidden md:flex items-center gap-1">
                    <a href="#fonctionnalites" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">Fonctionnalités</a>
                    <a href="#tarifs" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">Tarifs</a>
                    <a href="#faq" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">FAQ</a>
                    @auth
                        <a href="/dashboard" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors rounded-xl hover:bg-slate-50">Connexion</a>
                        <a href="{{ route('register') }}" class="ml-3 px-6 py-2.5 text-sm font-bold text-white bg-slate-900 rounded-full hover:bg-emerald-600 transition-all shadow-lg shadow-slate-900/10 hover:shadow-emerald-500/20">
                            Commencer
                        </a>
                    @endauth
                </div>
                <div class="md:hidden">
                    <button type="button" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors" id="mobile-menu-button">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu - Premium Style -->
        <div class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-slate-100" id="mobile-menu">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#fonctionnalites" class="block px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">Fonctionnalités</a>
                <a href="#tarifs" class="block px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">Tarifs</a>
                <a href="#faq" class="block px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">FAQ</a>
                @auth
                    <a href="/dashboard" class="block px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-50 font-semibold transition-all">Connexion</a>
                    <a href="{{ route('register') }}" class="block mt-4 px-4 py-4 bg-slate-900 text-white rounded-2xl text-center font-bold hover:bg-emerald-600 transition-all">Commencer gratuitement</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-24 pb-16 px-4 overflow-hidden bg-slate-50">
        <!-- Background decorations -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/40 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-blue-200/30 rounded-full blur-[100px]"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-violet-100/20 rounded-full blur-[80px]"></div>
        </div>

        <div class="max-w-7xl mx-auto w-full">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                <!-- Text Content -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 rounded-full border border-emerald-100">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <span class="text-sm font-semibold text-emerald-700">+500 clubs nous font confiance</span>
                        </div>
                        <h1 class="text-5xl sm:text-6xl xl:text-7xl font-black text-slate-900 leading-[0.95] tracking-tighter">
                            Gérez votre club <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">sans effort.</span>
                        </h1>
                        <p class="text-lg md:text-xl text-slate-600 leading-relaxed max-w-md">
                            La plateforme tout-en-un qui centralise membres, planning et statistiques dans une interface moderne.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-emerald-500 text-white rounded-2xl font-bold text-center hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5">
                            Démarrer gratuitement
                        </a>
                        <a href="#fonctionnalites" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-2xl font-bold text-center hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            Découvrir
                        </a>
                    </div>

                    <div class="flex items-center gap-6 pt-6">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white font-bold text-sm">M</div>
                            <div class="w-10 h-10 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white font-bold text-sm">A</div>
                            <div class="w-10 h-10 rounded-full bg-violet-500 border-2 border-white flex items-center justify-center text-white font-bold text-sm">L</div>
                            <div class="w-10 h-10 rounded-full bg-amber-500 border-2 border-white flex items-center justify-center text-white font-bold text-sm">+</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-slate-500">4.9/5 sur 200+ avis</p>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Preview -->
                <div class="lg:col-span-7 relative">
                    <!-- Main Dashboard Card -->
                    <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                        <!-- Header bar -->
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-900">FC Hubathlet</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold text-xs">JD</div>
                            </div>
                        </div>
                        
                        <!-- Dashboard Content -->
                        <div class="p-5 space-y-4">
                            <!-- Stats Row -->
                            <div class="grid grid-cols-4 gap-3">
                                <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">+12%</span>
                                    </div>
                                    <div class="text-xl font-black text-slate-900">48</div>
                                    <div class="text-[10px] text-slate-500 font-medium">Membres</div>
                                </div>
                                <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">Excellent</span>
                                    </div>
                                    <div class="text-xl font-black text-slate-900">92%</div>
                                    <div class="text-[10px] text-slate-500 font-medium">Présence</div>
                                </div>
                                <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-violet-600 bg-violet-50 px-1.5 py-0.5 rounded">Ce mois</span>
                                    </div>
                                    <div class="text-xl font-black text-slate-900">16</div>
                                    <div class="text-[10px] text-slate-500 font-medium">Séances</div>
                                </div>
                                <div class="bg-white rounded-xl p-3 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Total</span>
                                    </div>
                                    <div class="text-xl font-black text-slate-900">124<span class="text-sm">h</span></div>
                                    <div class="text-[10px] text-slate-500 font-medium">Heures</div>
                                </div>
                            </div>
                            
                            <!-- Chart + Top members -->
                            <div class="grid grid-cols-5 gap-3">
                                <!-- Weekly Chart -->
                                <div class="col-span-3 bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-xs font-bold text-slate-900">Présence hebdomadaire</span>
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                                    </div>
                                    <div class="flex items-end justify-between gap-1.5 h-20">
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded" style="height: 85%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">L</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded" style="height: 92%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">M</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded" style="height: 78%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">M</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded" style="height: 95%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">J</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-amber-500 rounded" style="height: 65%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">V</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 rounded" style="height: 88%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">S</span>
                                        </div>
                                        <div class="flex-1 flex flex-col items-center gap-1">
                                            <div class="w-full bg-slate-100 rounded relative" style="height: 60px;">
                                                <div class="absolute bottom-0 left-0 right-0 bg-slate-300 rounded" style="height: 40%;"></div>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium">D</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Top Members mini -->
                                <div class="col-span-2 bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                    <span class="text-xs font-bold text-slate-900 mb-3 block">Top présences</span>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[10px] font-black">1</span>
                                            <div class="w-6 h-6 rounded-lg bg-emerald-500 flex items-center justify-center text-white font-bold text-[10px]">ML</div>
                                            <span class="text-[11px] font-medium text-slate-700 flex-1 truncate">Marie L.</span>
                                            <span class="text-[10px] font-bold text-emerald-600">98%</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px] font-black">2</span>
                                            <div class="w-6 h-6 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold text-[10px]">TD</div>
                                            <span class="text-[11px] font-medium text-slate-700 flex-1 truncate">Thomas D.</span>
                                            <span class="text-[10px] font-bold text-emerald-600">95%</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-black">3</span>
                                            <div class="w-6 h-6 rounded-lg bg-violet-500 flex items-center justify-center text-white font-bold text-[10px]">SB</div>
                                            <span class="text-[11px] font-medium text-slate-700 flex-1 truncate">Sophie B.</span>
                                            <span class="text-[10px] font-bold text-emerald-600">93%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Upcoming Sessions -->
                            <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-slate-900">Prochaines séances</span>
                                    <span class="text-[10px] font-semibold text-emerald-600">Voir tout →</span>
                                </div>
                                <div class="flex gap-2">
                                    <div class="flex-1 flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex flex-col items-center justify-center text-white">
                                            <span class="text-[8px] font-bold uppercase">Jan</span>
                                            <span class="text-sm font-black leading-none">12</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[11px] font-semibold text-slate-900 truncate">Entraînement Seniors</div>
                                            <div class="text-[10px] text-slate-500">19:00 - 21:00</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex items-center gap-2 p-2 bg-slate-50 rounded-lg">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex flex-col items-center justify-center text-white">
                                            <span class="text-[8px] font-bold uppercase">Jan</span>
                                            <span class="text-sm font-black leading-none">14</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-[11px] font-semibold text-slate-900 truncate">Match Amical</div>
                                            <div class="text-[10px] text-slate-500">15:00 - 17:00</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Floating attendance card -->
                    <div class="absolute -bottom-4 -right-4 sm:-right-8 bg-gradient-to-br from-slate-800 to-slate-900 p-4 rounded-xl shadow-xl z-10" style="animation: float 5s ease-in-out infinite; animation-delay: 1s;">
                        <div class="text-[10px] text-slate-400 font-medium mb-1">Taux de présence</div>
                        <div class="flex items-end gap-2">
                            <span class="text-2xl font-black text-white">92%</span>
                            <span class="text-xs font-bold text-emerald-400 mb-1">↑ +5%</span>
                        </div>
                        <div class="w-24 bg-slate-700 rounded-full h-1.5 mt-2">
                            <div class="h-1.5 rounded-full bg-emerald-500" style="width: 92%"></div>
                        </div>
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="absolute -right-12 -top-12 w-48 h-48 border-[24px] border-emerald-100 rounded-full -z-10 opacity-50"></div>
                    <div class="absolute -left-8 -bottom-8 w-32 h-32 border-[16px] border-blue-100 rounded-full -z-10 opacity-50"></div>
                </div>
            </div>
        </div>
    </section>


    <!-- Social Proof -->
    <section class="relative py-12 border-y border-slate-100 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent to-slate-200"></div>
                <p class="text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-[0.3em] whitespace-nowrap">
                    Ils propulsent leur performance avec nous
                </p>
                <div class="h-px flex-1 bg-gradient-to-l from-transparent to-slate-200"></div>
            </div>
        </div>

        <div class="relative flex overflow-x-hidden">
            <div class="flex animate-marquee whitespace-nowrap items-center gap-16 md:gap-24 py-4">
                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">CP</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Club Sportif Paris</span>
                </div>

                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">LA</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Lyon Athletic</span>
                </div>

                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">MC</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Marseille FC</span>
                </div>

                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">TS</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Toulouse Sports</span>
                </div>
                
                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">NB</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Nantes Basket</span>
                </div>
            </div>

            <div class="absolute top-0 flex animate-marquee2 whitespace-nowrap items-center gap-16 md:gap-24 py-4 ml-[100%]">
                <div class="flex items-center gap-2 group cursor-default">
                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <span class="font-black text-slate-400 group-hover:text-emerald-600 transition-colors">CP</span>
                    </div>
                    <span class="text-xl font-bold text-slate-400 group-hover:text-slate-900 transition-colors">Club Sportif Paris</span>
                </div>
                </div>
        </div>

        <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-white to-transparent pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-white to-transparent pointer-events-none"></div>
    </section>

    <style>
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes marquee2 {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        .animate-marquee2 {
            animation: marquee2 30s linear infinite;
        }
    </style>

    <!-- Features Section -->
    <section id="fonctionnalites" class="relative py-32 px-4 sm:px-6 lg:px-8 overflow-hidden bg-white">
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-emerald-50/50 rounded-full blur-[120px] -z-10"></div>
    
    <div class="max-w-7xl mx-auto">
        <div class="max-w-3xl mb-20">
            <h2 class="text-base font-bold text-emerald-600 uppercase tracking-[0.2em] mb-4">L'excellence opérationnelle</h2>
            <p class="text-5xl md:text-6xl font-black text-slate-900 leading-[1.1] tracking-tighter">
                Tout ce dont votre club a besoin, <span class="text-slate-400">sans le superflu.</span>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <div class="md:col-span-7 group relative overflow-hidden rounded-[2.5rem] bg-slate-900 p-10 text-white transition-all hover:shadow-2xl hover:shadow-emerald-500/10">
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/40">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h3 class="text-3xl font-bold mb-4">Planning intelligent</h3>
                        <p class="text-slate-400 text-lg max-w-sm leading-relaxed">
                            Notifications automatiques et synchronisation en temps réel. Vos adhérents ne manquent plus une séance.
                        </p>
                    </div>
                    <div class="mt-8 flex gap-2">
                        <div class="h-1 w-20 bg-emerald-500 rounded-full"></div>
                        <div class="h-1 w-8 bg-slate-700 rounded-full"></div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 blur-[80px] group-hover:bg-emerald-500/20 transition-all"></div>
            </div>

            <div class="md:col-span-5 group relative rounded-[2.5rem] bg-emerald-50 p-10 transition-all hover:bg-emerald-100/50">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform text-emerald-600 font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Présence 1-clic</h3>
                <p class="text-slate-600 leading-relaxed">
                    Suivez l'assiduité instantanément. QR code ou liste rapide, c'est vous qui choisissez.
                </p>
            </div>

            <div class="md:col-span-5 group relative rounded-[2.5rem] border border-slate-100 bg-white p-10 transition-all hover:border-emerald-200 hover:shadow-xl">
                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10" /></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-4">Stats avancées</h3>
                <p class="text-slate-600 leading-relaxed">
                    Des tableaux de bord précis pour piloter la croissance de votre club et engager vos membres.
                </p>
            </div>

            <div class="md:col-span-7 group relative overflow-hidden rounded-[2.5rem] border border-slate-100 bg-white p-10 transition-all hover:border-emerald-200 hover:shadow-xl">
                <div class="grid sm:grid-cols-2 gap-8 items-center h-full">
                    <div>
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">White-label</h3>
                        <p class="text-slate-600 leading-relaxed">
                            Une application à vos couleurs. Votre logo, votre identité, votre succès.
                        </p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl h-32 flex items-center justify-center border border-dashed border-slate-200">
                        <span class="text-slate-400 font-medium text-sm italic">Votre Logo Ici</span>
                    </div>
                </div>
            </div>

            <div class="md:col-span-6 group relative rounded-[2.5rem] bg-slate-50 p-8 flex items-start gap-6 transition-all hover:bg-white hover:shadow-lg border border-transparent hover:border-slate-100">
                <div class="flex-shrink-0 w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8z" /></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">PWA Installable</h3>
                    <p class="text-slate-500 text-sm">Zéro friction. Pas besoin de passer par l'App Store pour vos membres.</p>
                </div>
            </div>

            <div class="md:col-span-6 group relative rounded-[2.5rem] bg-slate-50 p-8 flex items-start gap-6 transition-all hover:bg-white hover:shadow-lg border border-transparent hover:border-slate-100">
                <div class="flex-shrink-0 w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Déploiement express</h3>
                    <p class="text-slate-500 text-sm">Opérationnel en 5 minutes. Importez vos membres et commencez.</p>
                </div>
            </div>

        </div>
    </div>
</section>

    <!-- Pricing Section - Bento Asymmetric Grid -->
    <section id="tarifs" class="relative py-32 px-4 sm:px-6 lg:px-8 overflow-hidden bg-slate-50/50">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 mix-blend-soft-light"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-emerald-100/30 rounded-full blur-[150px] -z-10"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="max-w-3xl mb-20">
                <h2 class="text-base font-bold text-emerald-600 uppercase tracking-[0.2em] mb-4">Investissement intelligent</h2>
                <p class="text-5xl md:text-6xl font-black text-slate-900 leading-[1.1] tracking-tighter">
                    Un tarif pour chaque <span class="text-slate-400">stade de croissance.</span>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch">
                
                <!-- Starter Plan - Bento Card -->
                <div class="md:col-span-4 group bg-white rounded-[2.5rem] p-10 border border-slate-100 flex flex-col justify-between transition-all hover:shadow-2xl hover:shadow-emerald-500/5 hover:border-emerald-100">
                    <div>
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900">Starter</h3>
                        </div>
                        <p class="text-slate-500 mb-8 leading-relaxed">L'essentiel pour découvrir et bien démarrer.</p>
                        <div class="mb-10">
                            <span class="text-5xl font-black text-slate-900">Gratuit</span>
                            <p class="text-sm text-slate-400 mt-2">14 jours d'essai complet</p>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex items-center gap-3 text-slate-600">
                                <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                50 adhérents max
                            </li>
                            <li class="flex items-center gap-3 text-slate-600">
                                <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                Fonctions de base
                            </li>
                            <li class="flex items-center gap-3 text-slate-600">
                                <div class="w-5 h-5 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                Support email
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('register') }}" class="mt-10 block w-full py-4 px-6 text-center bg-slate-50 text-slate-900 rounded-2xl font-bold hover:bg-slate-100 transition-all group-hover:bg-slate-900 group-hover:text-white">
                        Commencer
                    </a>
                </div>

                <!-- Pro Plan - Featured Bento Large -->
                <div class="md:col-span-5 bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-emerald-500/20">
                    <div class="absolute top-0 right-0 bg-emerald-500 text-white px-6 py-2 text-[10px] font-black tracking-[0.2em] rounded-bl-3xl uppercase">
                        Recommandé
                    </div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-[80px]"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 rounded-full blur-[60px]"></div>
                    
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                </div>
                                <h3 class="text-3xl font-bold text-emerald-400">Pro</h3>
                            </div>
                            <p class="text-slate-400 mb-8 text-lg leading-relaxed">Le moteur de votre performance quotidienne.</p>
                            <div class="mb-10">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-6xl font-black">49€</span>
                                    <span class="text-slate-500">/mois</span>
                                </div>
                                <p class="text-sm text-slate-500 mt-2">Facturé annuellement • Économisez 20%</p>
                            </div>
                            <ul class="space-y-5">
                                <li class="flex items-center gap-4">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="text-slate-200">Adhérents illimités</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="text-slate-200">White-label complet</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="text-slate-200">Support prioritaire 24/7</span>
                                </li>
                                <li class="flex items-center gap-4">
                                    <div class="w-6 h-6 bg-emerald-500/20 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <span class="text-slate-200">Analytics avancés</span>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('register') }}" class="mt-12 block w-full py-5 px-6 text-center bg-emerald-500 text-white rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 hover:-translate-y-1">
                            Passer au Pro
                        </a>
                    </div>
                </div>

                <!-- Enterprise - Bento Vertical Accent -->
                <div class="md:col-span-3 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-[2.5rem] p-10 border border-emerald-100 flex flex-col justify-between transition-all hover:shadow-xl hover:shadow-emerald-500/10">
                    <div>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center mb-6 shadow-sm">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Club Elite</h3>
                        <p class="text-slate-600 mb-8 leading-relaxed">Pour les fédérations et structures multi-sites.</p>
                        <div class="text-emerald-600 font-black text-3xl mb-8 tracking-tight">Sur-mesure</div>
                        <div class="space-y-4">
                            <div class="h-1 w-16 bg-emerald-300 rounded-full"></div>
                            <p class="text-sm text-slate-500 italic leading-relaxed">API dédiée, intégrations personnalisées, account manager.</p>
                        </div>
                    </div>
                    <button class="mt-10 block w-full py-4 px-6 text-center bg-white text-emerald-600 border border-emerald-200 rounded-2xl font-bold hover:shadow-lg hover:border-emerald-300 transition-all">
                        Contactez-nous
                    </button>
                </div>
            </div>

            <!-- Trust Badge -->
            <div class="mt-16 flex flex-wrap items-center justify-center gap-8 text-sm text-slate-500">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span>Paiement sécurisé</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    <span>Annulation à tout moment</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    <span>Sans engagement</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section - Bento Dashboard Layout -->
    <section id="faq" class="relative py-32 px-4 sm:px-6 lg:px-8 bg-white overflow-hidden">
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-slate-50 rounded-full blur-[120px] -z-10"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid lg:grid-cols-12 gap-16 items-start">
                
                <!-- Left Column - Title & CTA Card -->
                <div class="lg:col-span-4 lg:sticky lg:top-32">
                    <h2 class="text-base font-bold text-emerald-600 uppercase tracking-[0.2em] mb-4">FAQ</h2>
                    <p class="text-5xl font-black text-slate-900 leading-[1.05] tracking-tighter mb-6">
                        Des réponses <br><span class="text-slate-400">transparentes.</span>
                    </p>
                    <p class="text-lg text-slate-600 leading-relaxed mb-10">
                        Tout ce que vous devez savoir pour propulser votre club sereinement.
                    </p>
                    
                    <!-- Floating CTA Card with Glassmorphism -->
                    <div class="relative p-8 bg-white/80 backdrop-blur-xl rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/50 rounded-full blur-[50px] group-hover:bg-emerald-200/50 transition-all"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            </div>
                            <p class="text-slate-900 font-bold mb-2">Encore une question ?</p>
                            <p class="text-sm text-slate-500 mb-6">Notre équipe d'experts répond en moins de 24h.</p>
                            <a href="#" class="inline-flex items-center gap-2 text-emerald-600 font-bold group-hover:gap-4 transition-all">
                                Échanger avec nous
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column - FAQ Items -->
                <div class="lg:col-span-8 space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="group bg-slate-50 rounded-[2rem] border border-transparent p-8 transition-all hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:border-slate-100">
                        <button class="w-full text-left flex justify-between items-start gap-4" onclick="toggleFAQ(this)">
                            <span class="text-xl font-bold text-slate-900 leading-tight">Combien de temps prend l'installation ?</span>
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all flex-shrink-0">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                        <div class="hidden mt-6 text-slate-600 leading-relaxed max-w-2xl pl-0">
                            <p>Moins de 5 minutes. Créez votre compte, importez vos membres via Excel ou manuellement, personnalisez votre identité visuelle et partagez le lien avec vos adhérents. C'est tout.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="group bg-slate-50 rounded-[2rem] border border-transparent p-8 transition-all hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:border-slate-100">
                        <button class="w-full text-left flex justify-between items-start gap-4" onclick="toggleFAQ(this)">
                            <span class="text-xl font-bold text-slate-900 leading-tight">Puis-je essayer gratuitement ?</span>
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all flex-shrink-0">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                        <div class="hidden mt-6 text-slate-600 leading-relaxed max-w-2xl pl-0">
                            <p>Absolument ! Profitez de 14 jours d'essai complet, sans carte bancaire requise. Testez toutes les fonctionnalités Pro sans aucune limite.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="group bg-slate-50 rounded-[2rem] border border-transparent p-8 transition-all hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:border-slate-100">
                        <button class="w-full text-left flex justify-between items-start gap-4" onclick="toggleFAQ(this)">
                            <span class="text-xl font-bold text-slate-900 leading-tight">Mes données sont-elles sécurisées ?</span>
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all flex-shrink-0">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                        <div class="hidden mt-6 text-slate-600 leading-relaxed max-w-2xl pl-0">
                            <p>Hubathlet utilise un chiffrement AES-256 de niveau bancaire. Vos données sont hébergées en France sur des serveurs certifiés et conformes au RGPD. Nous ne revendons jamais vos données.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="group bg-slate-50 rounded-[2rem] border border-transparent p-8 transition-all hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:border-slate-100">
                        <button class="w-full text-left flex justify-between items-start gap-4" onclick="toggleFAQ(this)">
                            <span class="text-xl font-bold text-slate-900 leading-tight">L'application fonctionne-t-elle hors-ligne ?</span>
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all flex-shrink-0">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                        <div class="hidden mt-6 text-slate-600 leading-relaxed max-w-2xl pl-0">
                            <p>Grâce à notre technologie PWA, vos membres peuvent consulter leur planning et leurs statistiques même avec une connexion instable. Les données se synchronisent automatiquement dès le retour en ligne.</p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="group bg-slate-50 rounded-[2rem] border border-transparent p-8 transition-all hover:bg-white hover:shadow-xl hover:shadow-emerald-500/5 hover:border-slate-100">
                        <button class="w-full text-left flex justify-between items-start gap-4" onclick="toggleFAQ(this)">
                            <span class="text-xl font-bold text-slate-900 leading-tight">Comment personnaliser l'application à mon image ?</span>
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all flex-shrink-0">
                                <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </button>
                        <div class="hidden mt-6 text-slate-600 leading-relaxed max-w-2xl pl-0">
                            <p>Depuis votre tableau de bord, uploadez votre logo, choisissez vos couleurs et personnalisez le nom de l'application. Vos adhérents auront l'impression d'utiliser l'application officielle de votre club.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA - Immersive Bento Card -->
    <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="relative rounded-[3rem] bg-slate-900 p-12 md:p-20 lg:p-24 overflow-hidden shadow-[0_50px_100px_rgba(0,0,0,0.15)]">
                
                <!-- Background Effects -->
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-soft-light"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 via-transparent to-blue-500/10"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-emerald-500 rounded-full blur-[120px] opacity-20"></div>
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-blue-500 rounded-full blur-[120px] opacity-15"></div>
                
                <!-- Floating Elements -->
                <div class="absolute top-10 right-10 w-20 h-20 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hidden lg:flex items-center justify-center animate-bounce-slow">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div class="absolute bottom-10 left-10 w-16 h-16 bg-emerald-500/20 backdrop-blur-sm rounded-xl border border-emerald-500/20 hidden lg:flex items-center justify-center" style="animation: bounce-slow 5s ease-in-out infinite 1s;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 max-w-3xl mx-auto text-center space-y-10">
                    <h2 class="text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[0.95] tracking-tighter">
                        Prêt à passer au <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">niveau supérieur ?</span>
                    </h2>
                    <p class="text-xl text-slate-400 leading-relaxed max-w-xl mx-auto">
                        Rejoignez les +500 clubs qui ont automatisé leur gestion avec Hubathlet.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-5 justify-center pt-4">
                        @auth
                            <a href="/dashboard" class="px-10 py-5 bg-emerald-500 text-white rounded-full font-black text-lg hover:bg-emerald-600 transition-all shadow-[0_20px_50px_rgba(16,185,129,0.4)] hover:-translate-y-1 hover:shadow-[0_30px_60px_rgba(16,185,129,0.5)]">
                                Accéder au dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-10 py-5 bg-emerald-500 text-white rounded-full font-black text-lg hover:bg-emerald-600 transition-all shadow-[0_20px_50px_rgba(16,185,129,0.4)] hover:-translate-y-1 hover:shadow-[0_30px_60px_rgba(16,185,129,0.5)]">
                                Démarrer gratuitement
                            </a>
                            <button type="button" onclick="openDemoModal()" class="px-10 py-5 bg-white/5 backdrop-blur-md text-white border border-white/10 rounded-full font-bold text-lg hover:bg-white/10 hover:border-white/20 transition-all">
                                Voir l'interface
                            </button>
                        @endauth
                    </div>
                    <p class="text-sm text-slate-500 font-medium pt-2">
                        ✓ Aucune carte bancaire &nbsp;&nbsp; ✓ Setup en 5 min &nbsp;&nbsp; ✓ Support inclus
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Premium Minimal Bento -->
    <footer class="relative bg-white pt-24 pb-12 px-4 sm:px-6 lg:px-8 border-t border-slate-50">
        <div class="max-w-7xl mx-auto">
            
            <!-- Footer Grid - Bento Style -->
            <div class="grid grid-cols-2 md:grid-cols-12 gap-10 md:gap-8 mb-20">
                
                <!-- Brand Column -->
                <div class="col-span-2 md:col-span-4 space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-slate-900 tracking-tighter">Hubathlet<span class="text-emerald-500">.</span></span>
                    </div>
                    <p class="text-slate-500 leading-relaxed max-w-xs">
                        Le moteur intelligent des clubs de sport modernes. Gestion, engagement et performance centralisés.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Links Columns -->
                <div class="col-span-1 md:col-span-2">
                    <h4 class="font-black text-slate-900 uppercase tracking-[0.15em] text-xs mb-6">Plateforme</h4>
                    <ul class="space-y-4 text-slate-500 font-medium">
                        <li><a href="#fonctionnalites" class="hover:text-emerald-600 transition-colors">Fonctionnalités</a></li>
                        <li><a href="#tarifs" class="hover:text-emerald-600 transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-emerald-600 transition-colors">Intégrations</a></li>
                        <li><a href="#" class="hover:text-emerald-600 transition-colors">Changelog</a></li>
                    </ul>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <h4 class="font-black text-slate-900 uppercase tracking-[0.15em] text-xs mb-6">Ressources</h4>
                    <ul class="space-y-4 text-slate-500 font-medium">
                        <li><a href="#" class="hover:text-emerald-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-emerald-600 transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-emerald-600 transition-colors">Guide PWA</a></li>
                        <li><a href="#faq" class="hover:text-emerald-600 transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Newsletter Card - Bento Style -->
                <div class="col-span-2 md:col-span-4">
                    <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/50 rounded-full blur-[50px] opacity-0 group-hover:opacity-100 transition-all"></div>
                        <div class="relative z-10">
                            <p class="font-black text-slate-900 uppercase tracking-[0.15em] text-xs mb-4">Newsletter Performance</p>
                            <p class="text-sm text-slate-500 mb-6">Conseils et astuces pour optimiser votre club, chaque semaine.</p>
                            <div class="flex gap-2">
                                <input type="email" placeholder="votre@email.com" class="flex-1 bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-300 transition-all">
                                <button class="bg-slate-900 text-white px-5 py-3 rounded-xl text-sm font-bold hover:bg-emerald-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 pt-10 border-t border-slate-100">
                <p class="text-slate-400 text-sm">© {{ date('Y') }} Hubathlet. Conçu pour la performance.</p>
                <div class="flex flex-wrap justify-center gap-8 text-slate-400 text-sm font-medium">
                    <a href="#" class="hover:text-slate-900 transition-colors">Mentions légales</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Confidentialité</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">CGU</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu?.classList.toggle('hidden');
        });

        // Smooth scroll with offset for fixed nav
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const target = document.querySelector(targetId);
                if (target) {
                    const offset = 100;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    document.getElementById('mobile-menu')?.classList.add('hidden');
                }
            });
        });

        // FAQ toggle with smooth animation
        function toggleFAQ(button) {
            const content = button.nextElementSibling;
            const icon = button.querySelector('svg');
            const parent = button.parentElement;
            const isOpen = !content.classList.contains('hidden');
            
            // Close all other FAQs
            document.querySelectorAll('#faq [data-faq-content]').forEach(el => {
                if (el !== content) {
                    el.classList.add('hidden');
                    el.parentElement.classList.remove('bg-white', 'shadow-xl', 'border-slate-100');
                    el.parentElement.classList.add('bg-slate-50', 'border-transparent');
                    const otherIcon = el.previousElementSibling.querySelector('svg');
                    if (otherIcon) otherIcon.style.transform = 'rotate(0deg)';
                }
            });
            
            if (isOpen) {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                parent.classList.remove('bg-white', 'shadow-xl', 'border-slate-100');
                parent.classList.add('bg-slate-50', 'border-transparent');
            } else {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                parent.classList.add('bg-white', 'shadow-xl', 'border-slate-100');
                parent.classList.remove('bg-slate-50', 'border-transparent');
            }
        }

        function openDemoModal() {
            alert('Démo à venir ! Contactez-nous pour une démonstration personnalisée.');
        }

        // Add scroll-triggered animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                }
            });
        }, observerOptions);

        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>
