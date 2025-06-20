<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Services\BookingEventLogger;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected $oldStatus;

    protected function beforeSave(): void
    {
        $this->oldStatus = $this->record->getOriginal('status');
    }

    protected function afterSave(): void
    {
        $newStatus = $this->record->status;

        if ($this->oldStatus !== $newStatus) {
            // Определяем тип события на основе нового статуса
            $eventType = match ($newStatus) {
                'arrived' => 'checkin',
                'departed' => 'checkout',
                'no_show' => 'cancel',
                default => null,
            };

            // Только если есть подходящее событие
            if ($eventType) {
                BookingEventLogger::log($this->record, $eventType);
            }
        }
    }
}
