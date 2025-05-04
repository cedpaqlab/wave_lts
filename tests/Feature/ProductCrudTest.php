<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_edit_and_delete_own_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create
        $response = $this->post(route('products.store'), [
            'name' => 'Produit Test',
            'description' => 'Desc',
            'daily_price' => 10.5,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Produit Test', 'user_id' => $user->id]);

        $product = Product::first();

        // Edit
        $response = $this->put(route('products.update', $product), [
            'name' => 'Produit Modifié',
            'description' => 'Desc2',
            'daily_price' => 20,
            'status' => 'inactive',
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Produit Modifié', 'user_id' => $user->id]);

        // Delete
        $response = $this->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function user_cannot_edit_or_delete_others_product()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->for($other)->create();
        $this->actingAs($user);

        $response = $this->put(route('products.update', $product), [
            'name' => 'Hack',
            'description' => '',
            'daily_price' => 1,
            'status' => 'active',
        ]);
        $response->assertForbidden();

        $response = $this->delete(route('products.destroy', $product));
        $response->assertForbidden();
    }
} 