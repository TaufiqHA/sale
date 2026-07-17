<?php

namespace Tests\Feature;

use App\Models\Production;
use App\Models\ProductionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_production_item_can_be_created_using_factory(): void
    {
        $productionItem = ProductionItem::factory()->create([
            'description' => 'Test item description',
            'unit_price' => 150.50,
            'qty' => 10.50,
            'total' => 1580.25,
        ]);

        $this->assertDatabaseHas('production_items', [
            'id' => $productionItem->id,
            'description' => 'Test item description',
            'unit_price' => 150.50,
            'qty' => 10.50,
            'total' => 1580.25,
        ]);
    }

    public function test_production_item_belongs_to_production(): void
    {
        $production = Production::factory()->create();

        $productionItem = ProductionItem::factory()->create([
            'production_id' => $production->id,
        ]);

        $this->assertInstanceOf(Production::class, $productionItem->production);
        $this->assertEquals($production->id, $productionItem->production->id);
    }

    public function test_production_has_many_production_items(): void
    {
        $production = Production::factory()->create();
        $productionItems = ProductionItem::factory()->count(3)->create([
            'production_id' => $production->id,
        ]);

        $this->assertCount(3, $production->productionItems);
        $this->assertTrue($production->productionItems->contains($productionItems[0]));
        $this->assertTrue($production->productionItems->contains($productionItems[1]));
        $this->assertTrue($production->productionItems->contains($productionItems[2]));
    }

    public function test_unauthenticated_user_cannot_access_production_items_endpoints(): void
    {
        $this->getJson('/production-items/1')->assertStatus(401);
        $this->postJson('/production-items', [])->assertStatus(401);
        $this->putJson('/production-items/1', [])->assertStatus(401);
        $this->deleteJson('/production-items/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_production_item(): void
    {
        $user = User::factory()->create();
        $production = Production::factory()->create();

        $response = $this->actingAs($user)->postJson('/production-items', [
            'production_id' => $production->id,
            'description' => 'Raw material X',
            'unit_price' => 200.00,
            'qty' => 5.5,
            'total' => 1100.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'description' => 'Raw material X',
                'unit_price' => '200.00',
                'qty' => '5.50',
                'total' => '1100.00',
            ]);

        $this->assertDatabaseHas('production_items', [
            'production_id' => $production->id,
            'description' => 'Raw material X',
            'unit_price' => 200.00,
            'qty' => 5.50,
            'total' => 1100.00,
        ]);
    }

    public function test_authenticated_user_can_show_production_item(): void
    {
        $user = User::factory()->create();
        $productionItem = ProductionItem::factory()->create();

        $response = $this->actingAs($user)->getJson('/production-items/'.$productionItem->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $productionItem->id,
                'description' => $productionItem->description,
            ]);
    }

    public function test_authenticated_user_can_update_production_item(): void
    {
        $user = User::factory()->create();
        $productionItem = ProductionItem::factory()->create();
        $production = Production::factory()->create();

        $response = $this->actingAs($user)->putJson('/production-items/'.$productionItem->id, [
            'production_id' => $production->id,
            'description' => 'Updated raw material',
            'unit_price' => 300.00,
            'qty' => 10.00,
            'total' => 3000.00,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'description' => 'Updated raw material',
                'unit_price' => '300.00',
                'qty' => '10.00',
                'total' => '3000.00',
            ]);

        $this->assertDatabaseHas('production_items', [
            'id' => $productionItem->id,
            'description' => 'Updated raw material',
            'unit_price' => 300.00,
            'qty' => 10.00,
            'total' => 3000.00,
        ]);
    }

    public function test_authenticated_user_can_delete_production_item(): void
    {
        $user = User::factory()->create();
        $productionItem = ProductionItem::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/production-items/'.$productionItem->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('production_items', [
            'id' => $productionItem->id,
        ]);
    }
}
