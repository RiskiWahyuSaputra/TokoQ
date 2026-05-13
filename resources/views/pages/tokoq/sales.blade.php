@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="mb-8 grid gap-card-gap md:grid-cols-2 xl:grid-cols-4">
    @foreach ($sales['summaries'] as $summary)
        <div class="paper-card rounded-[28px] p-6">
            <p class="text-label-caps text-secondary">{{ $summary['label'] }}</p>
            <p class="mt-3 text-h3 font-bold text-primary">{{ $summary['value'] }}</p>
            <p class="mt-3 text-body-sm text-on-surface-variant">{{ $summary['meta'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid gap-card-gap xl:grid-cols-[1.15fr_0.85fr]">
    <section class="paper-card rounded-[32px] p-8">
        <div class="mb-6">
            <p class="text-label-caps text-secondary">Grafik ringkas</p>
            <h3 class="mt-2 text-h3 text-primary">Performa penjualan 7 hari terakhir</h3>
        </div>

        <div class="flex h-72 items-end gap-4 rounded-[28px] bg-surface-container-low p-6">
            @foreach ($sales['daily_bars'] as $day)
                <div class="flex flex-1 flex-col items-center justify-end gap-3">
                    <div class="w-full rounded-t-[18px] bg-primary" style="height: {{ max(10, $day['height']) }}%;"></div>
                    <span class="text-[11px] font-bold uppercase text-on-surface-variant">{{ $day['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="space-y-4">
        <div class="paper-card rounded-[32px] p-6">
            <p class="text-label-caps text-secondary">Kategori teratas</p>
            <div class="mt-5 space-y-4">
                @foreach ($sales['top_categories'] as $category)
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <span class="font-medium text-on-surface">{{ $category['label'] }}</span>
                            <span class="font-bold text-primary">{{ $category['value'] }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-surface-container-low">
                            <div class="h-3 rounded-full bg-primary" style="width: {{ $category['value'] }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="paper-card rounded-[32px] p-6">
            <p class="text-label-caps text-secondary">Transaksi terbaru</p>
            <div class="mt-4 space-y-3">
                @foreach ($sales['transactions'] as $transaction)
                    <div class="rounded-3xl bg-surface-container-low p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-bold text-primary">{{ $transaction['invoice'] }}</p>
                                <p class="text-body-sm text-on-surface-variant">{{ $transaction['time'] }}</p>
                            </div>
                            <p class="font-bold text-on-surface">{{ $transaction['total'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
