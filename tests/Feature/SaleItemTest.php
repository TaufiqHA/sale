<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_sale_item_can_be_created_using_factory(): void
    {
        $saleItem = SaleItem::factory()->create([
            'qty' => 5,
            'price' => 1000.50,
            'subtotal' => 5002.50,
        ]);

        $this->assertDatabaseHas('sale_items', [
            'id' => $saleItem->id,
            'qty' => 5,
            'price' => 1000.50,
            'subtotal' => 5002.50,
        ]);
    }

    public function test_sale_item_belongs_to_sale_and_product(): void
    {
        $sale = Sale::factory()->create();
        $product = Product::factory()->create();

        $saleItem = SaleItem::factory()->create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
        ]);

        $this->assertInstanceOf(Sale::class, $saleItem->sale);
        $this->assertInstanceOf(Product::class, $saleItem->product);
        $this->assertEquals($sale->id, $saleItem->sale->id);
        $this->assertEquals($product->id, $saleItem->product->id);
    }

    public function test_sale_has_many_sale_items(): void
    {
        $sale = Sale::factory()->create();
        $saleItems = SaleItem::factory()->count(3)->create([
            'sale_id' => $sale->id,
        ]);

        $this->assertCount(3, $sale->items);
        $this->assertTrue($sale->items->contains($saleItems[0]));
        $this->assertTrue($sale->items->contains($saleItems[1]));
        $this->assertTrue($sale->items->contains($saleItems[2]));
    }

    public function test_unauthenticated_user_cannot_access_sale_items_endpoints(): void
    {
        $this->getJson('/sale-items/1')->assertStatus(401);
        $this->postJson('/sale-items', [])->assertStatus(401);
        $this->putJson('/sale-items/1', [])->assertStatus(401);
        $this->deleteJson('/sale-items/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_sale_item(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/sale-items', [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 3,
            'price' => 12000.00,
            'subtotal' => 36000.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'qty' => 3,
                'price' => '12000.00',
                'subtotal' => '36000.00',
            ]);

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 3,
            'price' => 12000.00,
            'subtotal' => 36000.00,
        ]);
    }

    public function test_authenticated_user_can_show_sale_item(): void
    {
        $user = User::factory()->create();
        $saleItem = SaleItem::factory()->create();

        $response = $this->actingAs($user)->getJson('/sale-items/'.$saleItem->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $saleItem->id,
                'qty' => $saleItem->qty,
            ]);
    }

    public function test_authenticated_user_can_update_sale_item(): void
    {
        $user = User::factory()->create();
        $saleItem = SaleItem::factory()->create();
        $sale = Sale::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->putJson('/sale-items/'.$saleItem->id, [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'qty' => 10,
            'price' => 15000.50,
            'subtotal' => 150005.00,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'qty' => 10,
                'price' => '15000.50',
                'subtotal' => '150005.00',
            ]);

        $this->assertDatabaseHas('sale_items', [
            'id' => $saleItem->id,
            'qty' => 10,
            'price' => 15000.50,
            'subtotal' => 150005.00,
        ]);
    }

    public function test_authenticated_user_can_delete_sale_item(): void
    {
        $user = User::factory()->create();
        $saleItem = SaleItem::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/sale-items/'.$saleItem->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('sale_items', [
            'id' => $saleItem->id,
        ]);
    }
}
