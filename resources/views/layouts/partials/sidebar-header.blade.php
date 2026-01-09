{{-- Sidebar Header - Desktop --}}
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

{{-- Mobile Sidebar Header --}}
<div class="p-6 border-b border-slate-100 lg:hidden pt-20">
    <div class="flex items-center space-x-3 mb-4">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-lg" style="background-color: {{ $userRole?->color ?? '#10B981' }};">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div>
            <div class="font-bold text-slate-900">{{ Auth::user()->name }}</div>
            <div class="text-sm font-medium" style="color: {{ $userRole?->color ?? '#64748b' }};">{{ $userRole?->name ?? 'Membre' }}</div>
        </div>
    </div>
</div>

