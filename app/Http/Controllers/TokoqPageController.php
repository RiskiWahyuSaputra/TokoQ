<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use App\Models\TokoqTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TokoqPageController extends Controller
{
    public function landing(): View
    {
        $store = Store::current();
        $landing = TokoqTemplate::landing();

        $isReady = $store?->isOnboarded() ?? false;

        if ($isReady) {
            $landing['hero']['primary_cta'] = 'Buka Dashboard';
            $landing['hero']['secondary_cta'] = 'Kelola Kasir';
            $landing['stats'] = [
                ['value' => $this->currency((float) $store->sales()->sum('total')), 'label' => 'omzet total yang sudah tercatat'],
                ['value' => (string) $store->products()->count(), 'label' => 'produk aktif di inventori saat ini'],
                ['value' => (string) $store->sales()->count(), 'label' => 'transaksi yang sudah tersimpan'],
            ];
        }

        return view('pages.tokoq.landing', [
            'title' => 'TokoQ | Digital Twin UMKM',
            'landing' => $landing,
            'isStoreReady' => $isReady,
        ]);
    }

    public function dashboard(): View
    {
        $store = $this->currentStore();
        $todaySales = $store->sales()->whereDate('sold_at', today())->get();
        $weeklySales = $store->sales()->where('sold_at', '>=', now()->subDays(6)->startOfDay())->get();
        $topProduct = $this->topProduct($store);
        $lowStockProducts = $store->lowStockProducts();
        $latestSale = $store->sales()->latest('sold_at')->first();
        $timeline = $this->salesTimeline($store);
        $dailyBars = $this->weeklySalesBars($store);
        $prediction = $this->tomorrowPrediction($store);
        $bestSellers = $this->bestSellers($store);
        $criticalStocks = $this->criticalStocks($store);
        $score = $this->dashboardScore($store);

        $dashboard = [
            'greeting' => $this->greeting(),
            'score' => $score,
            'score_label' => $score >= 75 ? 'Toko dalam kondisi sehat' : ($score >= 55 ? 'Toko perlu sedikit perhatian' : 'Toko butuh tindakan cepat'),
            'score_description' => $score >= 75
                ? 'Semua sistem berjalan stabil, stok utama aman, dan ritme transaksi cukup sehat.'
                : ($score >= 55
                    ? 'Ada beberapa area yang perlu dipantau, terutama stok tipis dan konsistensi penjualan.'
                    : 'Perlu fokus pada restok, transaksi, dan pembenahan katalog agar performa membaik.'),
            'metrics' => [
                [
                    'label' => 'Omzet Hari Ini',
                    'value' => $this->currency((float) $todaySales->sum('total')),
                    'change' => $weeklySales->count() > 0 ? '+'.round(($todaySales->sum('total') / max(1, $weeklySales->avg('total'))) * 10).'% ritme hari ini' : 'Belum ada pembanding',
                    'icon' => 'payments',
                    'icon_bg' => 'bg-secondary-container text-on-secondary-container',
                    'accent' => 'text-on-primary-container bg-primary-container',
                ],
                [
                    'label' => 'Transaksi Hari Ini',
                    'value' => (string) $todaySales->count(),
                    'change' => 'Invoice terakhir '.($latestSale?->invoice_number ?? 'belum ada'),
                    'icon' => 'receipt_long',
                    'icon_bg' => 'bg-tertiary-fixed text-on-tertiary-fixed-variant',
                    'accent' => null,
                ],
                [
                    'label' => 'Produk Stok Kritis',
                    'value' => (string) $criticalStocks->count(),
                    'change' => $lowStockProducts->count().' total produk butuh pantauan',
                    'icon' => 'warning',
                    'icon_bg' => 'bg-error-container text-on-error-container',
                    'accent' => null,
                    'value_class' => 'text-error',
                ],
                [
                    'label' => 'Prediksi Omzet Besok',
                    'value' => $this->currency($prediction['value']),
                    'change' => $prediction['delta'].' vs kemarin',
                    'icon' => 'auto_awesome',
                    'icon_bg' => 'bg-tertiary-container text-on-tertiary-container',
                    'accent_border' => true,
                ],
            ],
            'insights' => [
                [
                    'icon' => 'trending_up',
                    'icon_class' => 'text-tertiary',
                    'description' => $topProduct
                        ? 'Penjualan '.$topProduct['name'].' naik dan sudah bergerak '.$this->decimal($topProduct['quantity']).' '.$topProduct['unit'].' dalam 7 hari terakhir.'
                        : 'Belum ada penjualan. Selesaikan transaksi pertama dari halaman kasir.',
                ],
                [
                    'icon' => 'inventory_2',
                    'icon_class' => 'text-on-secondary-fixed-variant',
                    'description' => $lowStockProducts->isNotEmpty()
                        ? $lowStockProducts->first()->name.' tersisa '.$this->decimal((float) $lowStockProducts->first()->stock_quantity).' '.$lowStockProducts->first()->unit.'.'
                        : 'Semua produk masih dalam status aman untuk sementara.',
                ],
                [
                    'icon' => 'shopping_cart_checkout',
                    'icon_class' => 'text-on-secondary-fixed-variant',
                    'description' => $latestSale
                        ? 'Invoice '.$latestSale->invoice_number.' berhasil tersimpan dengan total '.$this->currency((float) $latestSale->total).'.'
                        : 'Mulai transaksi dari kasir agar insight penjualan ikut terisi.',
                ],
            ],
            'daily_bars' => $dailyBars,
            'timeline' => $timeline,
            'critical_stocks' => $criticalStocks,
            'best_sellers' => $bestSellers,
        ];

        return view('pages.tokoq.dashboard', $this->appPageData($store, [
            'title' => 'Dashboard | TokoQ',
            'pageTitle' => 'Dashboard Utama',
            'pageSubtitle' => 'Ringkasan performa toko yang paling penting hari ini.',
            'primaryAction' => ['label' => 'Buka Kasir', 'route' => 'pos', 'icon' => 'point_of_sale'],
            'headerTabs' => [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                ['label' => 'Laporan', 'route' => 'reports'],
            ],
            'dashboard' => $dashboard,
        ]));
    }

    public function pos(): View
    {
        $store = $this->currentStore();
        $latestSale = $store->sales()->with('items.product')->latest('sold_at')->first();

        $pos = [
            'categories' => $store->categories()->orderBy('name')->get()->map(fn (Category $category) => [
                'label' => $category->name,
                'slug' => $category->slug,
            ]),
            'products' => $store->products()->with('category')->orderBy('name')->get()->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category?->name ?? 'Umum',
                    'price' => $this->currency((float) $product->price),
                    'price_raw' => (float) $product->price,
                    'stock' => $this->decimal((float) $product->stock_quantity).' '.$product->unit,
                    'stock_raw' => (float) $product->stock_quantity,
                    'badge' => $product->stockStatus(),
                ];
            }),
            'recent_sale' => $latestSale ? [
                'invoice' => $latestSale->invoice_number,
                'customer_name' => $latestSale->customer_name,
                'items' => $latestSale->items->map(fn (SaleItem $item) => [
                    'name' => $item->product?->name ?? 'Produk',
                    'qty' => $this->decimal((float) $item->quantity),
                    'total' => $this->currency((float) $item->line_total),
                ]),
                'summary' => [
                    'subtotal' => $this->currency((float) $latestSale->subtotal),
                    'discount' => $this->currency((float) $latestSale->discount),
                    'total' => $this->currency((float) $latestSale->total),
                ],
            ] : null,
        ];

        return view('pages.tokoq.pos', $this->appPageData($store, [
            'title' => 'Kasir POS | TokoQ',
            'pageTitle' => 'Kasir POS',
            'pageSubtitle' => 'Pilih kuantitas produk dan simpan transaksi langsung ke database.',
            'searchPlaceholder' => 'Cari produk atau scan barcode...',
            'headerTabs' => [
                ['label' => 'Kasir', 'route' => 'pos'],
                ['label' => 'Inventori', 'route' => 'inventory'],
            ],
            'pos' => $pos,
        ]));
    }

    public function inventory(): View
    {
        $store = $this->currentStore();
        $products = $store->products()->with('category')->orderBy('name')->get();

        $inventory = [
            'stats' => [
                ['label' => 'SKU Aktif', 'value' => (string) $products->count()],
                ['label' => 'Stok Kritis', 'value' => (string) $products->filter(fn (Product $product) => $product->stockStatus() === 'Kritis')->count()],
                ['label' => 'Nilai Inventori', 'value' => $this->currency($products->sum(fn (Product $product) => (float) $product->price * (float) $product->stock_quantity))],
            ],
            'products' => $products->map(fn (Product $product) => [
                'name' => $product->name,
                'category' => $product->category?->name ?? 'Umum',
                'sku' => $product->sku,
                'stock' => $this->decimal((float) $product->stock_quantity).' '.$product->unit,
                'status' => $product->stockStatus(),
            ]),
            'recommendations' => $this->inventoryRecommendations($products),
        ];

        return view('pages.tokoq.inventory', $this->appPageData($store, [
            'title' => 'Inventori | TokoQ',
            'pageTitle' => 'Inventori',
            'pageSubtitle' => 'Tambah produk baru dan pantau stok yang sudah tersimpan.',
            'searchPlaceholder' => 'Cari nama produk atau SKU...',
            'inventory' => $inventory,
        ]));
    }

    public function sales(): View
    {
        $store = $this->currentStore();
        $rangeStart = now()->subDays(6)->startOfDay();
        $sales = $store->sales()->where('sold_at', '>=', $rangeStart)->with('items.product.category')->orderBy('sold_at')->get();
        $transactions = $store->sales()->latest('sold_at')->take(5)->get();
        $totalSales = (float) $sales->sum('total');
        $itemCount = $sales->flatMap->items->sum('quantity');
        $dailyTotals = collect(range(0, 6))->map(function (int $offset) use ($sales) {
            $day = now()->subDays(6 - $offset);
            $total = (float) $sales->whereBetween('sold_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])->sum('total');

            return [
                'label' => $day->translatedFormat('D'),
                'total' => $total,
            ];
        });

        $maxDaily = max(1, (float) $dailyTotals->max('total'));
        $categoryTotals = $sales
            ->flatMap->items
            ->groupBy(fn (SaleItem $item) => $item->product?->category?->name ?? 'Umum')
            ->map(fn (Collection $items, string $category) => [
                'label' => $category,
                'amount' => (float) $items->sum('line_total'),
            ])
            ->sortByDesc('amount')
            ->take(4)
            ->values();

        $salesData = [
            'summaries' => [
                ['label' => 'Total Omzet', 'value' => $this->currency($totalSales), 'meta' => '7 hari terakhir'],
                ['label' => 'Rata-rata Transaksi', 'value' => $this->currency($sales->count() ? $totalSales / $sales->count() : 0), 'meta' => $sales->count().' transaksi'],
                ['label' => 'Produk Terjual', 'value' => $this->decimal((float) $itemCount), 'meta' => 'Unit keluar dari inventori'],
                ['label' => 'Jam Sibuk', 'value' => $this->busiestHourLabel($store), 'meta' => 'Berdasarkan transaksi tersimpan'],
            ],
            'daily_bars' => $dailyTotals->map(fn (array $day) => [
                'label' => $day['label'],
                'height' => (int) round(($day['total'] / $maxDaily) * 100),
                'total' => $this->currency($day['total']),
            ]),
            'top_categories' => $categoryTotals->map(function (array $category) use ($totalSales) {
                $percentage = $totalSales > 0 ? round(($category['amount'] / $totalSales) * 100) : 0;

                return [
                    'label' => $category['label'],
                    'value' => $percentage.'%',
                ];
            }),
            'transactions' => $transactions->map(fn (Sale $sale) => [
                'time' => $sale->sold_at->format('H:i'),
                'invoice' => $sale->invoice_number,
                'total' => $this->currency((float) $sale->total),
            ]),
        ];

        return view('pages.tokoq.sales', $this->appPageData($store, [
            'title' => 'Analisis Penjualan | TokoQ',
            'pageTitle' => 'Penjualan',
            'pageSubtitle' => 'Analisis ini sekarang dihitung dari transaksi nyata yang tersimpan.',
            'primaryAction' => ['label' => 'Buka Kasir', 'route' => 'pos', 'icon' => 'point_of_sale'],
            'headerTabs' => [
                ['label' => 'Dashboard', 'route' => 'dashboard'],
                ['label' => 'Laporan', 'route' => 'reports'],
            ],
            'sales' => $salesData,
        ]));
    }

    public function reports(): View
    {
        $store = $this->currentStore();
        $sales = $store->sales()->with('items.product')->latest('sold_at')->get();
        $salesCount = $sales->count();
        $productsCount = $store->products()->count();
        $lowStockCount = $store->lowStockProducts()->count();
        $rangeStart = now()->subDays(6)->startOfDay();
        $rangeEnd = now()->endOfDay();
        $periodSales = $sales->filter(fn (Sale $sale) => $sale->sold_at->between($rangeStart, $rangeEnd));
        $weeklyRevenue = (float) $periodSales->sum('total');
        $discountTotal = (float) $periodSales->sum('discount');
        $taxEstimate = $weeklyRevenue * 0.11;
        $netRevenue = max(0, $weeklyRevenue - $discountTotal - $taxEstimate);
        $profitEstimate = $netRevenue * 0.24;
        $topProducts = $this->bestSellers($store)->take(3);
        $generatedAt = now();

        $reports = [
            'types' => [
                ['label' => 'Penjualan', 'icon' => 'analytics', 'active' => true],
                ['label' => 'Inventori', 'icon' => 'inventory_2', 'active' => false],
                ['label' => 'Kasir', 'icon' => 'point_of_sale', 'active' => false],
                ['label' => 'Prediksi', 'icon' => 'psychology', 'active' => false],
            ],
            'period' => [
                'label' => $rangeStart->translatedFormat('d M Y').' - '.$rangeEnd->translatedFormat('d M Y'),
                'generated_at' => $generatedAt->translatedFormat('d M Y, H:i').' WIB',
            ],
            'summary_cards' => [
                [
                    'label' => 'Total Omzet',
                    'value' => $this->currency($weeklyRevenue),
                    'meta' => $salesCount > 0 ? '+'.max(1, round(($periodSales->count() / max(1, $salesCount)) * 100)).'% kontribusi periode ini' : 'Belum ada data',
                    'icon' => 'payments',
                ],
                [
                    'label' => 'Transaksi',
                    'value' => $periodSales->count().' Nota',
                    'meta' => $periodSales->count() > 0 ? round($periodSales->count() / 7, 1).' transaksi per hari' : 'Belum ada transaksi',
                    'icon' => 'shopping_bag',
                ],
                [
                    'label' => 'Estimasi Profit',
                    'value' => $this->currency($profitEstimate),
                    'meta' => '24% margin bersih',
                    'icon' => 'savings',
                ],
            ],
            'highlights' => [
                'Omzet 7 hari terakhir tercatat '.$this->currency($weeklyRevenue).'.',
                $productsCount.' produk aktif sedang dipantau oleh sistem inventori.',
                $lowStockCount.' produk perlu perhatian stok dalam waktu dekat.',
            ],
            'exports' => [
                ['label' => 'Unduh PDF', 'description' => 'Ringkasan '.$salesCount.' transaksi yang sudah tersimpan', 'icon' => 'picture_as_pdf', 'class' => 'text-red-600'],
                ['label' => 'Ekspor Excel', 'description' => 'Daftar '.$productsCount.' produk dan status stok terbaru', 'icon' => 'description', 'class' => 'text-green-700'],
                ['label' => 'Kirim WhatsApp', 'description' => 'Sorotan bisnis untuk '.$store->owner_name, 'icon' => 'send', 'button_class' => 'bg-[#25D366] text-white'],
            ],
            'document' => [
                'gross_revenue' => $this->currency($weeklyRevenue),
                'discount_total' => $this->currency($discountTotal),
                'tax_estimate' => $this->currency($taxEstimate),
                'net_revenue' => $this->currency($netRevenue),
                'top_products' => $topProducts,
                'verification_code' => 'TQ-'.str_pad((string) $store->id, 4, '0', STR_PAD_LEFT).'-'.$generatedAt->format('ymd'),
            ],
        ];

        return view('pages.tokoq.reports', $this->appPageData($store, [
            'title' => 'Laporan | TokoQ',
            'pageTitle' => 'Laporan Bisnis',
            'pageSubtitle' => 'Sorotan laporan sekarang dirangkum dari data inventori dan penjualan Anda.',
            'primaryAction' => ['label' => 'Buka Kasir', 'route' => 'pos', 'icon' => 'point_of_sale'],
            'headerTabs' => [
                ['label' => 'Ringkasan', 'route' => 'reports'],
                ['label' => 'Prediksi AI', 'route' => 'forecast'],
            ],
            'reports' => $reports,
        ]));
    }

    public function forecast(): View
    {
        $store = $this->currentStore();
        $dailyTotals = collect(range(1, 7))->map(function (int $daysAgo) use ($store) {
            $day = now()->subDays($daysAgo);

            return (float) $store->sales()
                ->whereBetween('sold_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('total');
        });

        $average = (float) $dailyTotals->avg();
        $yesterday = (float) $dailyTotals->first();
        $prediction = $average > 0 ? $average * 1.08 : 0;
        $confidence = $store->sales()->count() >= 5 ? 86 : 64;
        $topProduct = $this->topProduct($store);
        $lowStock = $store->lowStockProducts()->take(2);

        $forecast = [
            'prediction' => [
                'value' => $this->currency($prediction),
                'delta' => $yesterday > 0 ? round((($prediction - $yesterday) / $yesterday) * 100).'%' : '0%',
                'confidence' => $confidence.'%',
            ],
            'bars' => $dailyTotals
                ->reverse()
                ->push($prediction)
                ->map(function (float $amount) use ($dailyTotals, $prediction) {
                    $max = max(1, $dailyTotals->max(), $prediction);

                    return (int) round(($amount / $max) * 100);
                }),
            'drivers' => [
                [
                    'title' => 'Pola penjualan mingguan',
                    'description' => 'Rata-rata omzet 7 hari terakhir ada di '.$this->currency($average).' per hari.',
                ],
                [
                    'title' => 'Produk paling bergerak',
                    'description' => $topProduct
                        ? $topProduct['name'].' saat ini menjadi kontributor penjualan terbesar.'
                        : 'Belum ada produk dominan karena transaksi masih minim.',
                ],
                [
                    'title' => 'Tekanan stok',
                    'description' => $lowStock->isNotEmpty()
                        ? $lowStock->pluck('name')->implode(', ').' berpotensi menahan omzet bila tidak direstok.'
                        : 'Tidak ada tekanan stok yang berarti untuk saat ini.',
                ],
            ],
            'actions' => [
                $lowStock->isNotEmpty()
                    ? 'Restok '.$lowStock->first()->name.' sebelum stok turun lebih jauh.'
                    : 'Jaga ritme stok produk utama seperti sekarang.',
                $topProduct
                    ? 'Siapkan display atau bundling tambahan untuk '.$topProduct['name'].'.'
                    : 'Mulai transaksi rutin agar prediksi semakin akurat.',
                'Gunakan halaman laporan untuk memeriksa tren mingguan sebelum belanja ulang.',
            ],
        ];

        return view('pages.tokoq.forecast', $this->appPageData($store, [
            'title' => 'Prediksi AI | TokoQ',
            'pageTitle' => 'Prediksi AI',
            'pageSubtitle' => 'Prediksi ini dihitung dari histori penjualan dan tekanan stok terbaru.',
            'headerTabs' => [
                ['label' => 'Prediksi', 'route' => 'forecast'],
                ['label' => 'Laporan', 'route' => 'reports'],
            ],
            'forecast' => $forecast,
        ]));
    }

    private function appPageData(Store $store, array $data): array
    {
        return array_merge([
            'navItems' => TokoqTemplate::sidebarNavigation(),
            'owner' => $store->ownerSummary(),
        ], $data);
    }

    private function currentStore(): Store
    {
        return Store::current() ?? abort(404, 'Store tidak ditemukan.');
    }

    private function dashboardScore(Store $store): int
    {
        $productCount = max(1, $store->products()->count());
        $lowStockCount = $store->lowStockProducts()->count();
        $salesCount = $store->sales()->where('sold_at', '>=', now()->subDays(7))->count();
        $inventoryScore = max(0, 40 - ($lowStockCount * 5));
        $salesScore = min(40, $salesCount * 6);
        $catalogScore = min(20, $productCount * 3);

        return min(100, $inventoryScore + $salesScore + $catalogScore);
    }

    private function weeklySalesBars(Store $store): Collection
    {
        $bars = collect(range(0, 6))->map(function (int $offset) use ($store) {
            $day = now()->subDays(6 - $offset);
            $total = (float) $store->sales()
                ->whereBetween('sold_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('total');

            return [
                'label' => $day->translatedFormat('D'),
                'total' => $total,
            ];
        });

        $max = max(1, (float) $bars->max('total'));

        return $bars->map(function (array $bar) use ($max) {
            return [
                'label' => $bar['label'],
                'total' => $this->currency($bar['total']),
                'height' => (int) round(($bar['total'] / $max) * 100),
            ];
        });
    }

    private function salesTimeline(Store $store): Collection
    {
        $periods = [
            ['label' => '08.00 - 11.59', 'from' => 8, 'to' => 11],
            ['label' => '12.00 - 15.59', 'from' => 12, 'to' => 15],
            ['label' => '16.00 - 19.59', 'from' => 16, 'to' => 19],
            ['label' => '20.00 - 23.59', 'from' => 20, 'to' => 23],
        ];

        $todaySales = $store->sales()->whereDate('sold_at', today())->get();
        $max = max(1, (float) $todaySales->max('total'));

        return collect($periods)->map(function (array $period) use ($todaySales, $max) {
            $total = (float) $todaySales
                ->filter(fn (Sale $sale) => (int) $sale->sold_at->format('H') >= $period['from'] && (int) $sale->sold_at->format('H') <= $period['to'])
                ->sum('total');

            return [
                'hour' => $period['label'],
                'value' => $this->currency($total),
                'height' => (int) round(($total / $max) * 100),
            ];
        });
    }

    private function inventoryRecommendations(Collection $products): array
    {
        $critical = $products->filter(fn (Product $product) => $product->stockStatus() === 'Kritis');
        $thin = $products->filter(fn (Product $product) => $product->stockStatus() === 'Menipis');

        return [
            $critical->isNotEmpty()
                ? $critical->first()->name.' perlu restok segera karena stoknya sudah kritis.'
                : 'Belum ada produk yang masuk status kritis.',
            $thin->isNotEmpty()
                ? $thin->pluck('name')->take(2)->implode(', ').' cocok dipantau sebelum akhir minggu.'
                : 'Mayoritas stok masih berada di zona aman.',
            'Tambahkan produk baru dari form di samping untuk memperluas katalog.',
        ];
    }

    private function topProduct(Store $store): ?array
    {
        $top = SaleItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('sale', fn ($query) => $query->where('store_id', $store->id)->where('sold_at', '>=', now()->subDays(7)))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->first();

        if (! $top || ! $top->product) {
            return null;
        }

        return [
            'name' => $top->product->name,
            'quantity' => (float) $top->total_quantity,
            'unit' => $top->product->unit,
            'revenue' => (float) $top->product->saleItems()->whereHas('sale', fn ($query) => $query->where('store_id', $store->id)->where('sold_at', '>=', now()->subDays(7)))->sum('line_total'),
        ];
    }

    private function bestSellers(Store $store): Collection
    {
        return SaleItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(line_total) as total_revenue'))
            ->whereHas('sale', fn ($query) => $query->where('store_id', $store->id)->where('sold_at', '>=', now()->subDays(30)))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->take(4)
            ->get()
            ->values()
            ->map(function (SaleItem $item, int $index) {
                return [
                    'rank' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'name' => $item->product?->name ?? 'Produk',
                    'quantity_value' => $this->decimal((float) $item->total_quantity),
                    'quantity' => $this->decimal((float) $item->total_quantity).' terjual',
                    'revenue' => $this->currency((float) $item->total_revenue),
                ];
            });
    }

    private function criticalStocks(Store $store): Collection
    {
        return $store->products()
            ->with('category')
            ->orderBy('stock_quantity')
            ->get()
            ->filter(fn (Product $product) => in_array($product->stockStatus(), ['Kritis', 'Menipis'], true))
            ->take(4)
            ->values()
            ->map(function (Product $product) {
                return [
                    'name' => $product->name,
                    'stock' => $this->decimal((float) $product->stock_quantity).' '.$product->unit,
                    'status' => $product->stockStatus(),
                ];
            });
    }

    private function tomorrowPrediction(Store $store): array
    {
        $dailyTotals = collect(range(1, 7))->map(function (int $daysAgo) use ($store) {
            $day = now()->subDays($daysAgo);

            return (float) $store->sales()
                ->whereBetween('sold_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('total');
        });

        $average = (float) $dailyTotals->avg();
        $yesterday = (float) $dailyTotals->first();
        $prediction = $average > 0 ? $average * 1.08 : 0;

        return [
            'value' => $prediction,
            'delta' => $yesterday > 0 ? '+'.round((($prediction - $yesterday) / $yesterday) * 100).'%' : '0%',
        ];
    }

    private function greeting(): string
    {
        $hour = (int) now()->format('H');

        return match (true) {
            $hour < 11 => 'Selamat Pagi',
            $hour < 15 => 'Selamat Siang',
            $hour < 19 => 'Selamat Sore',
            default => 'Selamat Malam',
        };
    }

    private function busiestHourLabel(Store $store): string
    {
        $sale = $store->sales()->latest('sold_at')->first();

        return $sale ? $sale->sold_at->format('H.00') : 'Belum ada';
    }

    private function currency(float $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    private function decimal(float $value): string
    {
        return fmod($value, 1.0) === 0.0
            ? number_format($value, 0, ',', '.')
            : number_format($value, 2, ',', '.');
    }
}
