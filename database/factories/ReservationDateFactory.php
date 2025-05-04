<?php

namespace Database\Factories;

use App\Models\ReservationDate;
use App\Models\Reservation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationDateFactory extends Factory
{
    protected $model = ReservationDate::class;

    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'product_id' => Product::factory(),
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 month')->format('Y-m-d'),
        ];
    }
} 