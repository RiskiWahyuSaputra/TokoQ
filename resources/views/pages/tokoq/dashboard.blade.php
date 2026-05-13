@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="mb-8">
    <h2 class="mb-1 text-h2 text-primary">{{ $dashboard['greeting'] }}, {{ $owner['name'] }}</h2>
    <p class="text-on-surface-variant">Berikut ringkasan performa toko Anda hari ini.</p>
</div>

<div class="mb-section-margin grid grid-cols-12 gap-card-gap">
    <section class="digital-twin-gradient relative col-span-12 overflow-hidden rounded-[24px] border border-outline-variant bg-white p-8 shadow-md shadow-[#49592A]/10 lg:col-span-4">
        <div class="absolute -right-16 -top-16 h-32 w-32 rounded-full bg-primary-fixed/20 blur-3xl"></div>
        <div class="relative z-10 flex flex-col items-center text-center">
            <p class="mb-6 text-label-caps text-secondary">Digital Twin Score</p>
            <div class="relative mb-6 h-40 w-40">
                <svg class="h-full w-full -rotate-90 transform">
                    <circle cx="80" cy="80" r="70" fill="transparent" stroke="#edefdf" stroke-width="12"></circle>
                    <circle
                        cx="80"
                        cy="80"
                        r="70"
                        fill="transparent"
                        stroke="#576B33"
                        stroke-width="12"
                        stroke-linecap="round"
                        stroke-dasharray="440"
                        stroke-dashoffset="{{ 440 - (($dashboard['score'] / 100) * 440) }}"
                    ></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-[42px] font-extrabold text-primary">{{ $dashboard['score'] }}</span>
                    <span class="font-medium text-on-surface-variant">/ 100</span>
                </div>
            </div>
            <div class="mb-2 flex items-center gap-2 rounded-full bg-secondary-container px-4 py-2">
                <span class="material-symbols-outlined text-sm text-secondary" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                <span class="text-body-sm font-bold text-on-secondary-fixed-variant">{{ $dashboard['score_label'] }}</span>
            </div>
            <p class="max-w-[220px] text-body-sm text-on-surface-variant">{{ $dashboard['score_description'] }}</p>
        </div>
    </section>

    <section class="col-span-12 grid grid-cols-1 gap-card-gap md:grid-cols-2 lg:col-span-8">
        @foreach ($dashboard['metrics'] as $metric)
            <article class="{{ !empty($metric['accent_border']) ? 'border-t-4 border-t-tertiary' : '' }} rounded-[24px] border border-outline-variant bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                <div class="mb-4 flex items-start justify-between">
                    <div class="{{ $metric['icon_bg'] }} rounded-xl p-3">
                        <span class="material-symbols-outlined">{{ $metric['icon'] }}</span>
                    </div>
                    @if (!empty($metric['accent']))
                        <span class="{{ $metric['accent'] }} flex items-center gap-1 rounded-lg px-2 py-1 text-body-sm font-bold">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            {{ $metric['change'] }}
                        </span>
                    @endif
                </div>
                <p class="font-medium text-on-surface-variant">{{ $metric['label'] }}</p>
                <h3 class="{{ $metric['value_class'] ?? 'text-on-surface' }} text-h2 font-bold">{{ $metric['value'] }}</h3>
                @if (empty($metric['accent']))
                    <p class="mt-2 text-body-sm text-on-surface-variant">{{ $metric['change'] }}</p>
                @endif
            </article>
        @endforeach
    </section>
</div>

