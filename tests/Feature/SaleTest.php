<?php

namespace Tests\Feature;

use App\Models\Counter;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\Expedition;
use App\Models\Marketplace;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_can_be_created_using_factory(): void
    {
        $sale = Sale::factory()->create([
            'subtotal' => 15000.50,
            'discount' => 1000.00,
            'shipping_cost' => 500.00,
            'grand_total' => 14500.50,
        ]);

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'subtotal' => 15000.50,
            'discount' => 1000.00,
            'shipping_cost' => 500.00,
            'grand_total' => 14500.50,
        ]);

        $this->assertInstanceOf(Carbon::class, $sale->date);
    }

    public function test_sale_relations_can_be_resolved(): void
    {
        $counter = Counter::factory()->create();
        $customer = Customer::factory()->create(['counter_id' => $counter->id]);
        $expedition = Expedition::factory()->create();
        $marketplace = Marketplace::factory()->create();
        $courier = Courier::factory()->create();

        $sale = Sale::factory()->create([
            'counter_id' => $counter->id,
            'customer_id' => $customer->id,
            'expedition_id' => $expedition->id,
            'marketplace_id' => $marketplace->id,
            'courier_id' => $courier->id,
        ]);

        $this->assertEquals($counter->id, $sale->counter->id);
        $this->assertEquals($customer->id, $sale->customer->id);
        $this->assertEquals($expedition->id, $sale->expedition->id);
        $this->assertEquals($marketplace->id, $sale->marketplace->id);
        $this->assertEquals($courier->id, $sale->courier->id);
    }

    public function test_unauthenticated_user_cannot_access_sales_endpoints(): void
    {
        $this->getJson('/sales')->assertStatus(401);
        $this->postJson('/sales', [])->assertStatus(401);
        $this->getJson('/sales/1')->assertStatus(401);
        $this->putJson('/sales/1', [])->assertStatus(401);
        $this->deleteJson('/sales/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_view_sales_index_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/sales');

        $response->assertStatus(200)
            ->assertViewIs('administrator.sale');
    }

    public function test_authenticated_user_can_list_sales_as_json(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();

        $response = $this->actingAs($user)->getJson('/sales');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $sale->id,
                'barcode' => $sale->barcode,
            ]);
    }

    public function test_authenticated_user_can_create_sale(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->postJson('/sales', [
            'counter_id' => $counter->id,
            'barcode' => '1234567890',
            'type' => 'umum',
            'date' => now()->toDateTimeString(),
            'subtotal' => 20000.00,
            'discount' => 1000.00,
            'shipping_cost' => 500.00,
            'grand_total' => 19500.00,
            'payment_method' => 'transfer',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'barcode' => '1234567890',
                'subtotal' => '20000.00',
                'discount' => '1000.00',
                'shipping_cost' => '500.00',
                'grand_total' => '19500.00',
                'payment_method' => 'transfer',
            ]);

        $this->assertDatabaseHas('sales', [
            'barcode' => '1234567890',
            'subtotal' => 20000.00,
        ]);
    }

    public function test_create_sale_validation_errors(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/sales', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'counter_id',
                'barcode',
                'type',
                'date',
                'subtotal',
                'grand_total',
                'payment_method',
            ]);
    }

    public function test_authenticated_user_can_show_sale(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();

        $response = $this->actingAs($user)->getJson('/sales/'.$sale->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $sale->id,
                'barcode' => $sale->barcode,
            ]);
    }

    public function test_authenticated_user_can_update_sale(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->putJson('/sales/'.$sale->id, [
            'counter_id' => $counter->id,
            'barcode' => '987654321',
            'type' => 'marketplace',
            'date' => now()->toDateTimeString(),
            'subtotal' => 50000.00,
            'discount' => 5000.00,
            'shipping_cost' => 1500.00,
            'grand_total' => 46500.00,
            'payment_method' => 'tunai',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'barcode' => '987654321',
                'type' => 'marketplace',
                'subtotal' => '50000.00',
                'discount' => '5000.00',
                'shipping_cost' => '1500.00',
                'grand_total' => '46500.00',
                'payment_method' => 'tunai',
            ]);

        $this->assertDatabaseHas('sales', [
            'id' => $sale->id,
            'barcode' => '987654321',
        ]);
    }

    public function test_authenticated_user_can_delete_sale(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/sales/'.$sale->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('sales', [
            'id' => $sale->id,
        ]);
    }

    public function test_sale_creation_decrements_product_stock(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->postJson('/sales', [
            'counter_id' => $counter->id,
            'barcode' => '1234567890',
            'type' => 'umum',
            'date' => now()->toDateTimeString(),
            'subtotal' => 20000.00,
            'discount' => 1000.00,
            'shipping_cost' => 500.00,
            'grand_total' => 19500.00,
            'payment_method' => 'transfer',
            'items' => [
                [
                    'product_id' => $product->id,
                    'qty' => 3,
                    'price' => 10000.00,
                    'subtotal' => 30000.00,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_sale_update_adjusts_product_stock(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();

        $product1 = Product::factory()->create(['stock' => 10]);
        $product2 = Product::factory()->create(['stock' => 10]);

        $sale = Sale::factory()->create([
            'counter_id' => $counter->id,
        ]);

        $saleItem = SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product1->id,
            'qty' => 3,
        ]);
        $product1->update(['stock' => 7]);

        $response = $this->actingAs($user)->putJson('/sales/'.$sale->id, [
            'counter_id' => $counter->id,
            'barcode' => $sale->barcode,
            'type' => $sale->type,
            'date' => $sale->date->toDateTimeString(),
            'subtotal' => 20000.00,
            'grand_total' => 20000.00,
            'payment_method' => 'tunai',
            'items' => [
                [
                    'product_id' => $product1->id,
                    'qty' => 2,
                    'price' => 10000.00,
                    'subtotal' => 20000.00,
                ],
                [
                    'product_id' => $product2->id,
                    'qty' => 4,
                    'price' => 5000.00,
                    'subtotal' => 20000.00,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $this->assertEquals(8, $product1->fresh()->stock);
        $this->assertEquals(6, $product2->fresh()->stock);
    }

    public function test_sale_deletion_restores_product_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 7]);
        $sale = Sale::factory()->create();
        $saleItem = SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 3,
        ]);

        $response = $this->actingAs($user)->deleteJson('/sales/'.$sale->id);

        $response->assertStatus(204);
        $this->assertEquals(10, $product->fresh()->stock);
    }
}
