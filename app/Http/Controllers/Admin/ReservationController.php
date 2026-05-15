<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Venue;
use Inertia\Inertia;
use App\Mail\ReservationStatusChanged;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('venue')
            ->when(request('status'),  fn($q, $s) => $q->where('status', $s))
            ->when(request('space'),   fn($q, $s) => $q->whereHas('venue', fn($q) => $q->where('slug', $s)))
            ->when(request('date'),    fn($q, $d) => $q->whereDate('start_time', $d))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => $reservations,
            'spaces'       => Venue::select('id_venue', 'venue_name', 'slug')->get(),
            'filters'      => request()->only('status', 'space', 'date'),
        ]);
    }

    public function show(Reservation $reservation)
    {
        return Inertia::render('Admin/Reservations/Show', [
            'reservation' => $reservation->load('venue'),
        ]);
    }

    public function accept(Reservation $reservation)
    {
        $reservation->update(['status' => 'confirmed']);
        Mail::to($reservation->user_email)->send(new ReservationStatusChanged($reservation->load('venue')));
        return back()->with('message', 'Reserva confirmada.');
    }

    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'rejected']);
        Mail::to($reservation->user_email)->send(new ReservationStatusChanged($reservation->load('venue')));
        return back()->with('message', 'Reserva rechazada.');
    }

    public function cancel(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);
        Mail::to($reservation->user_email)->send(new ReservationStatusChanged($reservation->load('venue')));
        return back()->with('message', 'Reserva cancelada.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('message', 'Reserva eliminada.');
    }
}