<div class="mb-section-margin grid grid-cols-12 gap-card-gap">
    <section class="col-span-12 rounded-[24px] border border-outline-variant bg-white p-8 shadow-sm xl:col-span-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h3 class="text-h3 font-bold text-on-surface">Omzet 7 Hari Terakhir</h3>
                <p class="text-body-sm text-on-surface-variant">Visualisasi performa mingguan</p>
            </div>
            <a href="{{ route('sales') }}" class="flex items-center gap-2 font-medium text-on-surface-variant transition-colors hover:text-primary">
                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                Minggu Ini
            </a>
        </div>

        <div class="flex h-64 items-end gap-4">
            @foreach ($dashboard['daily_bars'] as $bar)
                <div class="group flex flex-1 flex-col items-center gap-2">
                    <div class="w-full rounded-t-xl {{ $loop->iteration === 6 ? 'bg-primary-container' : 'bg-surface-container-high group-hover:bg-primary-container' }} transition-colors" style="height: {{ max(16, $bar['height']) }}%;"></div>
                    <span class="{{ $loop->iteration === 6 ? 'font-bold text-primary' : 'text-on-surface-variant' }} text-label-caps">{{ $bar['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="col-span-12 flex flex-col gap-card-gap xl:col-span-4">
        <div class="relative overflow-hidden rounded-[24px] border border-outline-variant border-t-4 border-t-tertiary bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-tertiary-container text-on-tertiary-container">
                    <span class="material-symbols-outlined text-[20px]">psychology</span>
                </div>
                <h3 class="text-body-lg font-bold text-primary">AI Business Insights</h3>
            </div>
            <ul class="space-y-4">
                @foreach ($dashboard['insights'] as $insight)
                    <li class="flex items-start gap-4 rounded-2xl border border-outline-variant/30 bg-surface-container-low p-4">
                        <span class="material-symbols-outlined mt-1 text-[20px] {{ $insight['icon_class'] }}">{{ $insight['icon'] }}</span>
                        <p class="text-body-md text-on-surface">{{ $insight['description'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>

        <a href="{{ route('forecast') }}" class="group relative cursor-pointer rounded-[24px] bg-primary-container p-6 shadow-lg transition-transform hover:scale-[1.02]">
            <div class="relative z-10">
                <h4 class="mb-2 font-bold text-on-primary-container">Butuh Bantuan?</h4>
                <p class="mb-4 text-body-sm text-on-primary-container/80">Konsultasikan strategi penjualan dan restok Anda dengan insight AI TokoQ.</p>
                <span class="flex items-center gap-2 font-bold text-on-primary-container">
                    Lihat Prediksi
                    <span class="material-symbols-outlined">arrow_forward</span>
                </span>
            </div>
            <span class="material-symbols-outlined pointer-events-none absolute -bottom-4 -right-4 text-[120px] text-white/5">smart_toy</span>
        </a>
    </section>
</div>

<div class="grid grid-cols-12 gap-card-gap">
    <section class="col-span-12 overflow-hidden rounded-[24px] border border-outline-variant bg-white shadow-sm lg:col-span-8">
        <div class="flex items-center justify-between border-b border-outline-variant p-8">
            <div>
                <h3 class="text-h3 font-bold text-on-surface">Daftar Stok Kritis</h3>
                <p class="text-body-sm text-on-surface-variant">Barang-barang yang hampir habis</p>
            </div>
            <a href="{{ route('inventory') }}" class="flex items-center gap-2 rounded-full bg-primary px-6 py-2.5 font-bold text-on-primary transition-transform active:scale-95">
                <span class="material-symbols-outlined text-[20px]">list_alt</span>
                Buka Inventori
            </a>
        </div>

        <div class="table-responsive overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-surface-container font-label-caps uppercase text-on-surface-variant">
                    <tr>
                        <th class="px-8 py-4">Produk</th>
                        <th class="px-8 py-4">Sisa Stok</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($dashboard['critical_stocks'] as $product)
                        <tr>
                            <td class="px-8 py-4 font-bold text-on-surface">{{ $product['name'] }}</td>
                            <td class="px-8 py-4 text-on-surface-variant">{{ $product['stock'] }}</td>
                            <td class="px-8 py-4">
                                <span class="{{ $product['status'] === 'Kritis' ? 'bg-error-container text-on-error-container' : 'bg-[#ffdad6]/50 text-error' }} rounded-full px-3 py-1 text-body-sm font-bold">
                                    {{ $product['status'] }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <a href="{{ route('inventory') }}" class="font-bold text-primary hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-6 text-body-sm text-on-surface-variant">Belum ada produk dengan status kritis atau menipis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="col-span-12 rounded-[24px] border border-outline-variant bg-white p-8 shadow-sm lg:col-span-4">
        <h3 class="mb-6 text-h3 font-bold text-on-surface">Produk Terlaris</h3>
        <div class="space-y-6">
            @forelse ($dashboard['best_sellers'] as $product)
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-surface-container font-bold text-primary">{{ $product['rank'] }}</div>
                    <div class="flex-1">
                        <p class="font-bold text-on-surface">{{ $product['name'] }}</p>
                        <p class="text-body-sm text-on-surface-variant">{{ $product['quantity'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-primary">{{ $product['revenue'] }}</p>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl bg-surface-container-low p-4 text-body-sm text-on-surface-variant">
                    Belum ada data penjualan yang cukup untuk menentukan produk terlaris.
                </div>
            @endforelse

            <a href="{{ route('sales') }}" class="mt-2 block w-full rounded-xl border border-primary/20 py-3 text-center font-bold text-primary transition-colors hover:bg-primary/5">
                Lihat Semua Produk
            </a>
        </div>
    </section>
</div>

<footer class="app-footer flex items-center justify-between px-container-padding pb-12 pt-8 opacity-50">
    <p class="text-body-sm">© 2024 TokoQ Digital Twin Management. All rights reserved.</p>
    <div class="flex gap-6 text-body-sm">
        <a href="{{ route('reports') }}" class="underline hover:text-primary">Syarat & Ketentuan</a>
        <a href="{{ route('reports') }}" class="underline hover:text-primary">Kebijakan Privasi</a>
    </div>
</footer>

<a href="{{ route('pos') }}" class="app-fab fixed bottom-10 right-10 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-primary text-on-primary shadow-2xl transition-all hover:scale-110 active:scale-95">
    <span class="material-symbols-outlined text-[32px]">add</span>
</a>
@endsection
