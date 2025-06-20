<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\EventLog;
use Illuminate\Support\Facades\Auth;

class BookingEventLogger
{
    public static function log(Booking $booking, string $eventType): void
    {
        EventLog::create([
            'booking_id' => $booking->id,
            'event_type' => $eventType,
            'performed_by' => Auth::id(), // или передавать явно
        ]);
    }
}
