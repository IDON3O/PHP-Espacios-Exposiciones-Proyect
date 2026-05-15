<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>
<body style="font-family: sans-serif; background:#f9fafb; padding: 32px;">
<div style="max-width:560px; margin:0 auto; background:#fff; border-radius:16px; padding:32px; border:1px solid #e5e7eb;">

    @php
        $config = [
            'confirmed' => ['emoji' => '✅', 'title' => '¡Reserva confirmada!',  'color' => '#16a34a', 'msg' => 'Tu reserva ha sido aprobada.'],
            'rejected'  => ['emoji' => '❌', 'title' => 'Reserva rechazada',     'color' => '#dc2626', 'msg' => 'Lamentablemente tu reserva no fue aprobada.'],
            'cancelled' => ['emoji' => '🚫', 'title' => 'Reserva cancelada',     'color' => '#6b7280', 'msg' => 'Tu reserva ha sido cancelada.'],
        ][$reservation->status] ?? ['emoji' => 'ℹ️', 'title' => 'Actualización', 'color' => '#4f46e5', 'msg' => ''];
    @endphp

    <h1 style="color:{{ $config['color'] }}; font-size:22px; margin-bottom:4px;">
        {{ $config['emoji'] }} {{ $config['title'] }}
    </h1>
    <p style="color:#6b7280; font-size:14px; margin-top:0;">{{ $config['msg'] }}</p>

    <hr style="border:none; border-top:1px solid #f3f4f6; margin:24px 0;">

    <table style="width:100%; font-size:14px; color:#374151; border-collapse:collapse;">
        <tr><td style="padding:6px 0; font-weight:600; width:40%;">Espacio</td><td>{{ $reservation->venue->venue_name }}</td></tr>
        <tr><td style="padding:6px 0; font-weight:600;">Inicio</td><td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td></tr>
        <tr><td style="padding:6px 0; font-weight:600;">Fin</td><td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td></tr>
        <tr><td style="padding:6px 0; font-weight:600;">Solicitante</td><td>{{ $reservation->user_name }}</td></tr>
    </table>

    <hr style="border:none; border-top:1px solid #f3f4f6; margin:24px 0;">
    <p style="color:#9ca3af; font-size:12px; text-align:center;">Espacios para Exposiciones</p>
</div>
</body>
</html>
