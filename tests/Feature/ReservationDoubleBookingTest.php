<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\ReservationDate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationDoubleBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_double_book_same_product_and_date()
    {
        // Create user and product
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->create();
        $date = now()->addDays(3)->format('Y-m-d');

        // Première réservation
        $this->actingAs($user)->post(route('reservations.store'), [
            'product_id' => $product->id,
            'dates' => $date,
        ])->assertRedirect(route('reservations.index'));

        // Deuxième réservation sur la même date
        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'product_id' => $product->id,
            'dates' => $date,
        ]);

        $response->assertSessionHasErrors(['dates']);
        $this->assertEquals(1, ReservationDate::where('product_id', $product->id)->where('date', $date)->count());
    }
} 