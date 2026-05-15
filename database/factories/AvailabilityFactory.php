<?php
namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvailabilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'venue_id'    => Venue::inRandomOrder()->first()->id_venue,
            'day_of_week' => $this->faker->numberBetween(1, 5),
            'start_time'  => '08:00:00',
            'end_time'    => '18:00:00',
        ];
    }
}
