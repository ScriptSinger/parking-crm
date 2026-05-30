<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Бронирования';
    protected static ?string $pluralModelLabel = 'Бронирования';
    protected static ?string $modelLabel = 'Бронирование';


    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client_id')
                    ->label('Клиент')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required(),

                Select::make('car_id')
                    ->label('Автомобиль')
                    ->relationship('car', 'plate_number')
                    ->searchable()
                    ->required(),

                DateTimePicker::make('arrival_date')
                    ->label('Дата прибытия')
                    ->required(),

                DateTimePicker::make('departure_date')
                    ->label('Дата выезда')
                    ->required(),

                Select::make('status')
                    ->label('Статус')
                    ->required()
                    ->options([
                        'reserved'  => 'Забронировано',
                        'arrived'   => 'Прибыл',
                        'departed'  => 'Уехал',
                        'no_show'   => 'Не приехал',
                    ]),

                Select::make('payment_status')
                    ->label('Оплата')
                    ->required()
                    ->options([
                        'unpaid' => 'Не оплачено',
                        'paid'   => 'Оплачено',
                    ]),

                TextInput::make('price')
                    ->label('Цена')
                    ->numeric()
                    ->step(0.01)
                    ->suffix('₽'),

                Textarea::make('notes')
                    ->label('Заметки')
                    ->rows(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')->label('Клиент')->searchable()->sortable(),
                TextColumn::make('car.plate_number')->label('Авто')->searchable()->sortable(),
                TextColumn::make('arrival_date')->label('Прибытие')->dateTime('d.m.Y H:i'),
                TextColumn::make('departure_date')->label('Выезд')->dateTime('d.m.Y H:i'),
                TextColumn::make('status')->label('Статус')->badge()->colors([
                    'warning' => 'reserved',
                    'success' => 'arrived',
                    'gray'    => 'departed',
                    'danger'  => 'no_show',
                ])->formatStateUsing(function (string $state): string {
                    return match ($state) {
                        'reserved' => 'Забронировано',
                        'arrived' => 'Прибыл',
                        'departed' => 'Уехал',
                        'no_show' => 'Не приехал',
                        default => $state,
                    };
                }),


                TextColumn::make('payment_status')->label('Оплата')->badge()->colors([
                    'danger'  => 'unpaid',
                    'success' => 'paid',
                ])->formatStateUsing(fn($state) => $state === 'paid' ? 'Оплачено' : 'Не оплачено'),
                TextColumn::make('price')->label('Цена')->money('RUB'),
            ])
            ->defaultSort('arrival_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'reserved'  => 'Забронировано',
                        'arrived'   => 'Прибыл',
                        'departed'  => 'Уехал',
                        'no_show'   => 'Не приехал',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Оплата')
                    ->options([
                        'unpaid' => 'Не оплачено',
                        'paid'   => 'Оплачено',
                    ]),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
