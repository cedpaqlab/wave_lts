<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_product_public_page()
    {
        $product = Product::factory()->create(['name' => 'slug-produit']);
        $response = $this->get('/produit/slug-produit');
        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee((string) $product->daily_price);
    }

    /** @test */
    public function guest_cannot_reserve_without_auth()
    {
        $product = Product::factory()->create();
        $date = now()->addDays(2)->format('Y-m-d');
        $response = $this->post('/reservations', [
            'product_id' => $product->id,
            'dates' => $date,
        ]);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_reserve_from_public_page()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'slug-produit']);
        $date = now()->addDays(2)->format('Y-m-d');
        $this->actingAs($user);
        $response = $this->post('/reservations', [
            'product_id' => $product->id,
            'dates' => $date,
        ]);
        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservation_dates', [
            'product_id' => $product->id,
            'date' => $date,
        ]);
    }

    /** @test */
    public function reservation_collision_is_handled_on_public_page()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['name' => 'slug-produit']);
        $date = now()->addDays(2)->format('Y-m-d');
        $this->actingAs($user);
        // Première réservation
        $this->post('/reservations', [
            'product_id' => $product->id,
            'dates' => $date,
        ]);
        // Collision
        $response = $this->post('/reservations', [
            'product_id' => $product->id,
            'dates' => $date,
        ]);
        $response->assertSessionHasErrors(['dates']);
    }
} 