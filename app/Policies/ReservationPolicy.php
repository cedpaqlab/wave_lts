<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine if the user can view the reservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        // Owner of reservation OR owner of product
        return $user->id === $reservation->user_id || $user->id === $reservation->product->user_id;
    }
} 