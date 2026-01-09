{{-- Navigation Joueur --}}
<nav class="flex-1 p-4 space-y-1 overflow-y-auto no-scrollbar">
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Menu principal</span>
    </div>
    
    <a href="{{ route('player.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('player.dashboard')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Tableau de bord</span>
    </a>
    
    <a href="{{ route('player.schedule') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('player.schedule')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Mon planning</span>
    </a>
    
    <a href="{{ route('attendance.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('attendance.*')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Mes présences</span>
    </a>
    
    <a href="{{ route('player.stats') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('player.stats')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span>Mes statistiques</span>
    </a>

    <div class="mt-8 mb-6">
        <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Mon profil</span>
    </div>
    
    <a href="{{ route('player.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('player.profile')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Mon profil</span>
    </a>
    
    <a href="{{ route('player.settings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('player.settings')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Paramètres</span>
    </a>
</nav>

