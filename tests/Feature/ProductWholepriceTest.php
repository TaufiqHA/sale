<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductWholeprice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWholepriceTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_wholeprice_can_be_created_using_factory(): void
    {
        $product = Product::factory()->create();

        $wholeprice = ProductWholeprice::factory()->create([
            'product_id' => $product->id,
            'minimum_qty' => 10,
            'wholeprice_price' => 25.50,
        ]);

        $this->assertDatabaseHas('product_wholeprices', [
            'id' => $wholeprice->id,
            'product_id' => $product->id,
            'minimum_qty' => 10,
            'wholeprice_price' => 25.50,
        ]);
    }

    public function test_product_wholeprice_belongs_to_product(): void
    {
        $wholeprice = ProductWholeprice::factory()->create();

        $this->assertInstanceOf(Product::class, $wholeprice->product);
    }

    public function test_product_has_many_wholeprices(): void
    {
        $product = Product::factory()->create();
        $wholeprices = ProductWholeprice::factory()->count(3)->create([
            'product_id' => $product->id,
        ]);

        $this->assertCount(3, $product->wholeprices);
        $this->assertInstanceOf(ProductWholeprice::class, $product->wholeprices->first());
    }

    public function test_unauthenticated_user_cannot_access_product_wholeprices(): void
    {
        $this->getJson('/product-wholeprices/1')->assertStatus(401);
        $this->postJson('/product-wholeprices', [])->assertStatus(401);
        $this->putJson('/product-wholeprices/1', [])->assertStatus(401);
        $this->deleteJson('/product-wholeprices/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_product_wholeprice(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson('/product-wholeprices', [
            'product_id' => $product->id,
            'minimum_qty' => 5,
            'wholeprice_price' => 12500.00,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('minimum_qty', 5)
            ->assertJsonPath('wholeprice_price', '12500.00');

        $this->assertDatabaseHas('product_wholeprices', [
            'product_id' => $product->id,
            'minimum_qty' => 5,
            'wholeprice_price' => 12500.00,
        ]);
    }

    public function test_authenticated_user_can_show_product_wholeprice(): void
    {
        $user = User::factory()->create();
        $wholeprice = ProductWholeprice::factory()->create();

        $response = $this->actingAs($user)->getJson('/product-wholeprices/'.$wholeprice->id);

        $response->assertStatus(200)
            ->assertJsonPath('id', $wholeprice->id);
    }

    public function test_authenticated_user_can_update_product_wholeprice(): void
    {
        $user = User::factory()->create();
        $wholeprice = ProductWholeprice::factory()->create([
            'minimum_qty' => 5,
            'wholeprice_price' => 10000.00,
        ]);

        $response = $this->actingAs($user)->putJson('/product-wholeprices/'.$wholeprice->id, [
            'product_id' => $wholeprice->product_id,
            'minimum_qty' => 10,
            'wholeprice_price' => 9000.00,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('minimum_qty', 10)
            ->assertJsonPath('wholeprice_price', '9000.00');

        $this->assertDatabaseHas('product_wholeprices', [
            'id' => $wholeprice->id,
            'minimum_qty' => 10,
            'wholeprice_price' => 9000.00,
        ]);
    }

    public function test_authenticated_user_can_delete_product_wholeprice(): void
    {
        $user = User::factory()->create();
        $wholeprice = ProductWholeprice::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/product-wholeprices/'.$wholeprice->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('product_wholeprices', [
            'id' => $wholeprice->id,
        ]);
    }
}
