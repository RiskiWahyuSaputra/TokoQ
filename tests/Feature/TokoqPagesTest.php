<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokoqPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_pages_redirect_to_onboarding_when_store_is_not_ready(): void
    {
        $pages = [
            '/dashboard',
            '/kasir',
            '/inventaris',
            '/analisis-penjualan',
            '/laporan',
            '/prediksi-ai',
        ];

        foreach ($pages as $page) {
            $this->get($page)
                ->assertRedirect('/onboarding');
        }
    }

    public function test_store_can_finish_onboarding_and_access_core_pages(): void
    {
        $response = $this->post('/onboarding', [
            'store_name' => 'Warung TokoQ',
            'owner_name' => 'Bu Sari',
            'owner_role' => 'Pemilik Toko',
            'business_type' => 'Toko Kelontong',
            'phone' => '08123456789',
            'address' => 'Jl. Mawar No. 12',
            'products' => [
                ['name' => 'Beras Premium', 'category' => 'Sembako', 'price' => 14500, 'stock' => 20, 'unit' => 'kg'],
                ['name' => 'Teh Botol', 'category' => 'Minuman', 'price' => 5000, 'stock' => 10, 'unit' => 'botol'],
            ],
        ]);

        $response->assertRedirect('/dashboard');

        foreach (['/dashboard', '/kasir', '/inventaris', '/analisis-penjualan', '/laporan', '/prediksi-ai'] as $page) {
            $this->get($page)->assertOk();
        }
    }
}
