<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class CurrentMonthReservations extends Widget
{
    protected static string $view = 'filament.widgets.current-month-reservations';

    public function getData(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $count = Reservation::whereBetween('created_at', [$start, $end])->count();
        return [
            'count' => $count,
        ];
    }
} 