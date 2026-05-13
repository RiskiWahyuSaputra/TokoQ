@extends('layouts.tokoq.app', ['title' => $title])

@section('content')
<div class="mb-8 grid gap-card-gap md:grid-cols-3">
    @foreach ($inventory['stats'] as $stat)
        <div class="paper-card rounded-[28px] p-6">
            <p class="text-label-caps text-secondary">{{ $stat['label'] }}</p>
            <p class="mt-3 text-h2 font-bold text-primary">{{ $stat['value'] }}</p>
        </div>
    @endforeach
</div>

<div class="grid gap-card-gap xl:grid-cols-[1.1fr_0.9fr]">
    <section class="paper-card rounded-[32px] p-6">
        <div class="mb-6 flex flex-wrap items-center gap-3">
            <span class="text-label-caps text-on-surface-variant">Filter status</span>
            <span class="rounded-full border border-primary bg-primary/5 px-4 py-1.5 text-body-sm font-bold text-primary">Semua</span>
            <span class="rounded-full border border-outline-variant px-4 py-1.5 text-body-sm text-on-surface-variant">Kritis</span>
            <span class="rounded-full border border-outline-variant px-4 py-1.5 text-body-sm text-on-surface-variant">Menipis</span>
            <span class="rounded-full border border-outline-variant px-4 py-1.5 text-body-sm text-on-surface-variant">Aman</span>
        </div>

        <div class="table-responsive overflow-hidden rounded-[26px] border border-outline-variant">
            <table class="w-full min-w-[720px] border-collapse text-left">
                <thead class="bg-surface-container-low">
                    <tr>
                        <th class="px-6 py-4 text-label-caps text-on-surface-variant">Produk</th>
                        <th class="px-6 py-4 text-label-caps text-on-surface-variant">Kategori</th>
                        <th class="px-6 py-4 text-label-caps text-on-surface-variant">SKU</th>
                        <th class="px-6 py-4 text-label-caps text-on-surface-variant">Stok</th>
                        <th class="px-6 py-4 text-label-caps text-on-surface-variant">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($inventory['products'] as $product)
                        <tr class="border-t border-outline-variant/60">
                            <td class="px-6 py-4 font-bold text-on-surface">{{ $product['name'] }}</td>
                            <td class="px-6 py-4 text-on-surface-variant">{{ $product['category'] }}</td>
                            <td class="px-6 py-4 text-on-surface-variant">{{ $product['sku'] }}</td>
                            <td class="px-6 py-4 text-on-surface">{{ $product['stock'] }}</td>
                            <td class="px-6 py-4">
                                <span class="{{ $product['status'] === 'Aman' ? 'bg-secondary-container text-on-secondary-fixed-variant' : ($product['status'] === 'Menipis' ? 'bg-amber-100 text-amber-800' : 'bg-error-container text-on-error-container') }} rounded-full px-3 py-1 text-[11px] font-bold uppercase">
                                    {{ $product['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="space-y-4">
        <div class="paper-card rounded-[32px] p-6">
            <p class="text-label-caps text-secondary">Tambah produk baru</p>
            <form action="{{ route('inventory.products.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <input name="name" type="text" value="{{ old('name') }}" class="w-full rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Nama produk">
                <input name="category" type="text" value="{{ old('category') }}" class="w-full rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Kategori">
                <div class="grid grid-cols-3 gap-3">
                    <input name="unit" type="text" value="{{ old('unit', 'pcs') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Unit">
                    <input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Harga">
                    <input name="stock_quantity" type="number" min="0" step="0.01" value="{{ old('stock_quantity') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Stok">
                </div>
                <button type="submit" class="w-full rounded-2xl bg-primary py-3 font-bold text-on-primary transition hover:opacity-90">Simpan Produk</button>
            </form>
        </div>
        <div class="paper-card rounded-[32px] p-6">
            <h3 class="text-h3 text-primary">Rekomendasi restok</h3>
            <ul class="mt-4 space-y-3 text-body-sm text-on-surface-variant">
                @foreach ($inventory['recommendations'] as $recommendation)
                    <li>{{ $recommendation }}</li>
                @endforeach
            </ul>
        </div>
    </section>
</div>
@endsection
