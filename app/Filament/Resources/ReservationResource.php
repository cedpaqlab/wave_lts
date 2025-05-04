<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use App\Models\Product;
use App\Models\ReservationDate;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Réservations';
    protected static ?string $label = 'Réservation';
    protected static ?string $pluralLabel = 'Réservations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produit')
                    ->options(function () {
                        $user = Auth::user();
                        if ($user && $user->hasRole('admin')) {
                            return Product::pluck('name', 'id');
                        }
                        return Product::where('user_id', $user->id)->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('Locataire')
                    ->relationship('user', 'name')
                    ->required()
                    ->visible(fn () => Auth::user()?->hasRole('admin')),
                Forms\Components\TextInput::make('dates')
                    ->label('Dates')
                    ->required()
                    ->extraAttributes(['type' => 'text', 'data-flatpickr' => json_encode(['mode' => 'multiple', 'dateFormat' => 'Y-m-d'])]),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'En attente',
                        'confirmed' => 'Confirmée',
                        'cancelled' => 'Annulée',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Produit'),
                Tables\Columns\TextColumn::make('user.name')->label('Locataire'),
                Tables\Columns\TextColumn::make('status')->label('Statut'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
} 