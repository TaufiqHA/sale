<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Counter;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user cannot access dashboard page.
     */
    public function test_unauthenticated_user_cannot_access_dashboard_page(): void
    {
        $response = $this->get('/administrator/dashboard');
        $response->assertRedirect(route('login'));
    }

    /**
     * Test unauthenticated user cannot access dashboard stats endpoint.
     */
    public function test_unauthenticated_user_cannot_access_dashboard_stats(): void
    {
        $response = $this->getJson('/administrator/dashboard/stats');
        $response->assertStatus(401);
    }

    /**
     * Test a user with role 'admin' cannot access the dashboard or stats.
     */
    public function test_admin_role_cannot_access_dashboard_and_stats(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/administrator/dashboard');
        $response->assertStatus(403);

        $responseStats = $this->actingAs($user)->getJson('/administrator/dashboard/stats');
        $responseStats->assertStatus(403);
    }

    /**
     * Test administrator can access dashboard page.
     */
    public function test_administrator_can_access_dashboard_page(): void
    {
        $user = User::factory()->create([
            'role' => 'administrator',
        ]);

        $response = $this->actingAs($user)->get('/administrator/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('counters');
        $response->assertViewHas('categories');
    }

    /**
     * Test administrator can retrieve dynamic stats with filters.
     */
    public function test_administrator_can_retrieve_dashboard_stats_with_correct_computations(): void
    {
        $user = User::factory()->create([
            'role' => 'administrator',
        ]);

        // Create counters
        $counterA = Counter::factory()->create(['name' => 'Counter A']);
        $counterB = Counter::factory()->create(['name' => 'Counter B']);

        // Create categories
        $categoryA = Category::factory()->create(['name' => 'Category A']);
        $categoryB = Category::factory()->create(['name' => 'Category B']);

        // Create products
        $productA = Product::factory()->create([
            'counter_id' => $counterA->id,
            'category_id' => $categoryA->id,
            'buy_price' => 10000,
            'sell_price' => 15000,
        ]);

        $productB = Product::factory()->create([
            'counter_id' => $counterB->id,
            'category_id' => $categoryB->id,
            'buy_price' => 20000,
            'sell_price' => 30000,
        ]);

        // Create a Sale for Counter A
        $sale1 = Sale::factory()->create([
            'counter_id' => $counterA->id,
            'date' => now()->format('Y-m-d H:i:s'),
            'subtotal' => 30000,
            'discount' => 3000, // 10% discount
            'shipping_cost' => 5000,
            'grand_total' => 32000,
        ]);

        // Add 2 items of product A (subtotal 30,000)
        SaleItem::factory()->create([
            'sale_id' => $sale1->id,
            'product_id' => $productA->id,
            'qty' => 2,
            'price' => 15000,
            'subtotal' => 30000,
        ]);

        // Create a Sale for Counter B
        $sale2 = Sale::factory()->create([
            'counter_id' => $counterB->id,
            'date' => now()->format('Y-m-d H:i:s'),
            'subtotal' => 60000,
            'discount' => 0,
            'shipping_cost' => 0,
            'grand_total' => 60000,
        ]);

        // Add 2 items of product B (subtotal 60,000)
        SaleItem::factory()->create([
            'sale_id' => $sale2->id,
            'product_id' => $productB->id,
            'qty' => 2,
            'price' => 30000,
            'subtotal' => 60000,
        ]);

        // Request stats without filters (returns combined values)
        $responseAll = $this->actingAs($user)->getJson('/administrator/dashboard/stats');
        $responseAll->assertStatus(200);

        // Expected totals:
        // Total Omset:
        // sale 1: 30000 - 3000 = 27000
        // sale 2: 60000 - 0 = 60000
        // Combined Omset = 87000
        // Total Qty = 2 + 2 = 4
        // Total Keuntungan (Estimasi):
        // sale 1 item: Net Omset (27000) - Cost (2 * 10000 = 20000) = 7000
        // sale 2 item: Net Omset (60000) - Cost (2 * 20000 = 40000) = 20000
        // Combined Keuntungan = 27000
        $responseAll->assertJsonPath('summary.total_omset', 87000);
        $responseAll->assertJsonPath('summary.total_keuntungan', 27000);
        $responseAll->assertJsonPath('summary.total_qty', 4);

        // Request stats filtered by Counter A
        $responseCounterA = $this->actingAs($user)->getJson('/administrator/dashboard/stats?counter_id='.$counterA->id);
        $responseCounterA->assertStatus(200);
        $responseCounterA->assertJsonPath('summary.total_omset', 27000);
        $responseCounterA->assertJsonPath('summary.total_keuntungan', 7000);
        $responseCounterA->assertJsonPath('summary.total_qty', 2);

        // Request stats filtered by Category B
        $responseCategoryB = $this->actingAs($user)->getJson('/administrator/dashboard/stats?category_id='.$categoryB->id);
        $responseCategoryB->assertStatus(200);
        $responseCategoryB->assertJsonPath('summary.total_omset', 60000);
        $responseCategoryB->assertJsonPath('summary.total_keuntungan', 20000);
        $responseCategoryB->assertJsonPath('summary.total_qty', 2);
    }
}
