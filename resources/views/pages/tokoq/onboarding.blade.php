@extends('layouts.tokoq.base', ['title' => $title, 'bodyClass' => 'onboarding-shell min-h-screen flex items-center justify-center p-6 sm:p-container-padding'])

@section('body')
<main class="onboarding-card glass-paper flex w-full max-w-6xl flex-col overflow-hidden rounded-[30px] border border-outline-variant md:flex-row">
    <aside class="onboarding-sidebar flex w-full flex-col gap-10 bg-inverse-surface p-8 text-on-primary-fixed md:w-80">
        <div>
            <h1 class="text-h3 text-primary-fixed">TokoQ</h1>
            <p class="mt-2 text-body-sm text-surface-variant">Selesaikan setup toko sekali, lalu dashboard dan kasir akan otomatis aktif.</p>
        </div>

        <nav class="onboarding-steps flex flex-col gap-8">
            @foreach ([
                ['label' => 'Profil Toko', 'state' => 'active'],
                ['label' => 'Produk Awal', 'state' => 'active'],
                ['label' => 'Seed Data Penjualan', 'state' => 'active'],
                ['label' => 'Dashboard Siap', 'state' => 'pending'],
            ] as $index => $step)
                <div class="flex items-start gap-4 {{ $step['state'] === 'pending' ? 'opacity-40' : '' }}">
                    <div class="{{ $step['state'] === 'active' ? 'border-2 border-primary-fixed text-primary-fixed' : 'border-2 border-outline text-outline' }} flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-bold">
                        {{ $index + 1 }}
                    </div>
                    <div>
                        <span class="text-label-caps text-primary-fixed-dim">Langkah {{ $index + 1 }}</span>
                        <p class="text-body-md">{{ $step['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="mt-auto rounded-[24px] bg-white/10 p-5 text-body-sm text-surface-variant">
            Setelah form ini disimpan, route seperti `/dashboard`, `/kasir`, dan `/inventaris` baru bisa diakses.
        </div>
    </aside>

    <section class="onboarding-canvas flex-1 bg-white p-8 md:p-12">
        @if (session('warning'))
            <div class="mb-6 rounded-[24px] border border-amber-200 bg-amber-50 px-5 py-4 text-body-sm text-amber-800">
                {{ session('warning') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-[24px] border border-error-container bg-error-container/40 px-5 py-4 text-body-sm text-on-error-container">
                Periksa kembali form onboarding. Beberapa field masih belum valid.
            </div>
        @endif

        <form action="{{ route('onboarding.store') }}" method="POST" class="space-y-10">
            @csrf

            <div>
                <h2 class="text-h2 text-primary">Aktifkan toko Anda</h2>
                <p class="mt-2 text-on-surface-variant">Isi profil toko dan produk awal. Sistem akan membuat data dasar inventori dan contoh transaksi.</p>
            </div>

            <section class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="store_name">Nama toko</label>
                    <input id="store_name" name="store_name" type="text" value="{{ old('store_name', $draftStore?->name) }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Contoh: TokoQ Grosir Harian">
                </div>
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="owner_name">Nama pemilik</label>
                    <input id="owner_name" name="owner_name" type="text" value="{{ old('owner_name', $draftStore?->owner_name) }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Contoh: Bu Sari">
                </div>
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="owner_role">Peran</label>
                    <input id="owner_role" name="owner_role" type="text" value="{{ old('owner_role', $draftStore?->owner_role ?: 'Pemilik Toko') }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="business_type">Jenis usaha</label>
                    <input id="business_type" name="business_type" type="text" value="{{ old('business_type', $draftStore?->business_type ?: 'Toko Kelontong') }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="phone">Telepon</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $draftStore?->phone) }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="mb-2 block text-body-sm font-bold text-on-surface" for="address">Alamat singkat</label>
                    <input id="address" name="address" type="text" value="{{ old('address', $draftStore?->address) }}" class="w-full rounded-2xl border border-outline-variant bg-surface-container-low px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Jl. Contoh No. 10">
                </div>
            </section>

            <section>
                <div class="mb-5">
                    <h3 class="text-h3 text-primary">Produk awal</h3>
                    <p class="mt-2 text-body-sm text-on-surface-variant">Minimal isi satu produk. Baris ini akan dipakai untuk membangun inventori pertama Anda.</p>
                </div>

                <div class="space-y-4">
                    @foreach ($defaultProducts as $index => $product)
                        <div class="rounded-[26px] border border-outline-variant bg-surface-container-low p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-container text-on-primary-container">
                                    <span class="material-symbols-outlined">inventory_2</span>
                                </div>
                                <div>
                                    <p class="font-bold text-primary">Produk {{ $index + 1 }}</p>
                                    <p class="text-body-sm text-on-surface-variant">Data awal inventori</p>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-4">
                                <input name="products[{{ $index }}][name]" type="text" value="{{ old("products.$index.name", $product['name'] ?? '') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Nama produk">
                                <input name="products[{{ $index }}][category]" type="text" value="{{ old("products.$index.category", $product['category'] ?? '') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Kategori">
                                <input name="products[{{ $index }}][price]" type="number" min="0" step="0.01" value="{{ old("products.$index.price", $product['price'] ?? '') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Harga">
                                <div class="grid grid-cols-[1fr_120px] gap-4">
                                    <input name="products[{{ $index }}][stock]" type="number" min="0" step="0.01" value="{{ old("products.$index.stock", $product['stock'] ?? '') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Stok">
                                    <input name="products[{{ $index }}][unit]" type="text" value="{{ old("products.$index.unit", $product['unit'] ?? 'pcs') }}" class="rounded-2xl border border-outline-variant bg-white px-4 py-3 outline-none focus:border-primary focus:ring-2 focus:ring-primary" placeholder="Unit">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <div class="onboarding-actions flex items-center justify-between gap-4">
                <a href="{{ route('landing') }}" class="rounded-full border border-outline-variant px-6 py-3 font-medium text-on-surface-variant transition hover:border-primary hover:text-primary">Kembali</a>
                <button type="submit" class="rounded-full bg-primary px-7 py-3 font-bold text-on-primary transition hover:opacity-90">
                    Selesaikan Onboarding
                </button>
            </div>
        </form>
    </section>
</main>
@endsection
