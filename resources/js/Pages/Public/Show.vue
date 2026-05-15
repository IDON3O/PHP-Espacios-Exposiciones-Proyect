<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ space: Object, slots: Array });

const formatPrice = (p) =>
    p > 0
        ? new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(p)
        : 'Entrada libre';

const groupedSlots = computed(() => {
    const groups = {};
    props.slots.forEach(slot => {
        // Handle both 'Y-m-d H:i' and 'Y-m-d H:i:s' formats
        const date = slot.start.includes(' ') ? slot.start.split(' ')[0] : slot.start;
        if (!groups[date]) groups[date] = [];
        groups[date].push(slot);
    });
    return groups;
});

const formatDayHeader = (dateStr) => {
    // Ensure we have a proper date string
    const cleanDate = dateStr.split(' ')[0]; // Take just the date part if it has time
    return new Date(cleanDate + 'T12:00:00').toLocaleDateString('es-CO', {
        weekday: 'long', day: 'numeric', month: 'long'
    });
};

const formatTime = (dt) => {
    // Parse 'Y-m-d H:i' format directly without timezone conversion
    const [datePart, timePart] = dt.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, minute] = timePart.split(':').map(Number);
    const date = new Date(year, month - 1, day, hour, minute);
    return date.toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head :title="space.venue_name" />

    <div class="aura min-h-screen">

        <!-- Nav -->
        <nav class="border-b aura-border px-8 py-5 flex items-center gap-4">
            <Link href="/" class="aura-label hover:text-aura-carbon transition-colors duration-300">← Volver</Link>
            <span class="text-aura-grey">·</span>
            <span class="aura-label text-aura-carbon">{{ space.venue_name }}</span>
        </nav>

        <main class="max-w-5xl mx-auto px-8 py-16 space-y-16">

            <!-- Detalle -->
            <section class="grid md:grid-cols-2 gap-16 items-start">

                <!-- Imagen -->
                <div class="aura-card-img aura-border" style="aspect-ratio:4/5;">
                    <img
                        v-if="space.venue_image"
                        :src="'/storage/' + space.venue_image"
                        :alt="space.venue_name"
                    />
                    <div v-else class="w-full h-full bg-aura-grey flex items-center justify-center">
                        <span class="aura-label">Sin imagen</span>
                    </div>
                </div>

                <!-- Info -->
                <div class="py-4 space-y-8">
                    <div>
                        <p class="aura-label mb-3">Espacio de exposición</p>
                        <h1 class="font-display font-black text-4xl md:text-5xl leading-tight text-aura-carbon mb-4">
                            {{ space.venue_name }}
                        </h1>
                        <p class="text-aura-muted leading-relaxed">{{ space.venue_description }}</p>
                    </div>

                    <hr class="aura-divider" />

                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="aura-label">Ubicación</dt>
                            <dd class="text-sm text-aura-carbon font-medium">{{ space.venue_address }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="aura-label">Capacidad</dt>
                            <dd class="text-sm text-aura-carbon font-medium">{{ space.venue_max_capacity }} personas</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="aura-label">Tarifa</dt>
                            <dd class="text-sm font-bold text-aura-carbon">{{ formatPrice(space.price_per_hour) }}/hora</dd>
                        </div>
                    </dl>

                    <div v-if="space.venue_rules" class="border-l-2 border-aura-carbon pl-4">
                        <p class="aura-label mb-2">Reglamento</p>
                        <p class="text-sm text-aura-muted leading-relaxed">{{ space.venue_rules }}</p>
                    </div>
                </div>
            </section>

            <hr class="aura-divider" />

            <!-- Slots -->
            <section>
                <p class="aura-label mb-2">Disponibilidad</p>
                <h2 class="font-display font-bold text-3xl text-aura-carbon mb-10">Próximos horarios</h2>

                <div v-if="slots.length === 0" class="py-16 text-center">
                    <p class="font-display text-xl text-aura-muted">Sin horarios disponibles en los próximos días.</p>
                </div>

                <div v-else class="space-y-10">
                    <div v-for="(daySlots, date) in groupedSlots" :key="date">
                        <p class="aura-label mb-4 capitalize">{{ formatDayHeader(date) }}</p>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                v-for="slot in daySlots"
                                :key="slot.start"
                                :href="route('reservations.form', { space: space.slug, start: slot.start })"
                                class="btn-ghost"
                            >
                                {{ formatTime(slot.start) }} – {{ formatTime(slot.end) }}
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t aura-border px-8 py-8 text-center mt-16">
            <p class="aura-label">Espacios para Exposiciones · Plataforma de reservas</p>
        </footer>
    </div>
</template>
