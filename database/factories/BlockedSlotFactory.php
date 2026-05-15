<?php
namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BlockedSlotFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('now', '+14 days'))
            ->setMinute(0)->setSecond(0);

        return [
            'venue_id'   => Venue::inRandomOrder()->first()->id_venue,
            'start_time' => $start,
            'end_time'   => $start->copy()->addHours(2),
            'reason'     => $this->faker->randomElement([
                'Mantenimiento preventivo',
                'Evento interno',
                'Instalación de obra',
                'Visita técnica',
                'Reserva especial',
            ]),
        ];
    }
}
