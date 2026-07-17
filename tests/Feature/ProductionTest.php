<?php

namespace Tests\Feature;

use App\Models\Counter;
use App\Models\Product;
use App\Models\Production;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_can_be_created_using_factory(): void
    {
        $counter = Counter::factory()->create(['name' => 'Counter A']);
        $product = Product::factory()->create(['name' => 'Product B']);

        $production = Production::factory()->create([
            'counter_id' => $counter->id,
            'product_id' => $product->id,
            'production_date' => '2026-07-17',
            'total_cost' => 150.50,
            'total_result' => 10,
            'hpp' => 15.05,
            'selling_price' => 20.00,
            'estimated_profit' => 4.95,
            'notes' => 'First batch',
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('productions', [
            'id' => $production->id,
            'counter_id' => $counter->id,
            'product_id' => $product->id,
            'production_date' => '2026-07-17 00:00:00',
            'total_cost' => 150.50,
            'total_result' => 10,
            'hpp' => 15.05,
            'selling_price' => 20.00,
            'estimated_profit' => 4.95,
            'notes' => 'First batch',
            'status' => 'draft',
        ]);
    }

    public function test_production_belongs_to_counter_and_product(): void
    {
        $production = Production::factory()->create();

        $this->assertInstanceOf(Counter::class, $production->counter);
        $this->assertInstanceOf(Product::class, $production->product);
    }

    public function test_production_has_default_status(): void
    {
        $production = new Production;
        $this->assertEquals('draft', $production->status);
    }

    public function test_unauthenticated_user_cannot_access_productions(): void
    {
        $this->getJson('/productions')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_productions(): void
    {
        $user = User::factory()->create();
        Production::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/productions');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_view_productions_index_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/productions');

        $response->assertStatus(200)
            ->assertViewIs('administrator.production');
    }

    public function test_authenticated_user_can_create_production(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);

        $response = $this->actingAs($user)->postJson('/productions', [
            'counter_id' => $counter->id,
            'product_id' => $product->id,
            'production_date' => '2026-07-17',
            'total_cost' => 200.00,
            'total_result' => 20,
            'hpp' => 10.00,
            'selling_price' => 15.00,
            'estimated_profit' => 5.00,
            'notes' => 'Standard batch',
            'status' => 'completed',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'status' => 'completed',
                'notes' => 'Standard batch',
            ]);

        $this->assertDatabaseHas('productions', [
            'notes' => 'Standard batch',
            'status' => 'completed',
        ]);

        $this->assertEquals(25, $product->fresh()->stock);
    }

    public function test_authenticated_user_can_show_production(): void
    {
        $user = User::factory()->create();
        $production = Production::factory()->create();

        $response = $this->actingAs($user)->getJson('/productions/'.$production->id);

        $response->assertStatus(200)
            ->assertJsonPath('notes', $production->notes);
    }

    public function test_authenticated_user_can_update_production(): void
    {
        $user = User::factory()->create();
        $oldProduct = Product::factory()->create(['stock' => 20]);
        $newProduct = Product::factory()->create(['stock' => 5]);
        $production = Production::factory()->create([
            'product_id' => $oldProduct->id,
            'total_result' => 10,
            'status' => 'completed',
        ]);

        // After creation (simulated by factory + manual stock adjustment), oldProduct stock is 20
        $this->assertEquals(20, $oldProduct->fresh()->stock);

        $newCounter = Counter::factory()->create();

        $response = $this->actingAs($user)->putJson('/productions/'.$production->id, [
            'counter_id' => $newCounter->id,
            'product_id' => $newProduct->id,
            'production_date' => '2026-07-18',
            'total_cost' => 300.00,
            'total_result' => 30,
            'hpp' => 10.00,
            'selling_price' => 12.00,
            'estimated_profit' => 2.00,
            'notes' => 'Updated notes',
            'status' => 'completed',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'notes' => 'Updated notes',
                'status' => 'completed',
            ]);

        $this->assertDatabaseHas('productions', [
            'id' => $production->id,
            'notes' => 'Updated notes',
            'status' => 'completed',
        ]);

        // After update, oldProduct stock reverts to 10, newProduct increases to 5 + 30 = 35
        $this->assertEquals(10, $oldProduct->fresh()->stock);
        $this->assertEquals(35, $newProduct->fresh()->stock);
    }

    public function test_authenticated_user_can_delete_production(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 20]);
        $production = Production::factory()->create([
            'product_id' => $product->id,
            'total_result' => 10,
            'status' => 'completed',
        ]);

        // Before delete (simulated by factory + manual stock adjustment): stock = 20
        $this->assertEquals(20, $product->fresh()->stock);

        $response = $this->actingAs($user)->deleteJson('/productions/'.$production->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('productions', [
            'id' => $production->id,
        ]);

        // After delete: stock reverts to 10
        $this->assertEquals(10, $product->fresh()->stock);
    }
}
