<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letters = ['А', 'В', 'Е', 'К', 'М', 'Н', 'О', 'Р', 'С', 'Т', 'У', 'Х'];

        // Формирование номера: буква + 3 цифры + 2 буквы
        $plateNumber = sprintf(
            '%s%03d%s%s %d',
            $letters[array_rand($letters)],
            rand(100, 999),
            $letters[array_rand($letters)],
            $letters[array_rand($letters)],
            fake()->randomElement([102, 116, 161, 777]) // регионы
        );

        return [
            'client_id' => Client::inRandomOrder()->first()?->id,
            'plate_number' => $plateNumber,
            'brand' => fake()->randomElement(['Toyota', 'Ford', 'Hyundai', 'BMW', 'LADA']),
            'model' => fake()->word(),
            'color' => fake()->safeColorName(),
        ];
    }
}
