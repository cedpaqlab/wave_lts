<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'status' => 'pending',
        ];
    }

    /**
     * Indique l'état pending.
     */
    public function pending()
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }
} 