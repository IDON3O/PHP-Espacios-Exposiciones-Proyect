<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\Venue;
use Inertia\Inertia;

class BlockedSlotController extends Controller
{
    public function index(Venue $space)
    {
        return Inertia::render('Admin/Spaces/BlockedSlots', [
            'space'        => $space,
            'blockedSlots' => $space->blockedSlots()->orderBy('start_time')->get(),
        ]);
    }

    public function store(Venue $space)
    {
        request()->validate([
            'start_time' => 'required|date|after:now',
            'end_time'   => 'required|date|after:start_time',
            'reason'     => 'nullable|string|max:255',
        ]);

        $space->blockedSlots()->create(request()->only('start_time', 'end_time', 'reason'));

        return back()->with('message', 'Bloqueo registrado.');
    }

    public function destroy(Venue $space, BlockedSlot $blockedSlot)
    {
        $blockedSlot->delete();
        return back()->with('message', 'Bloqueo eliminado.');
    }
}
