<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventLog extends Model
{
    protected $fillable = [
        'booking_id',
        'event_type',
        'performed_by',
    ];

    protected $casts = [
        'event_type' => 'string',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     *  Кто выполнил действие (обычно кассир или админ)
     */
    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
