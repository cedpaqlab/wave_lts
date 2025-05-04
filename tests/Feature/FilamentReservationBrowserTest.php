<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FilamentReservationBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_product_and_reservation_in_filament()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/products/create')
                ->type('name', 'Test Produit')
                ->type('description', 'Description de test')
                ->type('daily_price', '42.00')
                ->select('status', 'active')
                ->press('Créer')
                ->assertPathIs('/admin/products')
                ->assertSee('Test Produit')
                // Réservation
                ->visit('/admin/reservations/create')
                ->select('product_id', 1)
                ->type('dates', now()->addDays(2)->format('Y-m-d') . ',' . now()->addDays(3)->format('Y-m-d'))
                ->select('status', 'pending')
                ->press('Créer')
                ->assertPathIs('/admin/reservations')
                ->assertSee('Test Produit');
        });
    }
} 