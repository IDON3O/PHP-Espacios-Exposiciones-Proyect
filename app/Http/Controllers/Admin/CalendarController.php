<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\Reservation;
use App\Models\Venue;
use Carbon\Carbon;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index()
    {
        $slug  = request('space');
        $week  = request('week', Carbon::now()->startOfWeek()->toDateString());
        $start = Carbon::parse($week)->startOfWeek();
        $end   = $start->copy()->endOfWeek();

        $spaces = Venue::where('is_active', true)->get();
        $space  = $slug ? Venue::where('slug', $slug)->first() : $spaces->first();

        $reservations = $space ? Reservation::where('venue_id', $space->id_venue)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereBetween('start_time', [$start, $end])
            ->get() : collect();

        $blocked = $space ? BlockedSlot::where('venue_id', $space->id_venue)
            ->whereBetween('start_time', [$start, $end])
            ->get() : collect();

        return Inertia::render('Admin/Calendar', [
            'spaces'       => $spaces,
            'space'        => $space,
            'reservations' => $reservations,
            'blockedSlots' => $blocked,
            'weekStart'    => $start->toDateString(),
            'weekEnd'      => $end->toDateString(),
        ]);
    }
}
