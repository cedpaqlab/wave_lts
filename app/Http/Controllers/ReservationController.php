<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Product;
use App\Models\ReservationDate;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $products = Product::where('user_id', auth()->id())->pluck('name', 'id');
        return view('reservations.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'dates' => 'required|string',
        ]);
        $dates = array_filter(array_map('trim', explode(',', $data['dates'])));
        $productId = $data['product_id'];
        $collisions = ReservationDate::where('product_id', $productId)
            ->whereIn('date', $dates)
            ->exists();
        if ($collisions) {
            return back()->withErrors(['dates' => 'Un ou plusieurs jours sont déjà réservés'])->withInput();
        }
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'status' => 'pending',
        ]);
        foreach ($dates as $date) {
            ReservationDate::create([
                'reservation_id' => $reservation->id,
                'product_id' => $productId,
                'date' => $date,
            ]);
        }
        return redirect()->route('reservations.index');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        $products = Product::where('user_id', auth()->id())->pluck('name', 'id');
        return view('reservations.edit', compact('reservation', 'products'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        // Pour simplifier, on ne gère pas la modification des dates ici
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        $reservation->update($data);
        return redirect()->route('reservations.index');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();
        return redirect()->route('reservations.index');
    }
} 