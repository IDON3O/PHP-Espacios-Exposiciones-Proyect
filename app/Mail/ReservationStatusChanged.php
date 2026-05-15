<?php
namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $label = [
            'confirmed' => 'Reserva confirmada',
            'rejected'  => 'Reserva rechazada',
            'cancelled' => 'Reserva cancelada',
        ][$this->reservation->status] ?? 'Actualización de reserva';

        return new Envelope(subject: $label . ' — ' . $this->reservation->venue->venue_name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reservation-status-changed');
    }
}
