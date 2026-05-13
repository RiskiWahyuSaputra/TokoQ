@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="flex flex-col gap-gutter xl:flex-row">
    <section class="flex-1">
        <div class="mb-8 flex flex-wrap gap-3">
            <span class="rounded-full bg-primary px-6 py-2 font-bold text-on-primary">Semua</span>
            @foreach ($pos['categories'] as $category)
                <span class="rounded-full border border-outline-variant bg-white px-6 py-2 font-bold text-on-surface-variant">
                    {{ $category['label'] }}
                </span>
            @endforeach
        </div>

        @if ($errors->has('items'))
            <div class="mb-6 rounded-[24px] border border-error-container bg-error-container/40 px-5 py-4 text-body-sm text-on-error-container">
                {{ $errors->first('items') }}
            </div>
        @endif

        <form action="{{ route('pos.checkout') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-4 md:grid-cols-[1fr_220px]">
                <input name="customer_name" type="text" value="{{ old('customer_name') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Nama pelanggan opsional">
                <input name="discount" type="number" min="0" step="0.01" value="{{ old('discount', 0) }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Diskon">
            </div>

            <div class="grid gap-card-gap md:grid-cols-2 2xl:grid-cols-4">
                @foreach ($pos['products'] as $product)
                    <article class="paper-card overflow-hidden rounded-[28px]">
                        <div class="flex h-40 items-center justify-center bg-surface-container-low">
                            <span class="material-symbols-outlined text-[54px] text-primary">{{ str_contains($product['category'], 'Minuman') ? 'local_drink' : 'shopping_basket' }}</span>
                        </div>
                        <div class="p-5">
                            <div class="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-body-sm text-on-surface-variant">{{ $product['category'] }}</p>
                                    <h3 class="mt-1 text-body-lg font-bold text-on-surface">{{ $product['name'] }}</h3>
                                </div>
                                <span class="rounded-full bg-secondary-container px-3 py-1 text-[10px] font-bold uppercase text-on-secondary-fixed-variant">{{ $product['badge'] }}</span>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-h3 font-bold text-primary">{{ $product['price'] }}</p>
                                    <p class="text-body-sm text-on-surface-variant">Stok {{ $product['stock'] }}</p>
                                </div>
                                <input name="items[{{ $product['id'] }}]" type="number" min="0" step="0.01" max="{{ $product['stock_raw'] }}" value="{{ old('items.'.$product['id'], 0) }}" class="w-full rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Qty beli">
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <button type="submit" class="rounded-2xl bg-primary px-7 py-4 font-bold text-on-primary transition hover:opacity-90">
                Simpan Transaksi
            </button>
        </form>
    </section>

    <aside class="app-cart w-full xl:w-[360px]">
        <div class="paper-card app-sticky-panel sticky top-28 rounded-[32px] p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <p class="text-label-caps text-secondary">Transaksi terbaru</p>
                    <h3 class="mt-2 text-h3 text-primary">{{ $pos['recent_sale']['invoice'] ?? 'Belum ada transaksi' }}</h3>
                </div>
                @if ($pos['recent_sale'])
                    <span class="rounded-full bg-secondary-container px-3 py-1 text-body-sm font-bold text-on-secondary-fixed-variant">{{ $pos['recent_sale']['items']->count() }} item</span>
                @endif
            </div>

            @if ($pos['recent_sale'])
                @if ($pos['recent_sale']['customer_name'])
                    <p class="mb-4 text-body-sm text-on-surface-variant">Pelanggan: {{ $pos['recent_sale']['customer_name'] }}</p>
                @endif

                <div class="space-y-4">
                    @foreach ($pos['recent_sale']['items'] as $item)
                        <div class="rounded-3xl bg-surface-container-low p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-bold text-on-surface">{{ $item['name'] }}</p>
                                    <p class="text-body-sm text-on-surface-variant">Qty {{ $item['qty'] }}</p>
                                </div>
                                <p class="font-bold text-primary">{{ $item['total'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-3 border-t border-outline-variant pt-6 text-body-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant">Subtotal</span>
                        <span class="font-bold">{{ $pos['recent_sale']['summary']['subtotal'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-on-surface-variant">Diskon</span>
                        <span class="font-bold text-primary">{{ $pos['recent_sale']['summary']['discount'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-body-lg">
                        <span class="font-bold text-primary">Total</span>
                        <span class="font-extrabold text-primary">{{ $pos['recent_sale']['summary']['total'] }}</span>
                    </div>
                </div>
            @else
                <div class="rounded-3xl bg-surface-container-low p-5 text-body-sm text-on-surface-variant">
                    Setelah Anda menyimpan transaksi pertama, ringkasannya akan muncul di panel ini.
                </div>
            @endif
        </div>
    </aside>
</div>
@endsection
