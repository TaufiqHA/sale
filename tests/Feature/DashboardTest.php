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
        $response->assertViewHas('months');
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
            'date' => '2026-07-20 10:00:00',
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
            'date' => '2026-07-20 14:00:00',
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

        // Create a Sale for Counter A in previous month
        $sale3 = Sale::factory()->create([
            'counter_id' => $counterA->id,
            'date' => '2026-06-20 10:00:00',
            'subtotal' => 15000,
            'discount' => 0,
            'shipping_cost' => 0,
            'grand_total' => 15000,
        ]);

        SaleItem::factory()->create([
            'sale_id' => $sale3->id,
            'product_id' => $productA->id,
            'qty' => 1,
            'price' => 15000,
            'subtotal' => 15000,
        ]);

        // Request stats without filters (returns combined values for all 3 sales)
        $responseAll = $this->actingAs($user)->getJson('/administrator/dashboard/stats');
        $responseAll->assertStatus(200);

        // Expected totals:
        // Total Omset:
        // sale 1: 30000 - 3000 = 27000
        // sale 2: 60000 - 0 = 60000
        // sale 3: 15000 - 0 = 15000
        // Combined Omset = 102000
        // Total Qty = 2 + 2 + 1 = 5
        // Total Keuntungan (Estimasi):
        // sale 1 item: Net Omset (27000) - Cost (20000) = 7000
        // sale 2 item: Net Omset (60000) - Cost (40000) = 20000
        // sale 3 item: Net Omset (15000) - Cost (10000) = 5000
        // Combined Keuntungan = 32000
        $responseAll->assertJsonPath('summary.total_omset', 102000);
        $responseAll->assertJsonPath('summary.total_keuntungan', 32000);
        $responseAll->assertJsonPath('summary.total_qty', 5);

        // Request stats filtered by Counter A (includes sale 1 and sale 3)
        $responseCounterA = $this->actingAs($user)->getJson('/administrator/dashboard/stats?counter_id='.$counterA->id);
        $responseCounterA->assertStatus(200);
        $responseCounterA->assertJsonPath('summary.total_omset', 42000);
        $responseCounterA->assertJsonPath('summary.total_keuntungan', 12000);
        $responseCounterA->assertJsonPath('summary.total_qty', 3);

        // Request stats filtered by Category B (only sale 2)
        $responseCategoryB = $this->actingAs($user)->getJson('/administrator/dashboard/stats?category_id='.$categoryB->id);
        $responseCategoryB->assertStatus(200);
        $responseCategoryB->assertJsonPath('summary.total_omset', 60000);
        $responseCategoryB->assertJsonPath('summary.total_keuntungan', 20000);
        $responseCategoryB->assertJsonPath('summary.total_qty', 2);

        // Request stats filtered by specific date (only sale 1 and sale 2)
        $responseDate = $this->actingAs($user)->getJson('/administrator/dashboard/stats?date=2026-07-20');
        $responseDate->assertStatus(200);
        $responseDate->assertJsonPath('summary.total_omset', 87000);
        $responseDate->assertJsonPath('summary.total_keuntungan', 27000);
        $responseDate->assertJsonPath('summary.total_qty', 4);

        // Request stats filtered by another specific date (only sale 3)
        $responseDate3 = $this->actingAs($user)->getJson('/administrator/dashboard/stats?date=2026-06-20');
        $responseDate3->assertStatus(200);
        $responseDate3->assertJsonPath('summary.total_omset', 15000);
        $responseDate3->assertJsonPath('summary.total_keuntungan', 5000);
        $responseDate3->assertJsonPath('summary.total_qty', 1);

        // Request stats filtered by date range (Sale 1 and Sale 2)
        $responseRange1 = $this->actingAs($user)->getJson('/administrator/dashboard/stats?start_date=2026-07-01&end_date=2026-07-20');
        $responseRange1->assertStatus(200);
        $responseRange1->assertJsonPath('summary.total_omset', 87000);
        $responseRange1->assertJsonPath('summary.total_keuntungan', 27000);
        $responseRange1->assertJsonPath('summary.total_qty', 4);

        // Request stats filtered by date range (Sale 3 only)
        $responseRange2 = $this->actingAs($user)->getJson('/administrator/dashboard/stats?start_date=2026-06-01&end_date=2026-06-30');
        $responseRange2->assertStatus(200);
        $responseRange2->assertJsonPath('summary.total_omset', 15000);
        $responseRange2->assertJsonPath('summary.total_keuntungan', 5000);
        $responseRange2->assertJsonPath('summary.total_qty', 1);

        // Request stats filtered by full date range (Sale 1, 2, and 3)
        $responseRange3 = $this->actingAs($user)->getJson('/administrator/dashboard/stats?start_date=2026-06-01&end_date=2026-07-20');
        $responseRange3->assertStatus(200);
        $responseRange3->assertJsonPath('summary.total_omset', 102000);
        $responseRange3->assertJsonPath('summary.total_keuntungan', 32000);
        $responseRange3->assertJsonPath('summary.total_qty', 5);
    }
}
