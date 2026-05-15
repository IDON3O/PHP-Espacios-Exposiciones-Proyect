<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use App\Models\BlockedSlot;
use App\Models\Reservation;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Mail\ReservationCreated;
use Illuminate\Support\Facades\Mail;

class SpaceController extends Controller
{
    public function index(Request $request)
    {
        $date = null;
        if ($request->date) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $request->date);
            } catch (\Exception $e) {
                // Invalid date, ignore
            }
        }

        $venues = Venue::where('is_active', true)
            ->when($request->type, fn($q, $t) => $q->where('venue_type', $t))
            ->when($date, fn($q) => $q->whereExists(function($q) use ($date) {
                $q->selectRaw(1)
                    ->from('availabilities')
                    ->whereRaw('availabilities.venue_id = venues.id_venue')
                    ->where('day_of_week', $date->dayOfWeek);
            }))
            ->get();

        return Inertia::render('Public/Index', [
            'spaces' => $venues,
            'filterDate' => $date ? $date->toDateString() : null,
        ]);
    }

    public function show(string $slug)
    {
        $venue = Venue::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $slots = $this->getUpcomingSlots($venue, 7); // próximos 7 días

        return Inertia::render('Public/Show', [
            'space' => $venue,
            'slots' => $slots,
        ]);
    }

    public function reservationForm(Request $request)
    {
        $venue = Venue::where('slug', $request->space)->where('is_active', true)->firstOrFail();

        return Inertia::render('Public/ReservationForm', [
            'space'      => $venue,
            'start_time' => $request->start,
        ]);
    }

    public function storeReservation(Request $request)
    {
        $nowInTimezone = Carbon::now();

        $request->validate([
            'space_slug' => 'required|exists:venues,slug',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time'   => 'required|date_format:Y-m-d H:i',
            'user_name'  => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'notes'      => 'nullable|string|max:1000',
        ]);

        $venue = Venue::where('slug', $request->space_slug)->firstOrFail();
        $start = Carbon::createFromFormat('Y-m-d H:i', $request->start_time);
        $end   = Carbon::createFromFormat('Y-m-d H:i', $request->end_time);

        // Validar que start_time sea en el futuro
        if ($start->lte($nowInTimezone)) {
            return back()->withErrors(['start_time' => 'La fecha de inicio debe ser futura.']);
        }

        // Validar que end_time sea después de start_time
        if ($end->lte($start)) {
            return back()->withErrors(['end_time' => 'La fecha de fin debe ser posterior a la de inicio.']);
        }

        // Validar disponibilidad
        if (!$this->isAvailable($venue, $start, $end)) {
            return back()->withErrors(['start_time' => 'El horario seleccionado no está disponible.']);
        }

        $reservation = Reservation::create([
            'slug'       => Str::uuid(),
            'venue_id'   => $venue->id_venue,
            'start_time' => $start,
            'end_time'   => $end,
            'status'     => 'pending',
            'user_name'  => $request->user_name,
            'user_email' => $request->user_email,
            'notes'      => $request->notes,
        ]);

        Mail::to($reservation->user_email)->send(new ReservationCreated($reservation->load('venue')));

        return Inertia::render('Public/ReservationSuccess', [
            'reservation' => $reservation->load('venue'),
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function isAvailable(Venue $venue, Carbon $start, Carbon $end): bool
    {
        $slotMinutes = (int) env('RESERVATION_SLOT_MINUTES', 60);

        // Duración mínima
        if ($start->diffInMinutes($end) < $slotMinutes) return false;

        // Dentro del horario semanal
        $dayOfWeek = $start->dayOfWeek; // 0=Dom ... 6=Sab
        $availability = Availability::where('venue_id', $venue->id_venue)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$availability) return false;

        $availStart = Carbon::createFromFormat('Y-m-d H:i:s', $start->format('Y-m-d') . ' ' . $availability->start_time);
        $availEnd   = Carbon::createFromFormat('Y-m-d H:i:s', $start->format('Y-m-d') . ' ' . $availability->end_time);

        // El inicio debe ser >= al inicio de disponibilidad
        if ($start->lt($availStart)) return false;

        // El fin debe ser <= al fin de disponibilidad (permitir igualdad)
        if ($end->gt($availEnd)) return false;

        // Sin solapamiento con reservas existentes
        $overlap = Reservation::where('venue_id', $venue->id_venue)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        if ($overlap) return false;

        // Sin solapamiento con bloqueos
        $blocked = BlockedSlot::where('venue_id', $venue->id_venue)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start)
            ->exists();

        return !$blocked;
    }

    private function getUpcomingSlots(Venue $venue, int $days): array
    {
        $slotMinutes = (int) env('RESERVATION_SLOT_MINUTES', 60);
        $slots = [];
        $now   = Carbon::now();

        for ($i = 0; $i < $days; $i++) {
            $date = $now->copy()->addDays($i)->startOfDay();
            $dow  = $date->dayOfWeek;

            $availability = Availability::where('venue_id', $venue->id_venue)
                ->where('day_of_week', $dow)
                ->first();

            if (!$availability) continue;

            $cursor = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $availability->start_time);
            $dayEnd = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $availability->end_time);

            while ($cursor->copy()->addMinutes($slotMinutes)->lte($dayEnd)) {
                $slotEnd = $cursor->copy()->addMinutes($slotMinutes);

                if ($cursor->gt($now) && $this->isAvailable($venue, $cursor, $slotEnd)) {
                    $slots[] = [
                        'start' => $cursor->format('Y-m-d H:i'),
                        'end'   => $slotEnd->format('Y-m-d H:i'),
                    ];
                }

                $cursor->addMinutes($slotMinutes);
            }
        }

        return $slots;
    }

    public function showReservation(string $slug)
    {
        $reservation = Reservation::where('slug', $slug)
            ->with('venue')
            ->firstOrFail();

        return Inertia::render('Public/ReservationTrack', [
            'reservation' => $reservation,
        ]);
    }
}
