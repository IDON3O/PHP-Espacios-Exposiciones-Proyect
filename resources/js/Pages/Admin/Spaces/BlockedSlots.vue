<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ space: Object, blockedSlots: Array });

const form = useForm({ start_time: '', end_time: '', reason: '' });
const submit = () => form.post(route('spaces.blocked-slots.store', props.space.slug), { onSuccess: () => form.reset() });

const deleteForm = useForm({});
const remove = (id) => deleteForm.delete(route('spaces.blocked-slots.destroy', [props.space.slug, id]));

const fmt = (dt) => new Date(dt).toLocaleString('es-CO', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });

const inputClass = "w-full border-0 border-b bg-transparent px-0 py-2 text-sm text-aura-carbon focus:ring-0 focus:outline-none";
const inputStyle = "border-color:rgba(26,26,26,0.2); border-bottom-width:1px;";
</script>

<template>
    <Head :title="`Bloqueos — ${space.venue_name}`" />
    <AppLayout :title="`Bloqueos — ${space.venue_name}`">
        <div class="max-w-2xl mx-auto space-y-10">

            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">Gestión de bloqueos</p>
                <h1 class="font-display font-black text-4xl text-aura-carbon">Horarios bloqueados</h1>
                <p class="text-aura-muted text-sm mt-2">{{ space.venue_name }}</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-8">
                <p class="aura-label">Nuevo bloqueo</p>
                <div class="grid sm:grid-cols-2 gap-8">
                    <div>
                        <p class="aura-label mb-2">Inicio</p>
                        <input type="datetime-local" v-model="form.start_time" :class="inputClass" :style="inputStyle" />
                        <p v-if="form.errors.start_time" class="text-red-600 text-xs mt-1">{{ form.errors.start_time }}</p>
                    </div>
                    <div>
                        <p class="aura-label mb-2">Fin</p>
                        <input type="datetime-local" v-model="form.end_time" :class="inputClass" :style="inputStyle" />
                        <p v-if="form.errors.end_time" class="text-red-600 text-xs mt-1">{{ form.errors.end_time }}</p>
                    </div>
                </div>
                <div>
                    <p class="aura-label mb-2">Motivo</p>
                    <textarea v-model="form.reason" :class="inputClass" :style="inputStyle" rows="3" placeholder="Motivo del bloqueo..."></textarea>
                    <p v-if="form.errors.reason" class="text-red-600 text-xs mt-1">{{ form.errors.reason }}</p>
                </div>
                <div>
                    <button type="submit" class="bg-aura-primary text-white text-sm font-semibold px-6 py-2 rounded-md hover:bg-aura-primary/90 transition disabled:opacity-50" :disabled="form.processing">Bloquear horario</button>
                </div>
            </form>

            <!-- List -->
            <div v-if="blockedSlots.length > 0" class="border-t pt-6">
                <p class="aura-label mb-4">Bloqueos activos</p>
                <div class="space-y-4">
                    <div v-for="slot in blockedSlots" :key="slot.id" class="flex items-center justify-between py-3 border-b" style="border-color:rgba(26,26,26,0.08);">
                        <div>
                            <span class="font-medium text-aura-carbon">{{ fmt(slot.start_time) }} – {{ fmt(slot.end_time) }}</span>
                            <p v-if="slot.reason" class="text-aura-muted text-xs mt-1">{{ slot.reason }}</p>
                        </div>
                        <button @click="remove(slot.id)" class="text-red-600 text-sm hover:underline disabled:opacity-50" :disabled="deleteForm.processing">Eliminar</button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
