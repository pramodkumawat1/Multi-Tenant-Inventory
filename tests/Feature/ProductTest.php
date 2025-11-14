<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\StoreDetail;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_decrements_on_order()
    {
        $user = User::factory()->create();
        $shop = StoreDetail::factory()->create(['user_id' => $user->id, 'name' => 'Test Store']);
        $product = Product::factory()->create(['store_details_id' => $shop->id, 'stock' => 10]);

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'products' => [['product_id' => $product->id, 'quantity' => 3]],
        ]);

        $response->assertStatus(201);
        $this->assertEquals(7, $product->fresh()->stock);
    }

    public function test_insufficient_stock_error()
    {
        $user = User::factory()->create();
        $shop = StoreDetail::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create(['store_details_id' => $shop->id, 'stock' => 2]);

        $response = $this->actingAs($user, 'api')->postJson('/api/orders', [
            'products' => [['product_id' => $product->id, 'quantity' => 3]],
        ]);

        $response->assertStatus(500);
        $response->assertSee('Insufficient stock');
    }

    public function test_tenant_isolation()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $shop1 = StoreDetail::factory()->create(['user_id' => $user1->id]);
        $shop2 = StoreDetail::factory()->create(['user_id' => $user2->id]);
        Product::factory()->create(['store_details_id' => $shop1->id]);
        Product::factory()->create(['store_details_id' => $shop2->id]);

        $response = $this->actingAs($user1, 'api')->getJson('/api/products');
        $response->assertJsonCount(1, 'data');
    }

    public function test_create_product()
    {
        $user = User::factory()->create();
        $shop = StoreDetail::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->postJson('/api/products', [
            'name' => 'Test', 'sku' => 'SKU123', 'price' => 10.99, 'stock' => 5,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['sku' => 'SKU123', 'store_details_id' => $shop->id]);
    }

    public function test_unauthorized_access()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $shop1 = StoreDetail::factory()->create(['user_id' => $user1->id]);
        $shop2 = StoreDetail::factory()->create(['user_id' => $user2->id]);
        $product = Product::factory()->create(['store_details_id' => $shop2->id]);

        $response = $this->actingAs($user1, 'api')->getJson("/api/products/{$product->id}");
        $response->assertStatus(403);
    }
}
