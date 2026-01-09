@extends('layouts.app-base')

@section('body')
@php
    $currentClub = Auth::user()->clubs()->first();
    $userRole = $currentClub ? Auth::user()->roleInClub($currentClub) : null;
@endphp

<div class="min-h-screen flex flex-col lg:flex-row">
    
    @include('layouts.partials.mobile-header')

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-slate-200/50 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-out flex flex-col">
        
        @include('layouts.partials.sidebar-header', ['userRole' => $userRole])
        
        @include('layouts.partials.nav-admin')
        
        @include('layouts.partials.sidebar-footer', ['userRole' => $userRole])
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 min-h-screen pt-16 lg:pt-0">
        @yield('content')
    </main>
</div>
@endsection

