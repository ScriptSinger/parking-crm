<?php

namespace App\Filament\Resources\EventLogResource\Pages;

use App\Filament\Resources\EventLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventLog extends CreateRecord
{
    protected static string $resource = EventLogResource::class;
}
