<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ reservation: Object });

const fmt = (dt) => new Date(dt).toLocaleString('es-CO', {
    weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit'
});

const statusConfig = {
    pending: { label: 'Pendiente', class: 'border-aura-gold text-aura-gold' },
    confirmed: { label: 'Confirmada', class: 'border-aura-green text-aura-green' },
    rejected: { label: 'Rechazada', class: 'border-aura-red text-aura-red' },
    cancelled: { label: 'Cancelada', class: 'border-aura-red text-aura-red' },
    finished: { label: 'Finalizada', class: 'border-aura-grey text-aura-grey' },
};
</script>

<template>
    <Head title="Estado de reserva" />

    <div class="aura min-h-screen flex flex-col">
        <nav class="border-b aura-border px-8 py-5">
            <span class="font-display font-bold text-lg tracking-tight text-aura-carbon">
                Espacios · Exposiciones
            </span>
        </nav>

        <div class="flex-1 flex items-center justify-center px-8 py-16">
            <div class="max-w-md w-full space-y-10 text-center">

                <div>
                    <p class="aura-label mb-4">Estado de tu reserva</p>
                    <h1 class="font-display font-black text-5xl text-aura-carbon leading-tight mb-4">
                        Código<br>{{ reservation.slug }}
                    </h1>
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
                            <span class="aura-pill" :class="statusConfig[reservation.status].class">
                                {{ statusConfig[reservation.status].label }}
                            </span>
                        </dd>
                    </div>
                </dl>

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
