<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationLabel = 'Автомобили';
    protected static ?string $pluralModelLabel = 'Автомобили';
    protected static ?string $modelLabel = 'Автомобили';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->required()
                    ->label('Клиент'),

                TextInput::make('plate_number')
                    ->label('Гос. номер')
                    ->mask('A999AA 999')
                    ->placeholder('A123BC 102')
                    ->maxLength(10)

                    ->required(),

                TextInput::make('brand')->label('Марка')->nullable(),
                TextInput::make('model')->label('Модель')->nullable(),
                TextInput::make('color')->label('Цвет')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')->label('Клиент')->sortable()->searchable(),
                TextColumn::make('plate_number')->label('Номер'),
                TextColumn::make('brand')->label('Марка'),
                TextColumn::make('model')->label('Модель'),
                TextColumn::make('color')->label('Цвет'),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
