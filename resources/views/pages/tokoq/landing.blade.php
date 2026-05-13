@extends('layouts.tokoq.landing', ['title' => $title])

@section('content')
<section class="landing-section landing-hero matcha-gradient overflow-hidden px-container-padding pb-28 pt-20">
    <div class="mx-auto grid max-w-7xl items-center gap-16 md:grid-cols-2">
        <div class="space-y-8">
            <div class="inline-flex items-center gap-2 rounded-full bg-secondary-container px-4 py-1.5 text-label-caps text-on-secondary-fixed-variant">
                <span class="material-symbols-outlined text-[16px]">verified</span>
                {{ strtoupper($landing['hero']['eyebrow']) }}
            </div>

            <div class="space-y-5">
                <h1 class="max-w-2xl text-h1 text-primary md:text-[56px]">{{ $landing['hero']['title'] }}</h1>
                <p class="max-w-xl text-body-lg text-on-surface-variant">{{ $landing['hero']['description'] }}</p>
            </div>

            <div class="flex flex-wrap gap-4">
                <a href="{{ $isStoreReady ? route('dashboard') : route('onboarding') }}" class="inline-flex items-center gap-2 rounded-xl bg-primary px-8 py-4 font-bold text-on-primary shadow-lg shadow-primary/20 transition hover:bg-primary/90">
                    {{ $landing['hero']['primary_cta'] }}
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
                <a href="{{ $isStoreReady ? route('pos') : route('onboarding') }}" class="rounded-xl border border-outline-variant bg-surface-container-high px-8 py-4 font-bold text-primary transition hover:bg-surface-variant">
                    {{ $landing['hero']['secondary_cta'] }}
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($landing['stats'] as $stat)
                    <div class="paper-card rounded-3xl p-5">
                        <p class="text-h3 font-bold text-primary">{{ $stat['value'] }}</p>
                        <p class="mt-2 text-body-sm text-on-surface-variant">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative">
            <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-primary-fixed/40 blur-3xl"></div>
            <div class="paper-elevation relative overflow-hidden rounded-[28px] border border-outline-variant bg-white p-4 rotate-1">
                <img src="{{ $landing['hero']['preview_image'] }}" alt="Preview dashboard TokoQ" class="w-full rounded-[22px] border border-outline-variant">
                <div class="mt-4 grid gap-3 md:grid-cols-2">
                    <div class="rounded-2xl bg-surface-container-low p-4">
                        <p class="text-label-caps text-secondary">AI Insight</p>
                        <p class="mt-2 text-body-sm text-on-surface-variant">Stok gula pasir akan habis dalam 2 hari bila tren penjualan tetap.</p>
                    </div>
                    <div class="rounded-2xl bg-primary-container p-4 text-on-primary-container">
                        <p class="text-label-caps">Kasir Aktif</p>
                        <p class="mt-2 text-h3 font-bold">318 transaksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="masalah" class="landing-section bg-surface-dim px-container-padding py-24">
    <div class="mx-auto max-w-7xl">
        <div class="mb-14 max-w-2xl space-y-3">
            <p class="text-label-caps text-secondary">Masalah yang sering terjadi</p>
            <h2 class="text-h2 text-primary">Template UI ini sekarang sudah masuk ke alur Laravel dan siap dipakai untuk kebutuhan toko.</h2>
            <p class="text-on-surface-variant">Saya pertahankan nuansa matcha-premium dari template, lalu saya ubah jadi struktur yang lebih maintainable lewat Blade dan route bernama.</p>
        </div>

        <div class="grid gap-8 md:grid-cols-3">
            @foreach ($landing['pain_points'] as $point)
                <div class="paper-card rounded-[28px] p-8 transition duration-300 hover:-translate-y-1">
                    <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-error-container text-on-error-container">
                        <span class="material-symbols-outlined text-[30px]">{{ $point['icon'] }}</span>
                    </div>
                    <h3 class="text-h3">{{ $point['title'] }}</h3>
                    <p class="mt-3 text-on-surface-variant">{{ $point['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="fitur" class="landing-section px-container-padding py-24">
    <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="paper-card rounded-[32px] p-8 md:p-10">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-label-caps text-secondary">Ekosistem Halaman</p>
                    <h2 class="text-h2 text-primary">Semua template sudah punya route sendiri</h2>
                </div>
                <a href="{{ $isStoreReady ? route('dashboard') : route('onboarding') }}" class="rounded-full bg-primary px-5 py-3 text-body-sm font-bold text-on-primary">
                    {{ $isStoreReady ? 'Buka Dashboard' : 'Mulai Onboarding' }}
                </a>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($landing['solutions'] as $solution)
                    <div class="rounded-3xl bg-surface-container-low p-6">
                        <h3 class="text-body-lg font-bold text-primary">{{ $solution['title'] }}</h3>
                        <p class="mt-3 text-body-sm text-on-surface-variant">{{ $solution['description'] }}</p>
                    </div>
                @endforeach
                <div class="rounded-3xl border border-dashed border-outline-variant p-6">
                    <p class="text-label-caps text-secondary">Route aktif</p>
                    <div class="mt-3 flex flex-wrap gap-2 text-body-sm">
                        <span class="rounded-full bg-secondary-container px-3 py-1 text-on-secondary-fixed-variant">/dashboard</span>
                        <span class="rounded-full bg-secondary-container px-3 py-1 text-on-secondary-fixed-variant">/kasir</span>
                        <span class="rounded-full bg-secondary-container px-3 py-1 text-on-secondary-fixed-variant">/laporan</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="paper-card rounded-[32px] p-8">
            <p class="text-label-caps text-secondary">Preview cepat</p>
            <div class="mt-5 space-y-4">
                <a href="{{ route('onboarding') }}" class="flex items-center justify-between rounded-2xl bg-surface-container-low p-5 transition hover:bg-surface-container">
                    <div>
                        <p class="font-bold text-primary">Onboarding</p>
                        <p class="text-body-sm text-on-surface-variant">Masuk ke proses setup awal toko.</p>
                    </div>
                    <span class="material-symbols-outlined text-primary">north_east</span>
                </a>
                <a href="{{ $isStoreReady ? route('inventory') : route('onboarding') }}" class="flex items-center justify-between rounded-2xl bg-surface-container-low p-5 transition hover:bg-surface-container">
                    <div>
                        <p class="font-bold text-primary">Inventori</p>
                        <p class="text-body-sm text-on-surface-variant">Pantau stok kritis dan perputaran barang.</p>
                    </div>
                    <span class="material-symbols-outlined text-primary">north_east</span>
                </a>
                <a href="{{ $isStoreReady ? route('forecast') : route('onboarding') }}" class="flex items-center justify-between rounded-2xl bg-surface-container-low p-5 transition hover:bg-surface-container">
                    <div>
                        <p class="font-bold text-primary">Prediksi AI</p>
                        <p class="text-body-sm text-on-surface-variant">Lihat insight omzet dan rekomendasi tindakan.</p>
                    </div>
                    <span class="material-symbols-outlined text-primary">north_east</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section id="alur" class="landing-section bg-primary px-container-padding py-24 text-on-primary">
    <div class="mx-auto max-w-7xl">
        <div class="mb-12 max-w-2xl">
            <p class="text-label-caps text-primary-fixed">Alur implementasi</p>
            <h2 class="mt-3 text-h2">Dari folder `public/template` ke MVC Laravel yang rapi</h2>
        </div>

        <div class="grid gap-6 md:grid-cols-4">
            <div class="rounded-[28px] bg-white/10 p-6">
                <p class="text-label-caps text-primary-fixed">01</p>
                <h3 class="mt-3 text-body-lg font-bold">Views</h3>
                <p class="mt-2 text-body-sm text-primary-fixed">Setiap UI dipetakan ke Blade page dan layout bersama.</p>
            </div>
            <div class="rounded-[28px] bg-white/10 p-6">
                <p class="text-label-caps text-primary-fixed">02</p>
                <h3 class="mt-3 text-body-lg font-bold">Model</h3>
                <p class="mt-2 text-body-sm text-primary-fixed">Data demo dipusatkan dalam satu model agar mudah diubah.</p>
            </div>
            <div class="rounded-[28px] bg-white/10 p-6">
                <p class="text-label-caps text-primary-fixed">03</p>
                <h3 class="mt-3 text-body-lg font-bold">Controller</h3>
                <p class="mt-2 text-body-sm text-primary-fixed">Setiap halaman punya method sendiri supaya future-ready.</p>
            </div>
            <div class="rounded-[28px] bg-white/10 p-6">
                <p class="text-label-caps text-primary-fixed">04</p>
                <h3 class="mt-3 text-body-lg font-bold">Routes</h3>
                <p class="mt-2 text-body-sm text-primary-fixed">Navigasi sudah hidup dan saling terhubung antar template.</p>
            </div>
        </div>
    </div>
</section>
@endsection
