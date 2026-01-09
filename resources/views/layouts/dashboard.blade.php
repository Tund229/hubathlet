<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Hubathlet - Tableau de bord')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - Hubathlet</title>

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
        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
        
        /* Hide scrollbar for mobile menu */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased bg-slate-50 text-slate-900 font-sans">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <!-- Mobile Header -->
        <header class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50">
            <div class="flex items-center justify-between px-4 h-16">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-black tracking-tight text-slate-900">Hubathlet<span class="text-emerald-500">.</span></span>
                </a>
                
                <!-- Mobile Menu Button -->
                <button 
                    onclick="toggleMobileMenu()" 
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"
                >
                    <svg id="menu-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="lg:hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden" onclick="toggleMobileMenu()"></div>

        <!-- Sidebar (Desktop: fixed, Mobile: slide-in) -->
        <aside id="sidebar" class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-slate-200/50 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col">
            
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-slate-100 hidden lg:block">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black tracking-tight text-slate-900">Hubathlet<span class="text-emerald-500">.</span></span>
                </a>
            </div>
            
            <!-- Mobile Sidebar Header -->
            <div class="p-6 border-b border-slate-100 lg:hidden pt-20">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-slate-500">Propriétaire</div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto no-scrollbar">
                <div class="mb-6">
                    <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Menu principal</span>
                </div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('dashboard')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Tableau de bord</span>
                </a>
                
                <a href="{{ route('members.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('members.*')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Membres</span>
                </a>
                
                <a href="{{ route('planning.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('planning.*')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Planning</span>
                </a>
                
                <a href="{{ route('statistics.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('statistics.*')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Statistiques</span>
                </a>

                <div class="mt-8 mb-6">
                    <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Configuration</span>
                </div>
                
                <a href="{{ route('club.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('club.index') || request()->routeIs('club.edit')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Mon club</span>
                </a>
                
                <a href="{{ route('club.customization') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('club.customization')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                    <span>Personnalisation</span>
                </a>
                
                <a href="{{ route('club.settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('club.settings')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Paramètres</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-slate-100">
                <!-- User Profile (Desktop) -->
                <div class="hidden lg:flex items-center space-x-3 p-3 rounded-xl bg-slate-50 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-xl font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen pt-16 lg:pt-0">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
            
            // Prevent body scroll when menu is open
            document.body.classList.toggle('overflow-hidden');
        }
    </script>
    
    @stack('scripts')
</body>
</html>

