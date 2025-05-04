<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Widgets\CurrentMonthReservations;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CurrentMonthReservations::class,
        ];
    }
} 