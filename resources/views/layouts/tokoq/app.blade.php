@extends('layouts.tokoq.base')

@section('body')
<div class="app-shell min-h-screen bg-background text-on-background">
    <aside class="app-sidebar fixed left-0 top-0 z-50 flex h-screen w-64 flex-col bg-inverse-surface px-4 py-8 shadow-md shadow-[#49592A]/10">
        <div class="mb-10 px-4">
            <a href="{{ route('dashboard') }}" class="block">
                <h1 class="text-h2 font-bold text-primary-fixed">TokoQ</h1>
                <p class="text-body-sm text-surface-variant/80">{{ $owner['store_name'] }}</p>
            </a>
        </div>

        <nav class="flex-1 space-y-2">
            @foreach ($navItems as $item)
                @php($active = request()->routeIs($item['route']))
                <a href="{{ route($item['route']) }}" class="{{ $active ? 'border-l-4 border-primary-fixed bg-primary-container text-on-primary-container' : 'text-surface-variant hover:bg-surface-variant/10 hover:text-primary-fixed-dim' }} flex items-center gap-3 rounded-xl px-4 py-3 transition-colors duration-200">
                    <span class="material-symbols-outlined" @if($active) style="font-variation-settings: 'FILL' 1;" @endif>{{ $item['icon'] }}</span>
                    <span class="font-medium">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-auto space-y-3 border-t border-surface-variant/20 pt-6">
            <a href="{{ route('reports') }}" class="flex items-center gap-3 px-4 py-2 text-surface-variant transition-colors hover:text-primary-fixed-dim">
                <span class="material-symbols-outlined">help</span>
                <span class="text-body-sm">Bantuan & Laporan</span>
            </a>
            <a href="{{ route('landing') }}" class="flex items-center gap-3 px-4 py-2 text-surface-variant transition-colors hover:text-primary-fixed-dim">
                <span class="material-symbols-outlined">logout</span>
                <span class="text-body-sm">Kembali ke Landing</span>
            </a>
        </div>
    </aside>

    <div class="app-sidebar-overlay lg:hidden" data-sidebar-overlay></div>

    <main class="app-main ml-64 min-h-screen">
        <header class="app-header sticky top-0 z-40 flex h-20 items-center justify-between border-b border-outline-variant bg-surface/90 px-container-padding backdrop-blur-md">
            <div class="app-header-left flex items-center gap-6">
                <button type="button" class="mobile-nav-trigger lg:hidden" data-sidebar-toggle aria-label="Buka menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <div>
                    <h2 class="text-h3 font-bold text-primary">{{ $pageTitle }}</h2>
                    <p class="text-body-sm text-on-surface-variant">{{ $pageSubtitle }}</p>
                </div>

                @if (!empty($headerTabs))
                    <div class="app-header-tabs hidden items-center gap-5 md:flex">
                        @foreach ($headerTabs as $tab)
                            @php($tabActive = request()->routeIs($tab['route']))
                            <a href="{{ route($tab['route']) }}" class="{{ $tabActive ? 'border-b-2 border-primary pb-1 font-bold text-primary' : 'font-medium text-on-surface-variant hover:text-primary' }} transition-colors">
                                {{ $tab['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="app-header-right flex items-center gap-6">
                @if (!empty($searchPlaceholder))
                    <div class="app-header-search relative w-full max-w-md">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                        <input type="text" placeholder="{{ $searchPlaceholder }}" class="w-full rounded-full border border-outline-variant bg-surface-container-low py-2.5 pl-11 pr-4 outline-none transition-all focus:border-transparent focus:ring-2 focus:ring-primary">
                    </div>
                @endif

                @if (!empty($primaryAction))
                    <a href="{{ route($primaryAction['route']) }}" class="app-header-cta inline-flex items-center gap-2 rounded-full bg-primary px-6 py-2.5 font-bold text-on-primary transition-opacity hover:opacity-90">
                        <span class="material-symbols-outlined text-[20px]">{{ $primaryAction['icon'] }}</span>
                        {{ $primaryAction['label'] }}
                    </a>
                @endif

                <div class="app-user-copy hidden border-l border-outline-variant pl-4 md:flex md:items-center md:gap-3">
                    <div class="text-right">
                        <p class="font-bold leading-none">{{ $owner['name'] }}</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $owner['role'] }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-primary-fixed bg-primary-container font-bold text-on-primary-container">
                        {{ $owner['initials'] }}
                    </div>
                </div>
            </div>
        </header>

        <div class="app-page p-container-padding">
            @if (session('success'))
                <div class="mb-6 rounded-[24px] border border-secondary-container bg-secondary-container/40 px-5 py-4 text-body-sm text-on-secondary-fixed-variant">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 rounded-[24px] border border-amber-200 bg-amber-50 px-5 py-4 text-body-sm text-amber-800">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
@endsection
