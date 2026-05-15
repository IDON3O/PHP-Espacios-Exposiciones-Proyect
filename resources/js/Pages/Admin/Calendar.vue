<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    spaces: Array, space: Object,
    reservations: Array, blockedSlots: Array,
    weekStart: String, weekEnd: String,
});

const selectedSpace = ref(props.space?.slug ?? '');

const navigate = (offset) => {
    const d = new Date(props.weekStart + 'T12:00:00');
    d.setDate(d.getDate() + offset * 7);
    router.get(route('calendar.index'), { space: selectedSpace.value, week: d.toISOString().slice(0, 10) });
};

const changeSpace = () => router.get(route('calendar.index'), { space: selectedSpace.value, week: props.weekStart });

const weekDays = computed(() => {
    const days = [];
    const start = new Date(props.weekStart + 'T12:00:00');
    for (let i = 0; i < 7; i++) {
        const d = new Date(start);
        d.setDate(start.getDate() + i);
        days.push(d);
    }
    return days;
});

const eventsForDay  = (day) => props.reservations.filter(r => r.start_time.slice(0,10) === day.toISOString().slice(0,10));
const blockedForDay = (day) => props.blockedSlots.filter(b => b.start_time.slice(0,10) === day.toISOString().slice(0,10));

const fmt      = (dt) => new Date(dt).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' });
const dayNames = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];

const statusStyle = (s) => ({
    pending:   'border-color:#b8965a; background:rgba(184,150,90,0.06);',
    confirmed: 'border-color:#2d6a4f; background:rgba(45,106,79,0.06);',
}[s] ?? '');

const statusColor = (s) => ({
    pending:   'color:#b8965a;',
    confirmed: 'color:#2d6a4f;',
}[s] ?? 'color:#1a1a1a;');
</script>

<template>
    <Head title="Calendario" />
    <AppLayout title="Calendario">
        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Heading -->
            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">Vista semanal</p>
                <h1 class="font-display font-black text-5xl text-aura-carbon">Calendario</h1>
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap items-end gap-6">
                <div>
                    <p class="aura-label mb-2">Espacio</p>
                    <select
                        v-model="selectedSpace" @change="changeSpace"
                        class="border-0 border-b bg-transparent px-0 py-1.5 text-sm text-aura-carbon focus:ring-0 focus:outline-none"
                        style="border-color:rgba(26,26,26,0.2); border-bottom-width:1px;"
                    >
                        <option v-for="s in spaces" :key="s.id_venue" :value="s.slug">{{ s.venue_name }}</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 ml-auto">
                    <p class="aura-label hidden sm:block">
                        {{ new Date(weekStart+'T12:00:00').toLocaleDateString('es-CO',{day:'numeric',month:'long'}) }}
                        –
                        {{ new Date(weekEnd+'T12:00:00').toLocaleDateString('es-CO',{day:'numeric',month:'long',year:'numeric'}) }}
                    </p>
                    <button @click="navigate(-1)" class="btn-ghost py-1.5 px-4">←</button>
                    <button @click="navigate(1)"  class="btn-ghost py-1.5 px-4">→</button>
                </div>
            </div>

            <!-- Calendar grid -->
            <div class="grid grid-cols-7 border-t border-l" style="border-color:rgba(26,26,26,0.08);">
                <!-- Day headers -->
                <div
                    v-for="day in weekDays" :key="'h'+day"
                    class="border-r border-b px-2 py-3 text-center"
                    style="border-color:rgba(26,26,26,0.08);"
                >
                    <p class="aura-label">{{ dayNames[day.getDay()] }}</p>
                    <p class="font-display font-bold text-lg text-aura-carbon mt-0.5">{{ day.getDate() }}</p>
                </div>

                <!-- Day cells -->
                <div
                    v-for="day in weekDays" :key="'c'+day"
                    class="border-r border-b px-2 py-3 min-h-32 space-y-1.5"
                    style="border-color:rgba(26,26,26,0.08);"
                >
                    <!-- Reservas -->
                    <div
                        v-for="r in eventsForDay(day)" :key="r.id"
                        class="border px-2 py-1.5"
                        :style="statusStyle(r.status)"
                    >
                        <p class="text-[10px] font-bold" :style="statusColor(r.status)">
                            {{ fmt(r.start_time) }}–{{ fmt(r.end_time) }}
                        </p>
                        <p class="text-[10px] truncate" style="color:#1a1a1a;">{{ r.user_name }}</p>
                    </div>

                    <!-- Bloqueos -->
                    <div
                        v-for="b in blockedForDay(day)" :key="'b'+b.id"
                        class="border px-2 py-1.5"
                        style="border-color:rgba(155,34,38,0.3); background:rgba(155,34,38,0.04);"
                    >
                        <p class="text-[10px] font-bold" style="color:#9b2226;">
                            {{ fmt(b.start_time) }}–{{ fmt(b.end_time) }}
                        </p>
                        <p class="text-[10px] truncate" style="color:#9b2226;">🔒 {{ b.reason ?? 'Bloqueado' }}</p>
                    </div>

                    <p
                        v-if="eventsForDay(day).length === 0 && blockedForDay(day).length === 0"
                        class="aura-label text-center pt-4"
                    >—</p>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 border" style="border-color:#b8965a; background:rgba(184,150,90,0.06);"></div>
                    <span class="aura-label">Pendiente</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 border" style="border-color:#2d6a4f; background:rgba(45,106,79,0.06);"></div>
                    <span class="aura-label">Confirmada</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 border" style="border-color:#9b2226; background:rgba(155,34,38,0.04);"></div>
                    <span class="aura-label">Bloqueado</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
