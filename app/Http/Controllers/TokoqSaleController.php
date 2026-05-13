<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Store;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TokoqSaleController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:120'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $quantities = collect($validated['items'])
            ->map(fn ($quantity) => (float) $quantity)
            ->filter(fn (float $quantity) => $quantity > 0);

        if ($quantities->isEmpty()) {
            return back()
                ->withErrors(['items' => 'Pilih minimal satu produk dengan kuantitas lebih dari 0.'])
                ->withInput();
        }

        $store = Store::current();
        $discount = (float) ($validated['discount'] ?? 0);

        DB::transaction(function () use ($store, $validated, $quantities, $discount): void {
            $products = Product::query()
                ->where('store_id', $store->id)
                ->whereIn('id', $quantities->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = 0;
            $itemsPayload = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    continue;
                }

                if ((float) $product->stock_quantity < $quantity) {
                    abort(422, 'Stok produk '.$product->name.' tidak mencukupi.');
                }

                $lineTotal = $quantity * (float) $product->price;
                $subtotal += $lineTotal;

                $itemsPayload[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }

            if ($subtotal <= 0) {
                abort(422, 'Transaksi tidak valid.');
            }

            $sale = Sale::query()->create([
                'store_id' => $store->id,
                'invoice_number' => sprintf('INV-%s-%03d', now()->format('ymd'), Sale::query()->count() + 1),
                'customer_name' => $validated['customer_name'] ?? null,
                'sold_at' => now(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => max(0, $subtotal - $discount),
                'notes' => 'Checkout dari halaman kasir',
            ]);

            foreach ($itemsPayload as $item) {
                /** @var Product $product */
                $product = $item['product'];
                $before = (float) $product->stock_quantity;
                $after = $before - $item['quantity'];

                SaleItem::query()->create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'line_total' => $item['line_total'],
                ]);

                $product->update([
                    'stock_quantity' => $after,
                ]);

                InventoryMovement::query()->create([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity_change' => -1 * $item['quantity'],
                    'stock_before' => $before,
                    'stock_after' => $after,
                    'notes' => 'Terjual lewat kasir: '.$sale->invoice_number,
                    'happened_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('pos')
            ->with('success', 'Transaksi berhasil disimpan dan stok sudah diperbarui.');
    }
}
