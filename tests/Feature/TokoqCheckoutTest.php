<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoqCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_sale_and_reduces_stock(): void
    {
        $this->post('/onboarding', [
            'store_name' => 'Warung TokoQ',
            'owner_name' => 'Bu Sari',
            'owner_role' => 'Pemilik Toko',
            'business_type' => 'Toko Kelontong',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 12',
            'products' => [
                ['name' => 'Beras Premium', 'category' => 'Sembako', 'price' => 14500, 'stock' => 20, 'unit' => 'kg'],
            ],
        ]);

        $product = Product::query()->firstOrFail();

        $response = $this->post('/kasir/checkout', [
            'customer_name' => 'Pelanggan A',
            'discount' => 0,
            'items' => [
                $product->id => 2,
            ],
        ]);

        $response->assertRedirect('/kasir');

        $this->assertDatabaseCount('sales', Sale::query()->count());
        $this->assertDatabaseHas('sale_items', [
            'product_id' => $product->id,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 18,
        ]);
    }
}
