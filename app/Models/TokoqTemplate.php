<?php

namespace App\Models;

class TokoqTemplate
{
    public static function owner(): array
    {
        return [
            'name' => 'Bu Sari',
            'role' => 'Pemilik Toko',
            'initials' => 'BS',
            'store_name' => 'TokoQ Grosir Harian',
        ];
    }

    public static function sidebarNavigation(): array
    {
        return [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
            ['route' => 'pos', 'label' => 'Kasir POS', 'icon' => 'point_of_sale'],
            ['route' => 'sales', 'label' => 'Penjualan', 'icon' => 'analytics'],
            ['route' => 'inventory', 'label' => 'Inventori', 'icon' => 'inventory_2'],
            ['route' => 'forecast', 'label' => 'Prediksi AI', 'icon' => 'psychology'],
            ['route' => 'reports', 'label' => 'Laporan', 'icon' => 'description'],
        ];
    }

    public static function landing(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'Solusi digital UMKM Indonesia',
                'title' => 'Sistem kasir dan digital twin sederhana untuk pemilik toko yang ingin naik level.',
                'description' => 'Catat transaksi, jaga stok, lihat tren penjualan, dan dapatkan rekomendasi AI dalam satu alur kerja yang rapi.',
                'primary_cta' => 'Lihat Dashboard Demo',
                'secondary_cta' => 'Mulai Onboarding',
                'preview_image' => asset('template/dashboard_pemilik_tokoq/screen.png'),
            ],
            'pain_points' => [
                ['icon' => 'edit_note', 'title' => 'Pencatatan Manual', 'description' => 'Rekap harian memakan waktu dan rawan salah hitung saat toko sedang ramai.'],
                ['icon' => 'inventory', 'title' => 'Stok Sulit Dipantau', 'description' => 'Barang laku sering habis tanpa alarm, sementara produk lambat jalan menumpuk.'],
                ['icon' => 'monitoring', 'title' => 'Keputusan Masih Tebak-tebakan', 'description' => 'Sulit tahu produk mana yang paling sehat margin dan kapan harus restok.'],
            ],
            'solutions' => [
                ['title' => 'Kasir cepat untuk transaksi harian', 'description' => 'Tombol produk favorit, ringkasan keranjang, dan total pembayaran yang mudah dibaca.'],
                ['title' => 'Inventori yang terasa hidup', 'description' => 'Lihat stok kritis, perputaran produk, dan barang yang perlu diamankan hari ini.'],
                ['title' => 'Insight AI yang langsung bisa ditindak', 'description' => 'Saran restok, prediksi omzet, dan draft laporan siap kirim ke pemilik usaha.'],
            ],
            'stats' => [
                ['value' => '8.750.000', 'label' => 'omzet harian yang dipantau di demo'],
                ['value' => '142', 'label' => 'produk populer yang dilacak per hari'],
                ['value' => '86%', 'label' => 'confidence untuk rekomendasi AI'],
            ],
        ];
    }

    public static function onboarding(): array
    {
        return [
            'steps' => [
                ['label' => 'Profil Toko', 'state' => 'done'],
                ['label' => 'Inventori', 'state' => 'active'],
                ['label' => 'Riwayat Penjualan', 'state' => 'pending'],
                ['label' => 'Konfirmasi', 'state' => 'pending'],
            ],
            'products' => [
                ['name' => 'Beras Premium', 'category' => 'Kebutuhan Pokok', 'price' => 'Rp 14.500 / kg', 'selected' => true],
                ['name' => 'Gula Pasir', 'category' => 'Kebutuhan Pokok', 'price' => 'Rp 16.000 / kg', 'selected' => false],
                ['name' => 'Mie Instan Ayam Bawang', 'category' => 'Snack & Mi', 'price' => 'Rp 3.500 / pcs', 'selected' => false],
                ['name' => 'Minyak Goreng 1L', 'category' => 'Sembako', 'price' => 'Rp 18.000 / botol', 'selected' => true],
            ],
        ];
    }

    public static function dashboard(): array
    {
        return [
            'metrics' => [
                ['label' => 'Omzet Hari Ini', 'value' => 'Rp 8.750.000', 'change' => '+12% dari kemarin'],
                ['label' => 'Transaksi', 'value' => '318', 'change' => 'Puncak jam 18.00'],
                ['label' => 'Margin Estimasi', 'value' => '23,4%', 'change' => 'Naik 1,8 poin'],
            ],
            'insights' => [
                ['title' => 'Produk terlaris', 'description' => 'Mie instan menyumbang 18% unit terjual hari ini.'],
                ['title' => 'Alarm stok', 'description' => 'Beras premium tersisa untuk 2 hari dengan laju penjualan sekarang.'],
                ['title' => 'Pelanggan aktif', 'description' => 'Pelanggan grosir kembali datang 3 kali minggu ini.'],
            ],
            'quick_actions' => [
                ['label' => 'Buka Kasir', 'route' => 'pos', 'icon' => 'point_of_sale'],
                ['label' => 'Cek Inventori', 'route' => 'inventory', 'icon' => 'inventory_2'],
                ['label' => 'Lihat Prediksi AI', 'route' => 'forecast', 'icon' => 'psychology'],
            ],
            'timeline' => [
                ['hour' => '08.00', 'value' => 'Rp 650.000'],
                ['hour' => '12.00', 'value' => 'Rp 1.240.000'],
                ['hour' => '16.00', 'value' => 'Rp 2.180.000'],
                ['hour' => '20.00', 'value' => 'Rp 1.870.000'],
            ],
        ];
    }

    public static function pos(): array
    {
        return [
            'categories' => ['Semua', 'Sembako', 'Minuman', 'Snack', 'Bumbu', 'Kebutuhan Mandi'],
            'products' => [
                ['name' => 'Mie Instan Ayam Bawang', 'category' => 'Sembako', 'price' => 'Rp 3.500', 'stock' => 92, 'badge' => 'Populer'],
                ['name' => 'Teh Botol Sosro', 'category' => 'Minuman', 'price' => 'Rp 5.000', 'stock' => 36, 'badge' => 'Cepat Laku'],
                ['name' => 'Sabun Cuci Piring', 'category' => 'Kebutuhan Mandi', 'price' => 'Rp 12.000', 'stock' => 18, 'badge' => 'Bundle'],
                ['name' => 'Beras Premium 5kg', 'category' => 'Sembako', 'price' => 'Rp 74.000', 'stock' => 12, 'badge' => 'Grosir'],
            ],
            'cart' => [
                ['name' => 'Mie Instan Ayam Bawang', 'qty' => 4, 'total' => 'Rp 14.000'],
                ['name' => 'Teh Botol Sosro', 'qty' => 2, 'total' => 'Rp 10.000'],
                ['name' => 'Beras Premium 5kg', 'qty' => 1, 'total' => 'Rp 74.000'],
            ],
            'summary' => [
                'subtotal' => 'Rp 98.000',
                'discount' => 'Rp 5.000',
                'total' => 'Rp 93.000',
            ],
        ];
    }

    public static function inventory(): array
    {
        return [
            'stats' => [
                ['label' => 'SKU Aktif', 'value' => '184'],
                ['label' => 'Stok Kritis', 'value' => '12'],
                ['label' => 'Nilai Inventori', 'value' => 'Rp 64,5 jt'],
            ],
            'products' => [
                ['name' => 'Beras Premium', 'category' => 'Sembako', 'sku' => 'BRS-001', 'stock' => '28 kg', 'status' => 'Aman'],
                ['name' => 'Minyak Goreng 1L', 'category' => 'Sembako', 'sku' => 'MYK-010', 'stock' => '9 botol', 'status' => 'Menipis'],
                ['name' => 'Gula Pasir', 'category' => 'Sembako', 'sku' => 'GPL-008', 'stock' => '6 kg', 'status' => 'Kritis'],
                ['name' => 'Teh Botol Sosro', 'category' => 'Minuman', 'sku' => 'MNM-117', 'stock' => '34 botol', 'status' => 'Aman'],
            ],
        ];
    }

    public static function sales(): array
    {
        return [
            'summaries' => [
                ['label' => 'Total Omzet', 'value' => 'Rp 8.750.000', 'meta' => '12% dari kemarin'],
                ['label' => 'Rata-rata Transaksi', 'value' => 'Rp 27.500', 'meta' => 'Stabil hari ini'],
                ['label' => 'Produk Terlaris', 'value' => 'Mie Instan', 'meta' => '142 unit terjual'],
                ['label' => 'Jam Sibuk', 'value' => '18.00 - 20.00', 'meta' => '35% transaksi'],
            ],
            'daily_bars' => [54, 68, 60, 82, 76, 91, 95],
            'top_categories' => [
                ['label' => 'Sembako', 'value' => '41%'],
                ['label' => 'Snack', 'value' => '27%'],
                ['label' => 'Minuman', 'value' => '19%'],
                ['label' => 'Lainnya', 'value' => '13%'],
            ],
            'transactions' => [
                ['time' => '08.15', 'invoice' => 'INV-240513-001', 'total' => 'Rp 76.000'],
                ['time' => '10.42', 'invoice' => 'INV-240513-014', 'total' => 'Rp 128.000'],
                ['time' => '17.05', 'invoice' => 'INV-240513-066', 'total' => 'Rp 92.500'],
            ],
        ];
    }

    public static function reports(): array
    {
        return [
            'types' => [
                ['label' => 'Penjualan', 'icon' => 'analytics', 'active' => true],
                ['label' => 'Inventori', 'icon' => 'inventory_2', 'active' => false],
                ['label' => 'Kasir', 'icon' => 'point_of_sale', 'active' => false],
                ['label' => 'Prediksi', 'icon' => 'psychology', 'active' => false],
            ],
            'highlights' => [
                'Omzet minggu ini tumbuh 14% dibanding minggu sebelumnya.',
                'Tiga produk teratas menghasilkan 38% total margin.',
                'Laporan PDF bisa dijadwalkan otomatis ke email pemilik setiap Senin pagi.',
            ],
            'exports' => [
                ['label' => 'PDF Harian', 'description' => 'Ringkasan penjualan dan kas harian'],
                ['label' => 'Excel Stok', 'description' => 'Daftar stok, barang kritis, dan kebutuhan restok'],
                ['label' => 'Email Owner', 'description' => 'Kirim otomatis ke pemilik toko dan partner'],
            ],
        ];
    }

    public static function forecast(): array
    {
        return [
            'prediction' => [
                'value' => 'Rp 1.520.000',
                'delta' => '+12% vs kemarin',
                'confidence' => '86%',
            ],
            'drivers' => [
                ['title' => 'Hari Libur Besok', 'description' => 'Trafik pelanggan diperkirakan naik karena momen belanja rumah tangga.'],
                ['title' => 'Stok Produk Utama', 'description' => 'Mie instan dan minyak goreng masih cukup untuk menopang lonjakan permintaan.'],
                ['title' => 'Pola Penjualan Mingguan', 'description' => 'Sabtu dan Minggu konsisten lebih tinggi dari hari kerja biasa.'],
            ],
            'actions' => [
                'Restok gula pasir sebelum jam 15.00.',
                'Siapkan paket bundling mie instan + teh botol.',
                'Tambahkan satu kasir cadangan pada jam 17.00 - 20.00.',
            ],
        ];
    }
}
