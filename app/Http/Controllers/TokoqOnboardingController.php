<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TokoqOnboardingController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $store = Store::current();

        if ($store && $store->isOnboarded()) {
            return redirect()->route('dashboard');
        }

        $draft = $store;

        $defaultProducts = [
            ['name' => 'Beras Premium', 'category' => 'Sembako', 'price' => 14500, 'stock' => 28, 'unit' => 'kg'],
            ['name' => 'Mie Instan Ayam Bawang', 'category' => 'Snack', 'price' => 3500, 'stock' => 96, 'unit' => 'pcs'],
            ['name' => 'Teh Botol Sosro', 'category' => 'Minuman', 'price' => 5000, 'stock' => 36, 'unit' => 'botol'],
        ];

        return view('pages.tokoq.onboarding', [
            'title' => 'Onboarding | TokoQ',
            'draftStore' => $draft,
            'defaultProducts' => old('products', $defaultProducts),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:120'],
            'owner_name' => ['required', 'string', 'max:120'],
            'owner_role' => ['nullable', 'string', 'max:120'],
            'business_type' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:500'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.name' => ['nullable', 'string', 'max:120'],
            'products.*.category' => ['nullable', 'string', 'max:120'],
            'products.*.price' => ['nullable', 'numeric', 'min:0'],
            'products.*.stock' => ['nullable', 'numeric', 'min:0'],
            'products.*.unit' => ['nullable', 'string', 'max:30'],
        ]);

        $products = collect($validated['products'])
            ->filter(fn (array $product) => filled($product['name'] ?? null))
            ->values();

        if ($products->isEmpty()) {
            return back()
                ->withErrors(['products' => 'Isi minimal satu produk untuk menyelesaikan onboarding.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $products): void {
            $store = Store::query()->firstOrNew();
            $store->fill([
                'name' => $validated['store_name'],
                'owner_name' => $validated['owner_name'],
                'owner_role' => $validated['owner_role'] ?: 'Pemilik Toko',
                'business_type' => $validated['business_type'] ?: 'Toko Kelontong',
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'onboarding_completed_at' => now(),
            ]);
            $store->save();

            $store->categories()->delete();
            $store->products()->delete();
            $store->sales()->delete();
            $store->inventoryMovements()->delete();

            $savedProducts = $products->map(function (array $productData) use ($store) {
                $category = Category::query()->firstOrCreate(
                    [
                        'store_id' => $store->id,
                        'slug' => Str::slug($productData['category'] ?: 'umum'),
                    ],
                    [
                        'name' => $productData['category'] ?: 'Umum',
                    ]
                );

                $stock = (float) ($productData['stock'] ?? 0);
                $product = Product::query()->create([
                    'store_id' => $store->id,
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'sku' => $this->generateSku($productData['name']),
                    'unit' => $productData['unit'] ?: 'pcs',
                    'price' => (float) ($productData['price'] ?? 0),
                    'stock_quantity' => $stock,
                    'is_active' => true,
                ]);

                InventoryMovement::query()->create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'type' => 'initial',
                    'quantity_change' => $stock,
                    'stock_before' => 0,
                    'stock_after' => $stock,
                    'notes' => 'Stok awal dari proses onboarding',
                    'happened_at' => now(),
                ]);

                return $product;
            });

            $this->seedDemoSales($store, $savedProducts);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Onboarding selesai. Dashboard toko Anda sekarang aktif.');
    }

    private function seedDemoSales(Store $store, $products): void
    {
        $days = collect(range(0, 5));

        $days->each(function (int $offset) use ($store, $products): void {
            $soldAt = Carbon::now()->subDays(5 - $offset)->setTime(9 + $offset, 15);
            $sale = Sale::query()->create([
                'store_id' => $store->id,
                'invoice_number' => sprintf('INV-%s-%03d', $soldAt->format('ymd'), $offset + 1),
                'customer_name' => $offset % 2 === 0 ? 'Pelanggan Tetap' : 'Pembeli Toko',
                'sold_at' => $soldAt,
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
            ]);

            $subtotal = 0;

            $products->take(min(2, $products->count()))->each(function (Product $product, int $index) use ($sale, $soldAt, &$subtotal): void {
                $quantity = (float) (2 + $index + ($soldAt->day % 3));
                $lineTotal = $quantity * (float) $product->price;
                $subtotal += $lineTotal;

                SaleItem::query()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ]);
            });

            $sale->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        });
    }

    private function generateSku(string $name): string
    {
        $prefix = Str::upper(Str::substr(Str::slug($name, ''), 0, 3));

        return $prefix.'-'.Str::upper(Str::random(4));
    }
}
