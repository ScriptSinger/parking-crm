<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrival = Carbon::now()->addDays(rand(-10, 10))->setTime(rand(0, 23), rand(0, 59));
        $departure = (clone $arrival)->addDays(rand(1, 7));

        return [
            'client_id' => Client::inRandomOrder()->first()?->id ?? Client::factory(),
            'car_id' => Car::inRandomOrder()->first()?->id ?? Car::factory(),
            'arrival_date' => $arrival,
            'departure_date' => $departure,
            'status' => fake()->randomElement(['reserved', 'arrived', 'departed', 'no_show']),
            'payment_status' => fake()->randomElement(['unpaid', 'paid']),
            'price' => fake()->randomElement([100, 150, 200, 250]),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }
}
