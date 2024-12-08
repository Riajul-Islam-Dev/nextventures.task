<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $adminUser;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('Admin');

        $this->product = Product::factory()->create();
    }

    public function test_get_all_products()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'description', 'price', 'stock', 'created_at', 'updated_at']
            ]);
    }

    public function test_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 100.00,
            'stock' => 10,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/products', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Product',
                'description' => 'This is a test product.',
                'price' => 100.00,
                'stock' => 10,
            ]);
    }

    public function test_show_product()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson("/api/products/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->product->id,
                'name' => $this->product->name,
                'description' => $this->product->description,
                'price' => $this->product->price,
                'stock' => $this->product->stock,
            ]);
    }

    public function test_update_product()
    {
        $updatedData = [
            'name' => 'Updated Product Name',
            'description' => 'Updated description.',
            'price' => 150.00,
            'stock' => 5,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->putJson("/api/products/{$this->product->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Product Name',
                'description' => 'Updated description.',
                'price' => 150.00,
                'stock' => 5,
            ]);
    }

    public function test_delete_product()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->deleteJson("/api/products/{$this->product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Product deleted successfully.',
            ]);

        $this->assertDeleted($this->product);
    }

    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
