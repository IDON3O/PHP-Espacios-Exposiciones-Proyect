<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ reservations: Object, spaces: Array, filters: Object });

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

const status = ref(props.filters?.status ?? '');
const space  = ref(props.filters?.space  ?? '');
const date   = ref(props.filters?.date   ?? '');

const applyFilters = () => {
    router.get(route('reservations.index'), { status: status.value, space: space.value, date: date.value }, { preserveState: true });
};

const actionForm = useForm({});
const doAction = (action, slug) => actionForm.post(route(`reservations.${action}`, slug));

const selectStyle = "border-0 border-b bg-transparent text-xs text-aura-carbon focus:ring-0 focus:outline-none py-1.5 px-0 pr-6";
const selectBorderStyle = "border-color:rgba(26,26,26,0.2); border-bottom-width:1px;";
</script>

<template>
    <Head title="Reservas" />
    <AppLayout title="Reservas">
        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Heading -->
            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">Gestión</p>
                <h1 class="font-display font-black text-5xl text-aura-carbon">Reservas</h1>
            </div>

            <!-- Filtros -->
            <div class="flex flex-wrap items-end gap-8">
                <div>
                    <p class="aura-label mb-2">Estado</p>
                    <select v-model="status" :class="selectStyle" :style="selectBorderStyle">
                        <option value="">Todos</option>
                        <option value="pending">Pendiente</option>
                        <option value="confirmed">Confirmada</option>
                        <option value="rejected">Rechazada</option>
                        <option value="cancelled">Cancelada</option>
                        <option value="finished">Finalizada</option>
                    </select>
                </div>
                <div>
                    <p class="aura-label mb-2">Espacio</p>
                    <select v-model="space" :class="selectStyle" :style="selectBorderStyle">
                        <option value="">Todos</option>
                        <option v-for="s in spaces" :key="s.id_venue" :value="s.slug">{{ s.venue_name }}</option>
                    </select>
                </div>
                <div>
                    <p class="aura-label mb-2">Fecha</p>
                    <input type="date" v-model="date" :class="selectStyle" :style="selectBorderStyle"/>
                </div>
                <button @click="applyFilters" class="btn-ghost">Filtrar</button>
            </div>

            <!-- Lista -->
            <div class="border-t" style="border-color:rgba(26,26,26,0.08);">
                <div v-if="reservations.data.length === 0" class="py-20 text-center aura-label">
                    No hay reservas que coincidan.
                </div>

                <div
                    v-for="r in reservations.data" :key="r.id"
                    class="grid grid-cols-4 items-start gap-6 py-6 border-b"
                    style="border-color:rgba(26,26,26,0.08); grid-template-columns: 1fr 1fr auto auto;"
                >
                    <!-- Solicitante -->
                    <div class="space-y-0.5">
                        <p class="font-semibold text-sm text-aura-carbon">{{ r.user_name }}</p>
                        <p class="aura-label">{{ r.user_email }}</p>
                        <p class="aura-label md:hidden">{{ fmt(r.start_time) }} → {{ fmt(r.end_time) }}</p>
                    </div>

                    <!-- Espacio + fecha -->
                    <div class="space-y-0.5">
                        <p class="text-sm text-aura-carbon font-medium">{{ r.venue?.venue_name }}</p>
                        <p class="aura-label">{{ fmt(r.start_time) }} → {{ fmt(r.end_time) }}</p>
                    </div>

                    <!-- Estado — columna fija -->
                    <div class="flex items-start pt-0.5">
                        <span class="aura-pill" :style="statusStyle(r.status)">{{ statusLabel(r.status) }}</span>
                    </div>

                    <!-- Acciones — columna fija, altura mínima garantizada -->
                    <div class="flex flex-col gap-2 items-start min-w-[80px]">
                        <button
                            v-if="r.status === 'pending'"
                            @click="doAction('accept', r.slug)"
                            :disabled="actionForm.processing"
                            class="aura-label transition-colors duration-300 hover:text-aura-carbon"
                        >Aprobar</button>
                        <button
                            v-if="r.status === 'pending'"
                            @click="doAction('reject', r.slug)"
                            :disabled="actionForm.processing"
                            class="aura-label transition-colors duration-300 hover:text-red-700"
                        >Rechazar</button>
                        <button
                            v-if="r.status === 'confirmed'"
                            @click="doAction('cancel', r.slug)"
                            :disabled="actionForm.processing"
                            class="aura-label transition-colors duration-300 hover:text-aura-carbon"
                        >Cancelar</button>
                        <!-- Placeholder para filas sin acciones -->
                        <span v-if="!['pending','confirmed'].includes(r.status)" class="aura-label">—</span>
                    </div>
                </div>
            </div>

            <!-- Paginación -->
            <div class="flex gap-1">
                <Link
                    v-for="link in reservations.links" :key="link.label"
                    :href="link.url ?? '#'"
                    v-html="link.label"
                    class="px-3 py-1.5 text-xs font-semibold border transition-colors duration-300"
                    :style="link.active
                        ? 'background:#1a1a1a; color:#fbf9f9; border-color:#1a1a1a;'
                        : 'background:transparent; color:#6b6b6b; border-color:rgba(26,26,26,0.15);'"
                />
            </div>
        </div>
    </AppLayout>
</template>
