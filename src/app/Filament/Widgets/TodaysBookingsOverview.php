<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodaysBookingsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $bookingsToday = Booking::whereDate('arrival_date', $today)->count();

        $revenueToday = Booking::whereDate('arrival_date', $today)
            ->where('payment_status', 'paid')
            ->sum('price');

        $unpaidToday = Booking::whereDate('arrival_date', $today)
            ->where('payment_status', 'unpaid')
            ->sum('price');

        return [
            Stat::make('Бронирований сегодня', $bookingsToday)
                ->description('Дата прибытия — сегодня')
                ->icon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make('Оплачено (₽)', number_format($revenueToday, 2, ',', ' '))
                ->description('По прибытиям сегодня')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Ожидается к оплате (₽)', number_format($unpaidToday, 2, ',', ' '))
                ->description('Не оплачено')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
