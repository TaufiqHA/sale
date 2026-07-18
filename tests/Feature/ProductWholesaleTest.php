<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductWholesale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWholesaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_wholesale_can_be_created_using_factory(): void
    {
        $product = Product::factory()->create();

        $wholesale = ProductWholesale::factory()->create([
            'product_id' => $product->id,
            'minimum_qty' => 10,
            'wholesale_price' => 25.50,
        ]);

        $this->assertDatabaseHas('product_wholesales', [
            'id' => $wholesale->id,
            'product_id' => $product->id,
            'minimum_qty' => 10,
            'wholesale_price' => 25.50,
        ]);
    }

    public function test_product_wholesale_belongs_to_product(): void
    {
        $wholesale = ProductWholesale::factory()->create();

        $this->assertInstanceOf(Product::class, $wholesale->product);
    }

    public function test_product_has_many_wholesales(): void
    {
        $product = Product::factory()->create();
        $wholesales = ProductWholesale::factory()->count(3)->create([
            'product_id' => $product->id,
        ]);

        $this->assertCount(3, $product->wholesales);
        $this->assertInstanceOf(ProductWholesale::class, $product->wholesales->first());
    }

    public function test_unauthenticated_user_cannot_access_product_wholesales(): void
    {
        $this->getJson('/product-wholesales/1')->assertStatus(401);
        $this->postJson('/product-wholesales', [])->assertStatus(401);
        $this->putJson('/product-wholesales/1', [])->assertStatus(401);
        $this->deleteJson('/product-wholesales/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_product_wholesale(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/product-wholesales', [
            'product_id' => $product->id,
            'minimum_qty' => 5,
            'wholesale_price' => 12500.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('minimum_qty', 5)
            ->assertJsonPath('wholesale_price', '12500.00');

        $this->assertDatabaseHas('product_wholesales', [
            'product_id' => $product->id,
            'minimum_qty' => 5,
            'wholesale_price' => 12500.00,
        ]);
    }

    public function test_authenticated_user_can_show_product_wholesale(): void
    {
        $user = User::factory()->create();
        $wholesale = ProductWholesale::factory()->create();

        $response = $this->actingAs($user)->getJson('/product-wholesales/'.$wholesale->id);

        $response->assertStatus(200)
            ->assertJsonPath('id', $wholesale->id);
    }

    public function test_authenticated_user_can_update_product_wholesale(): void
    {
        $user = User::factory()->create();
        $wholesale = ProductWholesale::factory()->create([
            'minimum_qty' => 5,
            'wholesale_price' => 10000.00,
        ]);

        $response = $this->actingAs($user)->putJson('/product-wholesales/'.$wholesale->id, [
            'product_id' => $wholesale->product_id,
            'minimum_qty' => 10,
            'wholesale_price' => 9000.00,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('minimum_qty', 10)
            ->assertJsonPath('wholesale_price', '9000.00');

        $this->assertDatabaseHas('product_wholesales', [
            'id' => $wholesale->id,
            'minimum_qty' => 10,
            'wholesale_price' => 9000.00,
        ]);
    }

    public function test_authenticated_user_can_delete_product_wholesale(): void
    {
        $user = User::factory()->create();
        $wholesale = ProductWholesale::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/product-wholesales/'.$wholesale->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('product_wholesales', [
            'id' => $wholesale->id,
        ]);
    }
}
