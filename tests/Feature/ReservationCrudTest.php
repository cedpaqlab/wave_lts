<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\ReservationDate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_edit_and_delete_own_reservation_with_multiple_dates()
    {
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->create();
        $this->actingAs($user);
        $dates = [now()->addDays(1)->format('Y-m-d'), now()->addDays(2)->format('Y-m-d')];

        // Create
        $response = $this->post(route('reservations.store'), [
            'product_id' => $product->id,
            'dates' => implode(',', $dates),
        ]);
        $response->assertRedirect(route('reservations.index'));
        $reservation = Reservation::first();
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'user_id' => $user->id]);
        foreach ($dates as $date) {
            $this->assertDatabaseHas('reservation_dates', [
                'reservation_id' => $reservation->id,
                'product_id' => $product->id,
                'date' => $date,
            ]);
        }

        // Edit (status only)
        $response = $this->put(route('reservations.update', $reservation), [
            'product_id' => $product->id,
            'status' => 'confirmed',
        ]);
        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'confirmed']);

        // Delete
        $response = $this->delete(route('reservations.destroy', $reservation));
        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
        $this->assertDatabaseMissing('reservation_dates', ['reservation_id' => $reservation->id]);
    }

    /** @test */
    public function user_cannot_edit_or_delete_others_reservation()
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $product = Product::factory()->for($other)->create();
        $reservation = Reservation::factory()->for($other)->for($product)->create();
        $this->actingAs($user);

        $response = $this->put(route('reservations.update', $reservation), [
            'product_id' => $product->id,
            'status' => 'confirmed',
        ]);
        $response->assertForbidden();

        $response = $this->delete(route('reservations.destroy', $reservation));
        $response->assertForbidden();
    }
} 