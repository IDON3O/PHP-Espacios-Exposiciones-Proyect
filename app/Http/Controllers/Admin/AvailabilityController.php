<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\Venue;
use Inertia\Inertia;

class AvailabilityController extends Controller
{
    public function index(Venue $space)
    {
        return Inertia::render('Admin/Spaces/Availability', [
            'space'          => $space,
            'availabilities' => $space->availabilities()->orderBy('day_of_week')->get(),
        ]);
    }

    public function store(Venue $space)
    {
        request()->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        $space->availabilities()->create(request()->only('day_of_week', 'start_time', 'end_time'));

        return back()->with('message', 'Disponibilidad agregada.');
    }

    public function destroy(Venue $space, Availability $availability)
    {
        $availability->delete();
        return back()->with('message', 'Disponibilidad eliminada.');
    }
}
