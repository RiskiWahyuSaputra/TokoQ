@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="grid gap-card-gap xl:grid-cols-[1.15fr_0.85fr]">
    <section class="ai-card-gradient rounded-[32px] border border-outline-variant p-8 shadow-md shadow-[#49592A]/10">
        <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-label-caps text-secondary">Estimasi pendapatan</p>
                <h3 class="mt-2 text-h2 text-on-surface">Prediksi omzet besok</h3>
            </div>
            <span class="rounded-full bg-primary-container px-4 py-2 text-body-sm font-bold text-on-primary-container">
                {{ $forecast['prediction']['confidence'] }} confidence
            </span>
        </div>

        <div class="mb-8 flex items-end gap-4">
            <span class="text-[54px] font-extrabold leading-none text-primary">{{ $forecast['prediction']['value'] }}</span>
            <span class="pb-2 text-body-lg text-secondary">{{ $forecast['prediction']['delta'] }} vs kemarin</span>
        </div>

        <div class="rounded-[28px] bg-surface-container-low p-6">
            <div class="flex h-64 items-end gap-3">
                @foreach ($forecast['bars'] as $bar)
                    <div class="flex flex-1 items-end">
                        <div class="w-full rounded-t-[18px] bg-primary/80" style="height: {{ max(10, $bar) }}%;"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex justify-between text-[11px] font-bold uppercase text-on-surface-variant">
                <span>Sen</span>
                <span>Sel</span>
                <span>Rab</span>
                <span>Kam</span>
                <span>Jum</span>
                <span>Sab</span>
                <span class="text-primary">Min</span>
                <span>Sen</span>
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div class="paper-card rounded-[32px] p-6">
            <p class="text-label-caps text-secondary">Faktor pendorong</p>
            <div class="mt-4 space-y-3">
                @foreach ($forecast['drivers'] as $driver)
                    <div class="rounded-3xl bg-surface-container-low p-4">
                        <p class="font-bold text-primary">{{ $driver['title'] }}</p>
                        <p class="mt-2 text-body-sm text-on-surface-variant">{{ $driver['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="paper-card rounded-[32px] p-6">
            <p class="text-label-caps text-secondary">Tindakan yang disarankan</p>
            <ul class="mt-4 space-y-3 text-body-sm text-on-surface-variant">
                @foreach ($forecast['actions'] as $action)
                    <li class="flex gap-3">
                        <span class="material-symbols-outlined text-primary">task_alt</span>
                        <span>{{ $action }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</div>
@endsection
