@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        
        {{-- Mobile pagination info --}}
        <div class="flex items-center justify-between w-full sm:hidden">
            <div class="text-sm text-slate-500">
                <span class="font-semibold text-slate-900">{{ $paginator->firstItem() ?? 0 }}</span>
                -
                <span class="font-semibold text-slate-900">{{ $paginator->lastItem() ?? 0 }}</span>
                sur
                <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
            </div>
            <div class="flex gap-2">
                {{-- Previous (Mobile) --}}
                @if ($paginator->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Next (Mobile) --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-300 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>

        {{-- Desktop pagination --}}
        <div class="hidden sm:flex sm:items-center sm:gap-4 w-full">
            {{-- Results info --}}
            <div class="text-sm text-slate-500">
                Affichage de
                <span class="font-semibold text-slate-900">{{ $paginator->firstItem() ?? 0 }}</span>
                à
                <span class="font-semibold text-slate-900">{{ $paginator->lastItem() ?? 0 }}</span>
                sur
                <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
                résultats
            </div>

            {{-- Pagination links --}}
            <div class="flex items-center gap-1 ml-auto">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all" rel="prev">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="w-9 h-9 flex items-center justify-center text-slate-400 text-sm">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-emerald-500 text-white font-bold text-sm shadow-sm shadow-emerald-500/25" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 font-medium text-sm hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all" rel="next">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-300 cursor-not-allowed" aria-disabled="true">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
