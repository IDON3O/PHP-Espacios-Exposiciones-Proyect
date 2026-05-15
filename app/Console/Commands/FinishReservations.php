<?php
namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinishReservations extends Command
{
    protected $signature   = 'reservations:finish';
    protected $description = 'Marca como finalizadas las reservas confirmadas cuyo end_time ya pasó.';

    public function handle(): void
    {
        $count = Reservation::where('status', 'confirmed')
            ->where('end_time', '<', Carbon::now())
            ->update(['status' => 'finished']);

        $this->info("✓ {$count} reserva(s) marcadas como finalizadas.");
    }
}
