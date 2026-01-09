{{-- Navigation Parent --}}
<nav class="flex-1 p-4 space-y-1 overflow-y-auto no-scrollbar">
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Menu principal</span>
    </div>
    
    <a href="{{ route('parent.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('parent.dashboard')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span>Tableau de bord</span>
    </a>
    
    <a href="{{ route('parent.children') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('parent.children*')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>Mes enfants</span>
    </a>
    
    <a href="{{ route('parent.schedule') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('parent.schedule')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>Planning</span>
    </a>

    <div class="mt-8 mb-6">
        <span class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Mon compte</span>
    </div>
    
    <a href="{{ route('parent.profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl font-medium transition-all @if(request()->routeIs('parent.profile')) bg-emerald-50 text-emerald-700 @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span>Mon profil</span>
    </a>
</nav>

