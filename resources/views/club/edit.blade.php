@extends('layouts.dashboard')

@section('title', 'Modifier le club')
@section('description', 'Modifiez les informations de votre club')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('club.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Modifier le club</h1>
                <p class="text-sm text-slate-500">{{ $club->name }}</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center gap-2">
            <a href="{{ route('club.index') }}" class="px-4 py-2 text-slate-600 rounded-lg font-semibold text-sm hover:bg-slate-100 transition-colors">
                Annuler
            </a>
            <button type="submit" form="club-form" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Enregistrer
            </button>
        </div>
    </div>

    <form id="club-form" method="POST" action="{{ route('club.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Logo -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-900 mb-4">Logo du club</h2>
                    
                    <div class="flex flex-col sm:flex-row items-start gap-6">
                        <!-- Preview -->
                        <div class="relative">
                            <div class="w-32 h-32 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-dashed border-slate-200" style="background-color: {{ $club->primary_color ?? '#10B981' }}15;" id="logo-preview-container">
                                @if($club->logo)
                                    <img src="{{ Storage::url($club->logo) }}" alt="{{ $club->name }}" class="w-full h-full object-cover" id="logo-preview">
                                @else
                                    <span class="text-4xl font-black" style="color: {{ $club->primary_color ?? '#10B981' }};" id="logo-initials">
                                        {{ strtoupper(substr($club->name, 0, 2)) }}
                                    </span>
                                @endif
                            </div>
                            @if($club->logo)
                                <form action="{{ route('club.delete-logo') }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg" onclick="return confirm('Supprimer le logo ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="flex-1">
                            <input type="file" name="logo" id="logo-input" class="hidden" accept="image/*" onchange="previewLogo(this)">
                            <label for="logo-input" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Choisir une image
                            </label>
                            <p class="text-sm text-slate-500 mt-2">PNG, JPG ou GIF. Max 2MB.<br>Recommandé : 512x512 pixels</p>
                            @error('logo')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-900 mb-4">Informations générales</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                Nom du club <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $club->name) }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Email du club
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $club->email) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $club->phone) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Ville
                                </label>
                                <input type="text" name="city" id="city" value="{{ old('city', $club->city) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Pays
                                </label>
                                <input type="text" name="country" id="country" value="{{ old('country', $club->country) }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all @error('country') border-red-500 @enderror">
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Preview -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-6 space-y-6">
                    <!-- Preview card -->
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 rounded-full blur-[40px]" style="background-color: {{ $club->primary_color ?? '#10B981' }}30;" id="preview-glow"></div>
                        
                        <div class="relative z-10">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-4">Aperçu</p>
                            
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center overflow-hidden" style="background-color: {{ $club->primary_color ?? '#10B981' }}20;" id="preview-logo-container">
                                    @if($club->logo)
                                        <img src="{{ Storage::url($club->logo) }}" alt="{{ $club->name }}" class="w-full h-full object-cover" id="preview-logo">
                                    @else
                                        <span class="text-2xl font-black" style="color: {{ $club->primary_color ?? '#10B981' }};" id="preview-initials">
                                            {{ strtoupper(substr($club->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-white" id="preview-name">{{ $club->name }}</div>
                                    <div class="text-sm text-slate-400" id="preview-location">{{ $club->city ?: 'Ville' }}</div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span id="preview-email">{{ $club->email ?: 'email@club.com' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span id="preview-phone">{{ $club->phone ?: '+33 1 23 45 67 89' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                        <div class="flex gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-blue-900 text-sm mb-1">Identité visuelle</h4>
                                <p class="text-sm text-blue-700">Pour personnaliser les couleurs de votre club, rendez-vous dans <a href="{{ route('club.customization') }}" class="underline font-medium">Personnalisation</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile actions -->
        <div class="sm:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-100 z-40">
            <div class="flex gap-3">
                <a href="{{ route('club.index') }}" class="flex-1 px-4 py-3 text-center text-slate-600 rounded-xl font-semibold bg-slate-100 hover:bg-slate-200 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="flex-1 px-4 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                    Enregistrer
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Live preview
document.getElementById('name').addEventListener('input', function() {
    document.getElementById('preview-name').textContent = this.value || 'Nom du club';
    const initials = this.value.substring(0, 2).toUpperCase() || 'AB';
    document.getElementById('preview-initials').textContent = initials;
    document.getElementById('logo-initials').textContent = initials;
});

document.getElementById('email').addEventListener('input', function() {
    document.getElementById('preview-email').textContent = this.value || 'email@club.com';
});

document.getElementById('phone').addEventListener('input', function() {
    document.getElementById('preview-phone').textContent = this.value || '+33 1 23 45 67 89';
});

document.getElementById('city').addEventListener('input', function() {
    document.getElementById('preview-location').textContent = this.value || 'Ville';
});

// Logo preview
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Main preview
            const container = document.getElementById('logo-preview-container');
            container.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover" id="logo-preview">';
            
            // Sidebar preview
            const previewContainer = document.getElementById('preview-logo-container');
            previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover" id="preview-logo">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

