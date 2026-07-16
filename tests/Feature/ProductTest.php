<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Counter;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created_using_factory(): void
    {
        $category = Category::factory()->create(['name' => 'Clothing']);
        $unit = Unit::factory()->create(['name' => 'Pieces', 'code' => 'PCS']);
        $counter = Counter::factory()->create(['name' => 'Counter A']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'counter_id' => $counter->id,
            'sku' => 'SKU-CLOTH-01',
            'barcode' => '1234567890123',
            'name' => 'Cool T-Shirt',
            'description' => 'A very cool cotton t-shirt.',
            'buy_price' => 15.50,
            'sell_price' => 29.99,
            'stock' => 25,
            'status' => true,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'counter_id' => $counter->id,
            'sku' => 'SKU-CLOTH-01',
            'barcode' => '1234567890123',
            'name' => 'Cool T-Shirt',
            'description' => 'A very cool cotton t-shirt.',
            'buy_price' => 15.50,
            'sell_price' => 29.99,
            'stock' => 25,
            'status' => true,
        ]);
    }

    public function test_product_belongs_to_category_and_unit(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertInstanceOf(Unit::class, $product->unit);
        $this->assertInstanceOf(Counter::class, $product->counter);
    }

    public function test_product_has_default_status(): void
    {
        $product = new Product;
        $this->assertTrue($product->status);
    }

    public function test_product_has_default_stock(): void
    {
        $product = new Product;
        $this->assertEquals(0, $product->stock);
    }

    public function test_unauthenticated_user_cannot_access_products(): void
    {
        $this->getJson('/products')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_products(): void
    {
        $user = User::factory()->create();
        Product::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/products');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_view_products_index_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products');

        $response->assertStatus(200)
            ->assertViewIs('administrator.product');
    }

    public function test_authenticated_user_can_create_product(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->postJson('/products', [
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'counter_id' => $counter->id,
            'sku' => 'SKU-NEW-01',
            'barcode' => '88800112233',
            'name' => 'New Awesome Soda',
            'description' => 'Soda drink',
            'buy_price' => 4500,
            'sell_price' => 6000,
            'stock' => 50,
            'status' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'sku' => 'SKU-NEW-01',
                'name' => 'New Awesome Soda',
            ]);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-NEW-01',
            'stock' => 50,
        ]);
    }

    public function test_authenticated_user_can_show_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->getJson('/products/'.$product->id);

        $response->assertStatus(200)
            ->assertJsonPath('sku', $product->sku);
    }

    public function test_authenticated_user_can_update_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'Old Product Name']);
        $newCategory = Category::factory()->create();
        $newUnit = Unit::factory()->create();
        $newCounter = Counter::factory()->create();

        $response = $this->actingAs($user)->putJson('/products/'.$product->id, [
            'category_id' => $newCategory->id,
            'unit_id' => $newUnit->id,
            'counter_id' => $newCounter->id,
            'sku' => 'SKU-UPDATED-01',
            'barcode' => '999888777',
            'name' => 'New Product Name',
            'description' => 'Updated desc',
            'buy_price' => 3000,
            'sell_price' => 4500,
            'stock' => 75,
            'status' => false,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Product Name',
                'sku' => 'SKU-UPDATED-01',
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'New Product Name',
            'sku' => 'SKU-UPDATED-01',
            'status' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_product(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/products/'.$product->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
