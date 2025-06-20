<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;

class ParkingStatsWidget extends ChartWidget
{
    protected static ?string $heading = 'Текущая загрузка парковки';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $totalSpots = 500;

        // Считаем все активные бронирования
        $occupied = Booking::whereIn('status', ['reserved', 'arrived'])->count();

        // Вычисляем количество свободных мест
        $free = max($totalSpots - $occupied, 0);

        return [
            'labels' => ['Свободные места', 'Занятые места'],
            'datasets' => [
                [
                    'label' => 'Количество мест',
                    'data' => [$free, $occupied],
                    'backgroundColor' => ['#38a169', '#e53e3e'],
                    'borderRadius' => 4,
                ],
            ],
        ];
    }
}
