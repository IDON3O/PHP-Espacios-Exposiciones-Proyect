<?php
namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $venue = Venue::inRandomOrder()->first();
        $start = Carbon::instance($this->faker->dateTimeBetween('now', '+30 days'))
            ->setMinute(0)->setSecond(0);
        $end   = $start->copy()->addHour();

        return [
            'slug'       => Str::uuid(),
            'venue_id'   => $venue->id_venue,
            'start_time' => $start,
            'end_time'   => $end,
            'status'     => $this->faker->randomElement(['pending','confirmed','rejected','cancelled','finished']),
            'user_name'  => $this->faker->name(),
            'user_email' => $this->faker->safeEmail(),
            'notes'      => $this->faker->optional()->sentence(),
        ];
    }
}
