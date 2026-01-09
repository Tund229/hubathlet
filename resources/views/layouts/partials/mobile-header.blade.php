{{-- Mobile Header --}}
<header class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-slate-200/50">
    <div class="flex items-center justify-between px-4 h-16">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-lg font-black tracking-tight text-slate-900">Hubathlet<span class="text-emerald-500">.</span></span>
        </a>
        
        {{-- Mobile Menu Button --}}
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

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay" class="lg:hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden" onclick="toggleMobileMenu()"></div>

