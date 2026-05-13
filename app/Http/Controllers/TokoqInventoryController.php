<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TokoqInventoryController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:120'],
            'unit' => ['required', 'string', 'max:30'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'numeric', 'min:0'],
        ]);

        $store = Store::current();

        DB::transaction(function () use ($validated, $store): void {
            $category = Category::query()->firstOrCreate(
                [
                    'store_id' => $store->id,
                    'slug' => Str::slug($validated['category']),
                ],
                [
                    'name' => $validated['category'],
                ]
            );

            $product = Product::query()->create([
                'store_id' => $store->id,
                'category_id' => $category->id,
                'name' => $validated['name'],
                'sku' => Str::upper(Str::substr(Str::slug($validated['name'], ''), 0, 3)).'-'.Str::upper(Str::random(4)),
                'unit' => $validated['unit'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => true,
            ]);

            InventoryMovement::query()->create([
                'store_id' => $store->id,
                'product_id' => $product->id,
                'type' => 'restock',
                'quantity_change' => $validated['stock_quantity'],
                'stock_before' => 0,
                'stock_after' => $validated['stock_quantity'],
                'notes' => 'Produk baru ditambahkan dari halaman inventori',
                'happened_at' => now(),
            ]);
        });

        return redirect()
            ->route('inventory')
            ->with('success', 'Produk baru berhasil ditambahkan ke inventori.');
    }
}
