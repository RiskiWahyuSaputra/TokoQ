@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="grid grid-cols-12 gap-gutter">
    <div class="col-span-12 space-y-gutter lg:col-span-8">
        <section class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-8 shadow-sm">
            <div class="mb-8 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h3 class="text-h3 text-on-surface">Konfigurasi Laporan</h3>
                    <p class="text-body-sm text-on-surface-variant">Pilih jenis data dan periode yang ingin dianalisis</p>
                </div>
                <div class="table-responsive flex rounded-xl border border-outline-variant bg-surface-container p-1">
                    <button class="rounded-lg bg-primary px-4 py-2 text-body-sm font-bold text-on-primary transition-all">Harian</button>
                    <button class="px-4 py-2 text-body-sm text-on-surface-variant transition-colors hover:text-primary">Mingguan</button>
                    <button class="px-4 py-2 text-body-sm text-on-surface-variant transition-colors hover:text-primary">Bulanan</button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-4">
                @foreach ($reports['types'] as $type)
                    <div class="{{ $type['active'] ? 'border-2 border-primary-container bg-surface-container-low' : 'border border-outline-variant bg-surface-container-lowest' }} flex cursor-pointer flex-col items-center rounded-xl p-4 text-center transition-all hover:border-primary">
                        <span class="material-symbols-outlined mb-2 {{ $type['active'] ? 'text-primary' : 'text-on-surface-variant' }}" @if($type['active']) style="font-variation-settings: 'FILL' 1;" @endif>{{ $type['icon'] }}</span>
                        <span class="{{ $type['active'] ? 'font-bold text-primary' : 'font-medium text-on-surface-variant' }} text-body-sm">{{ $type['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col items-end gap-4 md:flex-row">
                <div class="w-full flex-1">
                    <label class="mb-2 block text-label-caps text-secondary">Rentang Tanggal</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-sm text-outline">calendar_month</span>
                        <input type="text" value="{{ $reports['period']['label'] }}" class="w-full rounded-xl border border-outline-variant bg-white py-3 pl-10 pr-4 text-body-sm outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                <button class="flex items-center gap-2 rounded-xl bg-primary-container px-8 py-3 font-bold text-on-primary-container transition-all hover:opacity-90 active:scale-95">
                    <span class="material-symbols-outlined">refresh</span>
                    Terapkan
                </button>
            </div>
        </section>

        <section class="grid gap-card-gap md:grid-cols-3">
            @foreach ($reports['summary_cards'] as $card)
                <article class="relative overflow-hidden rounded-2xl border border-outline-variant border-t-4 border-t-primary-container bg-surface-container-lowest p-6">
                    <div class="absolute -bottom-4 -right-4 opacity-5">
                        <span class="material-symbols-outlined text-8xl">{{ $card['icon'] }}</span>
                    </div>
                    <p class="mb-2 text-label-caps text-on-surface-variant">{{ strtoupper($card['label']) }}</p>
                    <h4 class="text-h3 font-bold text-on-surface">{{ $card['value'] }}</h4>
                    <div class="mt-4 flex items-center gap-1 text-primary">
                        <span class="material-symbols-outlined text-sm">{{ $loop->first ? 'trending_up' : ($loop->last ? 'verified' : 'trending_up') }}</span>
                        <span class="text-body-sm font-bold">{{ $card['meta'] }}</span>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="rounded-2xl border border-outline-variant bg-surface-container-high p-6">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <div>
                    <h4 class="font-bold text-on-surface">Ekspor Dokumen</h4>
                    <p class="text-body-sm text-on-surface-variant">Pilih format untuk mengunduh laporan ini</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach ($reports['exports'] as $export)
                        <button class="{{ $export['button_class'] ?? 'border border-outline-variant bg-white text-secondary hover:bg-surface-container-lowest' }} flex items-center gap-2 rounded-xl px-5 py-2.5 font-bold transition-colors active:scale-95">
                            <span class="material-symbols-outlined {{ $export['class'] ?? '' }}">{{ $export['icon'] }}</span>
                            {{ $export['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-outline-variant bg-surface-container-lowest p-6">
            <p class="text-label-caps text-secondary">Sorotan otomatis</p>
            <ul class="mt-4 space-y-3 text-on-surface-variant">
                @foreach ($reports['highlights'] as $highlight)
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-primary">check_circle</span>
                        <span>{{ $highlight }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        <div class="flex animate-pulse items-center justify-center gap-3 py-6 text-on-surface-variant">
            <div class="h-6 w-6 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
            <span class="font-medium italic">Sedang menyiapkan laporan...</span>
        </div>
    </div>

    <aside class="col-span-12 lg:col-span-4">
        <div class="app-sticky-panel sticky top-28">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="font-bold text-on-surface">Pratinjau Dokumen</h3>
                <span class="rounded-full bg-secondary-container px-3 py-1 text-label-caps uppercase text-on-secondary-container">Draft</span>
            </div>

            <div class="a4-preview flex flex-col overflow-hidden rounded-lg border border-outline-variant bg-white p-8">
                <div class="mb-6 flex items-start justify-between border-b-2 border-primary-container pb-6">
                    <div>
                        <h1 class="mb-1 text-h3 text-primary">TokoQ</h1>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Laporan Penjualan Mingguan</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-on-surface">PERIODE</p>
                        <p class="text-[10px] text-on-surface-variant">{{ $reports['period']['label'] }}</p>
                    </div>
                </div>

                <div class="flex-1 space-y-6">
                    <div class="rounded-lg bg-surface-container p-4">
                        <h5 class="mb-3 text-[12px] font-bold uppercase tracking-tighter text-on-surface">Ringkasan Eksekutif</h5>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-on-surface-variant">Omzet Bruto</p>
                                <p class="text-[12px] font-bold text-on-surface">{{ $reports['document']['gross_revenue'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-on-surface-variant">Diskon/Promo</p>
                                <p class="text-[12px] font-bold text-error">{{ $reports['document']['discount_total'] }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-on-surface-variant">Pajak (11%)</p>
                                <p class="text-[12px] font-bold text-on-surface">{{ $reports['document']['tax_estimate'] }}</p>
                            </div>
                            <div class="mt-1 border-t border-outline-variant pt-1">
                                <p class="text-[10px] font-bold text-primary">OMZET NETO</p>
                                <p class="text-[12px] font-bold text-primary">{{ $reports['document']['net_revenue'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h5 class="mb-3 text-[12px] font-bold uppercase tracking-tighter text-on-surface">Produk Terlaris</h5>
                        <table class="w-full text-[10px] leading-relaxed">
                            <thead>
                                <tr class="border-b border-outline-variant bg-surface-container-high">
                                    <th class="p-2 text-left">NAMA BARANG</th>
                                    <th class="p-2 text-right">QTY</th>
                                    <th class="p-2 text-right">SUBTOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports['document']['top_products'] as $product)
                                    <tr class="border-b border-outline-variant/30">
                                        <td class="p-2">{{ $product['name'] }}</td>
                                        <td class="p-2 text-right">{{ $product['quantity_value'] }}</td>
                                        <td class="p-2 text-right">{{ $product['revenue'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-2 text-on-surface-variant">Belum ada data penjualan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex items-end justify-between border-t border-outline-variant pt-4 opacity-50">
                    <div>
                        <p class="text-[8px]">Dicetak secara otomatis oleh sistem TokoQ</p>
                        <p class="text-[8px]">Waktu: {{ $reports['period']['generated_at'] }}</p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded border border-outline-variant text-[8px] font-bold text-on-surface-variant">
                        {{ $reports['document']['verification_code'] }}
                    </div>
                </div>
            </div>

            <p class="mt-4 text-center text-body-sm italic text-on-surface-variant">
                "Data laporan ini dihitung berdasarkan transaksi kasir yang telah tervalidasi."
            </p>
        </div>
    </aside>
</div>
@endsection
