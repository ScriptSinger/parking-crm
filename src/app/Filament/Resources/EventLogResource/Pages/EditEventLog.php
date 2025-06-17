<?php

namespace App\Filament\Resources\EventLogResource\Pages;

use App\Filament\Resources\EventLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventLog extends EditRecord
{
    protected static string $resource = EventLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
