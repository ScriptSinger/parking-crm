<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email'];


    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function setPhoneAttribute($value): void
    {
        // Удаляем все, кроме цифр
        $digits = preg_replace('/\D+/', '', $value);

        // Приводим к формату E.164
        if (str_starts_with($digits, '8')) {
            $digits = '7' . substr($digits, 1);
        } elseif (!str_starts_with($digits, '7')) {
            $digits = '7' . $digits;
        }

        $this->attributes['phone'] = '+' . $digits;
    }

    public function getPhoneFormattedAttribute(): string
    {
        if (empty($this->phone)) {
            return '';
        }

        $digits = preg_replace('/\D/', '', $this->phone ?? '');

        if (strlen($digits) === 11) {
            return preg_replace('/(\d)(\d{3})(\d{3})(\d{2})(\d{2})/', '+$1 ($2) $3-$4-$5', $digits);
        }

        return (string) $this->phone;
    }


    public function getNumberFormattedAttribute(): string
    {
        return preg_replace('/^([А-Я])(\d{3})([А-Я]{2})(\d{2,3})$/u', '$1 $2 $3 $4', $this->number);
    }
}
