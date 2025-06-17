<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'arrival_date',
        'departure_date',
        'status',
        'payment_status',
        'price',
        'notes',
    ];

    protected $casts = [
        'arrival_date'    => 'datetime',
        'departure_date'  => 'datetime',
        'price'           => 'decimal:2',
    ];

    /**
     * Клиент, сделавший бронирование.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Автомобиль, участвующий в бронировании.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }



    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function eventLogs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }

    public function latestEvent(): HasOne
    {
        return $this->hasOne(EventLog::class)->latestOfMany();
    }
}
