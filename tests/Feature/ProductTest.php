<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created_using_factory(): void
    {
        $category = Category::factory()->create(['name' => 'Clothing']);
        $unit = Unit::factory()->create(['name' => 'Pieces', 'code' => 'PCS']);

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'sku' => 'SKU-CLOTH-01',
            'barcode' => '1234567890123',
            'name' => 'Cool T-Shirt',
            'description' => 'A very cool cotton t-shirt.',
            'buy_price' => 15.50,
            'sell_price' => 29.99,
            'status' => true,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'sku' => 'SKU-CLOTH-01',
            'barcode' => '1234567890123',
            'name' => 'Cool T-Shirt',
            'description' => 'A very cool cotton t-shirt.',
            'buy_price' => 15.50,
            'sell_price' => 29.99,
            'status' => true,
        ]);
    }

    public function test_product_belongs_to_category_and_unit(): void
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertInstanceOf(Unit::class, $product->unit);
    }

    public function test_product_has_default_status(): void
    {
        $product = new Product;
        $this->assertTrue($product->status);
    }
}
