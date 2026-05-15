<?php
namespace Database\Seeders;

use App\Models\Availability;
use App\Models\BlockedSlot;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Espacios
        $spaces = [
            [
                'venue_name'         => 'Galería Principal',
                'slug'               => 'galeria-principal',
                'venue_type'         => 'exhibition',
                'venue_description'  => 'Amplio espacio central para exposiciones de arte y fotografía.',
                'venue_rules'        => 'No se permite comida ni bebida. Mantener silencio.',
                'price_per_hour'     => 50000,
                'is_active'          => true,
                'venue_address'      => 'Piso 1, Edificio Cultural',
                'venue_max_capacity' => 80,
            ],
            [
                'venue_name'         => 'Sala de Innovación',
                'slug'               => 'sala-de-innovacion',
                'venue_type'         => 'exhibition',
                'venue_description'  => 'Espacio moderno para exposiciones tecnológicas e interactivas.',
                'venue_rules'        => 'Equipos delicados, manipular con cuidado.',
                'price_per_hour'     => 75000,
                'is_active'          => true,
                'venue_address'      => 'Piso 2, Edificio Cultural',
                'venue_max_capacity' => 50,
            ],
            [
                'venue_name'         => 'Pabellón Exterior',
                'slug'               => 'pabellon-exterior',
                'venue_type'         => 'exhibition',
                'venue_description'  => 'Espacio al aire libre ideal para esculturas y exhibiciones grandes.',
                'venue_rules'        => 'Solo disponible con buen clima. Montaje previo coordinado.',
                'price_per_hour'     => 40000,
                'is_active'          => true,
                'venue_address'      => 'Jardines, Edificio Cultural',
                'venue_max_capacity' => 150,
            ],
        ];

        $weekDays = [1, 2, 3, 4, 5];

        foreach ($spaces as $spaceData) {
            $venue = Venue::create($spaceData);

            foreach ($weekDays as $day) {
                Availability::create([
                    'venue_id'    => $venue->id_venue,
                    'day_of_week' => $day,
                    'start_time'  => '08:00:00',
                    'end_time'    => '18:00:00',
                ]);
            }
        }

        // Reservas de demo (sin solapamientos — fechas distintas por factory)
        Reservation::factory(15)->create();

        // Bloqueos de demo
        BlockedSlot::factory(5)->create();
    }
}
