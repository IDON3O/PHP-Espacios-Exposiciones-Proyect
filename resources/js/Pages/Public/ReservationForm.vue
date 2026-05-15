<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ space: Object, start_time: String });

const slotMinutes = 60;
// Parse a 'Y-m-d H:i' datetime string and add minutes, returning 'Y-m-d H:i'
const addMinutesToDatetime = (datetimeStr, minutes) => {
    const [datePart, timePart] = datetimeStr.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.split(':').map(Number);
    const totalMinutes = hour * 60 + minute + minutes;
    const newHour = Math.floor(totalMinutes / 60) % 24;
    const newMinute = totalMinutes % 60;
    const pad = (n) => String(n).padStart(2, '0');
    return `${year}-${pad(month)}-${pad(day)} ${pad(newHour)}:${pad(newMinute)}`;
};
const endTime = props.start_time
    ? addMinutesToDatetime(props.start_time, slotMinutes)
    : '';

const form = useForm({
    space_slug: props.space.slug,
    start_time: props.start_time ?? '',
    end_time:   endTime,
    user_name:  '',
    user_email: '',
    notes:      '',
});

const formatPrice = (p) =>
    p > 0
        ? new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(p)
        : 'Entrada libre';

const formatDt = (dt) => {
    if (!dt) return '';
    // Parse 'Y-m-d H:i' format directly without timezone conversion
    const [datePart, timePart] = dt.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.split(':').map(Number);
    const date = new Date(year, month - 1, day, hour, minute);
    return date.toLocaleString('es-CO', { weekday: 'long', day: 'numeric', month: 'long', hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Reservar espacio" />

    <div class="aura min-h-screen">

        <!-- Nav -->
        <nav class="border-b aura-border px-8 py-5 flex items-center gap-4">
            <Link :href="route('spaces.show', space.slug)" class="aura-label hover:text-aura-carbon transition-colors duration-300">← Volver</Link>
            <span class="text-aura-grey">·</span>
            <span class="aura-label text-aura-carbon">Reservar</span>
        </nav>

        <main class="max-w-2xl mx-auto px-8 py-16 space-y-10">

            <!-- Resumen espacio -->
            <div class="border-b aura-divider pb-10">
                <p class="aura-label mb-2">Reservando</p>
                <h1 class="font-display font-black text-4xl text-aura-carbon mb-1">{{ space.venue_name }}</h1>
                <p class="text-aura-muted text-sm">{{ space.venue_address }} · {{ formatPrice(space.price_per_hour) }}/hora</p>

                <div v-if="start_time" class="mt-6 p-4 border aura-border bg-aura-grey/20">
                    <p class="aura-label mb-1">Horario seleccionado</p>
                    <p class="text-sm font-semibold text-aura-carbon capitalize">
                        {{ formatDt(start_time) }} — {{ formatDt(endTime) }}
                    </p>
                </div>
            </div>

            <!-- Formulario -->
            <form @submit.prevent="form.post(route('reservations.store'))" class="space-y-8">
                <p class="aura-label">Datos del solicitante</p>

                <div class="space-y-6">
                    <!-- Campos ocultos de tiempo -->
                    <input type="hidden" v-model="form.start_time" />
                    <input type="hidden" v-model="form.end_time" />

                    <div>
                        <label class="aura-label block mb-2">Nombre completo</label>
                        <input
                            type="text"
                            v-model="form.user_name"
                            placeholder="Tu nombre"
                            class="w-full border-0 border-b border-aura-carbon/30 bg-transparent px-0 py-2 text-sm text-aura-carbon placeholder-aura-muted/50 focus:border-aura-carbon focus:ring-0 transition-colors duration-300"
                        />
                        <p v-if="form.errors.user_name" class="text-red-500 text-xs mt-1">{{ form.errors.user_name }}</p>
                    </div>

                    <div>
                        <label class="aura-label block mb-2">Correo electrónico</label>
                        <input
                            type="email"
                            v-model="form.user_email"
                            placeholder="tu@correo.com"
                            class="w-full border-0 border-b border-aura-carbon/30 bg-transparent px-0 py-2 text-sm text-aura-carbon placeholder-aura-muted/50 focus:border-aura-carbon focus:ring-0 transition-colors duration-300"
                        />
                        <p v-if="form.errors.user_email" class="text-red-500 text-xs mt-1">{{ form.errors.user_email }}</p>
                    </div>

                    <div>
                        <label class="aura-label block mb-2">Notas (opcional)</label>
                        <textarea
                            v-model="form.notes"
                            rows="4"
                            placeholder="Descripción de tu exposición, requerimientos especiales..."
                            class="w-full border-0 border-b border-aura-carbon/30 bg-transparent px-0 py-2 text-sm text-aura-carbon placeholder-aura-muted/50 focus:border-aura-carbon focus:ring-0 transition-colors duration-300 resize-none"
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="btn-solid w-full justify-center disabled:opacity-40"
                    >
                        {{ form.processing ? 'Enviando solicitud...' : 'Confirmar reserva' }}
                    </button>
                    <p v-if="form.errors.start_time" class="text-red-500 text-xs mt-3 text-center">{{ form.errors.start_time }}</p>
                </div>
            </form>
        </main>

        <footer class="border-t aura-border px-8 py-8 text-center mt-8">
            <p class="aura-label">Espacios para Exposiciones · Plataforma de reservas</p>
        </footer>
    </div>
</template>
