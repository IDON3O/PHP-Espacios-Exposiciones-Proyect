<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ stats: Object, recent: Array });

const statusStyle = (s) => ({
    pending:   'color:#b8965a; border-color:#b8965a;',
    confirmed: 'color:#2d6a4f; border-color:#2d6a4f;',
    rejected:  'color:#9b2226; border-color:#9b2226;',
    cancelled: 'color:#6b6b6b; border-color:#6b6b6b;',
    finished:  'color:#1a1a1a; border-color:#1a1a1a;',
}[s] ?? '');

const statusLabel = (s) => ({
    pending: 'Pendiente', confirmed: 'Confirmada',
    rejected: 'Rechazada', cancelled: 'Cancelada', finished: 'Finalizada',
}[s] ?? s);

const fmt = (dt) => new Date(dt).toLocaleString('es-CO', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout title="Dashboard">
        <div class="max-w-5xl mx-auto space-y-14">

            <!-- Heading -->
            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">Panel principal</p>
                <h1 class="font-display font-black text-5xl text-aura-carbon">Resumen</h1>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-px" style="background:rgba(26,26,26,0.08);">
                <div v-for="(val, key) in {
                    'Pendientes': stats.pending,
                    'Confirmadas': stats.confirmed,
                    'Total reservas': stats.total,
                    'Espacios activos': stats.spaces
                }" :key="key" class="px-8 py-8" style="background:#fbf9f9;">
                    <p class="aura-label mb-3">{{ key }}</p>
                    <p class="font-display font-black text-5xl text-aura-carbon">{{ val }}</p>
                </div>
            </div>

            <!-- Quick links -->
            <div class="flex flex-wrap gap-3">
                <Link :href="route('spaces.index')" class="btn-solid">Gestionar espacios</Link>
                <Link :href="route('reservations.index')" class="btn-ghost">Ver reservas</Link>
                <Link :href="route('calendar.index')" class="btn-ghost">Calendario</Link>
            </div>

            <!-- Recent -->
            <div>
                <p class="aura-label mb-6">Actividad reciente</p>
                <div class="border-t" style="border-color:rgba(26,26,26,0.08);">
                    <div v-if="recent.length === 0" class="py-12 text-center aura-label">
                        Sin reservas aún
                    </div>
                    <div
                        v-for="r in recent" :key="r.id"
                        class="flex items-center justify-between gap-6 py-5 border-b"
                        style="border-color:rgba(26,26,26,0.08);"
                    >
                        <div>
                            <p class="text-sm font-semibold text-aura-carbon">{{ r.user_name }}</p>
                            <p class="aura-label mt-0.5">{{ r.venue?.venue_name }} · {{ fmt(r.start_time) }}</p>
                        </div>
                        <span class="aura-pill text-xs" :style="statusStyle(r.status)">
                            {{ statusLabel(r.status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
