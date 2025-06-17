<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventLogResource\Pages;
use App\Filament\Resources\EventLogResource\RelationManagers;
use App\Models\EventLog;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventLogResource extends Resource
{
    protected static ?string $model = EventLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('booking_id')
                    ->label('Бронирование')
                    ->relationship('booking', 'id')
                    ->searchable()
                    ->required(),

                Select::make('event_type')
                    ->label('Тип события')
                    ->options([
                        'checkin'  => 'Заезд',
                        'checkout' => 'Выезд',
                        'cancel'   => 'Отмена',
                    ])
                    ->required(),

                Select::make('performed_by')
                    ->label('Исполнитель')
                    ->relationship('performedBy', 'name')
                    ->searchable()
                    ->required(),

                DateTimePicker::make('created_at')
                    ->label('Дата и время')
                    ->default(now())
                    ->required()
                    ->disabled(fn($livewire) => $livewire instanceof Pages\EditEventLog), // Только при создании
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.id')->label('ID бронирования')->sortable(),
                TextColumn::make('event_type')->label('Событие')->badge()->colors([
                    'info'    => 'checkin',
                    'success' => 'checkout',
                    'danger'  => 'cancel',
                ]),
                TextColumn::make('performedBy.name')->label('Исполнитель')->searchable(),
                TextColumn::make('created_at')->label('Дата и время')->dateTime('d.m.Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Тип события')
                    ->options([
                        'checkin'  => 'Заезд',
                        'checkout' => 'Выезд',
                        'cancel'   => 'Отмена',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEventLogs::route('/'),
            'create' => Pages\CreateEventLog::route('/create'),
            'edit' => Pages\EditEventLog::route('/{record}/edit'),
        ];
    }
}
