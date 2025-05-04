<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'product_id',
        'date',
    ];

    /**
     * Get the reservation for this date.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the product for this reservation date.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 