{{-- Sidebar Footer --}}
<div class="p-4 border-t border-slate-100">
    {{-- User Profile (Desktop) --}}
    <div class="hidden lg:flex items-center space-x-3 p-3 rounded-xl bg-slate-50 mb-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold" style="background-color: {{ $userRole?->color ?? '#10B981' }};">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</div>
            <div class="text-xs font-medium truncate" style="color: {{ $userRole?->color ?? '#64748b' }};">{{ $userRole?->name ?? 'Membre' }}</div>
        </div>
    </div>
    
    {{-- Logout --}}
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

