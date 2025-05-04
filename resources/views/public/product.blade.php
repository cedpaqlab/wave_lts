@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
    <div class="mb-4 text-gray-700">{{ $product->description }}</div>
    <div class="mb-4 font-semibold">Prix/jour : {{ number_format($product->daily_price, 2, ',', ' ') }} €</div>
    <form method="POST" action="{{ route('reservations.store') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="mb-4">
            <label for="dates" class="block font-medium mb-1">Dates</label>
            <input id="dates" name="dates" type="text" class="border rounded w-full p-2" required>
            @error('dates')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded">Réserver</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('#dates', {
        mode: 'multiple',
        dateFormat: 'Y-m-d',
        minDate: 'today',
    });
</script>
@endpush 