<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ reservation: Object });

const fmt = (dt) => new Date(dt).toLocaleString('es-CO', {
    weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit'
});
</script>

<template>
    <Head title="Solicitud recibida" />

    <div class="aura min-h-screen flex flex-col">
        <nav class="border-b aura-border px-8 py-5">
            <span class="font-display font-bold text-lg tracking-tight text-aura-carbon">
                Espacios · Exposiciones
            </span>
        </nav>

        <div class="flex-1 flex items-center justify-center px-8 py-16">
            <div class="max-w-md w-full space-y-10 text-center">

                <div>
                    <p class="aura-label mb-4">Solicitud enviada</p>
                    <h1 class="font-display font-black text-5xl text-aura-carbon leading-tight mb-4">
                        Reserva<br>recibida
                    </h1>
                    <p class="text-aura-muted leading-relaxed">
                        Tu solicitud está <strong class="text-aura-carbon">pendiente de aprobación</strong>.
                        Recibirás una notificación por correo cuando sea revisada.
                    </p>
                </div>

                <hr class="aura-divider" />

                <dl class="space-y-3 text-left">
                    <div class="flex justify-between gap-4">
                        <dt class="aura-label">Espacio</dt>
                        <dd class="text-sm font-semibold text-aura-carbon text-right">{{ reservation.venue.venue_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="aura-label">Inicio</dt>
                        <dd class="text-sm text-aura-carbon text-right capitalize">{{ fmt(reservation.start_time) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="aura-label">Fin</dt>
                        <dd class="text-sm text-aura-carbon text-right capitalize">{{ fmt(reservation.end_time) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="aura-label">Solicitante</dt>
                        <dd class="text-sm text-aura-carbon text-right">{{ reservation.user_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="aura-label">Estado</dt>
                        <dd>
                            <span class="aura-pill border-aura-gold text-aura-gold">Pendiente</span>
                        </dd>
                    </div>
                </dl>

                <hr class="aura-divider" />

                                <!-- Código de seguimiento -->
                                <div class="text-center">
                                    <p class="aura-label mb-2">Código de seguimiento</p>
                                    <p class="font-mono text-2xl font-bold text-aura-carbon tracking-wider">{{ reservation.slug }}</p>
                                    <p class="text-aura-muted text-xs mt-1">Guárdalo para consultar el estado de tu reserva</p>
                                </div>

                                <hr class="aura-divider" />

                                <Link href="/" class="btn-ghost w-full justify-center">
                                    Explorar más espacios
                                </Link>
            </div>
        </div>

        <footer class="border-t aura-border px-8 py-8 text-center">
            <p class="aura-label">Espacios para Exposiciones · Plataforma de reservas</p>
        </footer>
    </div>
</template>
