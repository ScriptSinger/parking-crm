<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('booking_id')
                    ->relationship('booking', 'id')
                    ->searchable()
                    ->required(),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('₽'),

                Select::make('method')
                    ->options([
                        'cash' => 'Наличные',
                        'card' => 'Карта',
                        'online' => 'Онлайн',
                    ])
                    ->required(),

                DateTimePicker::make('paid_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.id')->label('Бронирование')->sortable(),
                TextColumn::make('amount')->money('RUB', true),


                TextColumn::make('method')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'cash' => 'Наличные',
                        'card' => 'Карта',
                        'online' => 'Онлайн',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        'card' => 'primary',
                        'online' => 'warning',
                    }),

                TextColumn::make('paid_at')->dateTime(),
                TextColumn::make('created_at')->since(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
