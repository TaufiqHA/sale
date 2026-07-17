<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMonitorTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_stock_monitor_page(): void
    {
        $this->get('/administrator/stock-monitor')
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    public function test_authenticated_user_can_view_stock_monitor_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/administrator/stock-monitor');

        $response->assertStatus(200)
            ->assertViewIs('administrator.stock-monitor');
    }

    public function test_authenticated_user_can_get_stock_monitor_data_as_json(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)->getJson('/administrator/stock-monitor');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $product->id,
                'name' => $product->name,
                'stock' => 10,
            ]);
    }
}
