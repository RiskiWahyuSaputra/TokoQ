@extends('layouts.tokoq.base')

@section('body')
<div class="min-h-screen bg-surface-bright">
    <header class="landing-header sticky top-0 z-50 border-b border-outline-variant bg-surface/85 backdrop-blur-md">
        <div class="landing-header-inner mx-auto flex h-20 max-w-7xl items-center justify-between px-container-padding">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-container text-on-primary-container">
                    <span class="material-symbols-outlined">storefront</span>
                </div>
                <div>
                    <p class="text-h3 font-bold text-primary">TokoQ</p>
                    <p class="text-body-sm text-on-surface-variant">Digital Twin UMKM</p>
                </div>
            </a>

            <nav class="landing-desktop-nav hidden items-center gap-8 md:flex">
                <a href="#fitur" class="font-medium text-on-surface-variant transition-colors hover:text-primary">Fitur</a>
                <a href="#masalah" class="font-medium text-on-surface-variant transition-colors hover:text-primary">Masalah</a>
                <a href="#alur" class="font-medium text-on-surface-variant transition-colors hover:text-primary">Alur Kerja</a>
                <a href="{{ route('dashboard') }}" class="font-medium text-on-surface-variant transition-colors hover:text-primary">Dashboard</a>
                <a href="{{ route('onboarding') }}" class="font-medium text-on-surface-variant transition-colors hover:text-primary">Onboarding</a>
            </nav>

            <div class="flex items-center gap-3">
                <button type="button" class="landing-mobile-nav-toggle md:hidden" data-landing-menu-toggle aria-label="Buka menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <a href="{{ route('dashboard') }}" class="landing-primary-cta rounded-full bg-primary px-6 py-3 font-bold text-on-primary transition-opacity hover:opacity-90">
                    Demo App
                </a>
            </div>
        </div>

        <div class="landing-mobile-menu md:hidden">
            <nav>
                <a href="#fitur" class="text-on-surface-variant">Fitur</a>
                <a href="#masalah" class="text-on-surface-variant">Masalah</a>
                <a href="#alur" class="text-on-surface-variant">Alur Kerja</a>
                <a href="{{ route('dashboard') }}" class="text-on-surface-variant">Dashboard</a>
                <a href="{{ route('onboarding') }}" class="text-on-surface-variant">Onboarding</a>
                <a href="{{ route('dashboard') }}" class="inline-flex justify-center rounded-2xl bg-primary px-6 py-3 font-bold text-on-primary">Buka Demo</a>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer class="border-t border-outline-variant bg-surface-container-low">
        <div class="landing-footer-meta mx-auto flex max-w-7xl flex-col gap-4 px-container-padding py-10 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-bold text-primary">TokoQ</p>
                <p class="text-body-sm text-on-surface-variant">UI template sudah dihubungkan ke Laravel melalui route, controller, model, dan Blade.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-outline-variant px-4 py-2 text-body-sm text-on-surface-variant transition-colors hover:border-primary hover:text-primary">Dashboard</a>
                <a href="{{ route('pos') }}" class="rounded-full border border-outline-variant px-4 py-2 text-body-sm text-on-surface-variant transition-colors hover:border-primary hover:text-primary">Kasir</a>
                <a href="{{ route('reports') }}" class="rounded-full border border-outline-variant px-4 py-2 text-body-sm text-on-surface-variant transition-colors hover:border-primary hover:text-primary">Laporan</a>
            </div>
        </div>
    </footer>
</div>
@endsection
