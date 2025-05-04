<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use App\Models\ReservationDate;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Split dates (comma separated)
        $dates = array_filter(array_map('trim', explode(',', $data['dates'])));
        $productId = $data['product_id'];

        // Check for collisions
        $collisions = ReservationDate::where('product_id', $productId)
            ->whereIn('date', $dates)
            ->exists();

        if ($collisions) {
            Notification::make()
                ->title('Un ou plusieurs jours sont déjà réservés')
                ->danger()
                ->send();
            abort(422, 'Un ou plusieurs jours sont déjà réservés');
        }

        // Remove dates from data before creating reservation
        unset($data['dates']);
        $reservation = static::getModel()::create($data);

        // Store each date
        foreach ($dates as $date) {
            ReservationDate::create([
                'reservation_id' => $reservation->id,
                'product_id' => $productId,
                'date' => $date,
            ]);
        }

        return $reservation;
    }
} 