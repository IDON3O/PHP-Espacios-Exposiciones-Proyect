<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ space: Object, availabilities: Array });

const days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

const form = useForm({ day_of_week: 1, start_time: '08:00', end_time: '18:00' });
const submit = () => form.post(route('spaces.availabilities.store', props.space.slug), { onSuccess: () => form.reset() });

const deleteForm = useForm({});
const remove = (id) => deleteForm.delete(route('spaces.availabilities.destroy', [props.space.slug, id]));

const inputClass = "border-0 border-b bg-transparent px-0 py-2 text-sm text-aura-carbon focus:ring-0 focus:outline-none";
const inputStyle = "border-color:rgba(26,26,26,0.2); border-bottom-width:1px;";
</script>

<template>
    <Head :title="`Disponibilidad — ${space.venue_name}`" />
    <AppLayout :title="`Disponibilidad — ${space.venue_name}`">
        <div class="max-w-2xl mx-auto space-y-10">

            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">Configuración</p>
                <h1 class="font-display font-black text-4xl text-aura-carbon">Disponibilidad semanal</h1>
                <p class="text-aura-muted text-sm mt-2">{{ space.venue_name }}</p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="flex flex-wrap items-end gap-8">
                <div>
                    <p class="aura-label mb-2">Día</p>
                    <select v-model="form.day_of_week" :class="inputClass" :style="inputStyle">
                        <option v-for="(day, i) in days" :key="i" :value="i">{{ day }}</option>
                    </select>
                    <p v-if="form.errors.day_of_week" class="text-red-600 text-xs mt-1">{{ form.errors.day_of_week }}</p>
                </div>
                <div>
                    <p class="aura-label mb-2">Inicio</p>
                    <input type="time" v-model="form.start_time" :class="inputClass" :style="inputStyle" />
                    <p v-if="form.errors.start_time" class="text-red-600 text-xs mt-1">{{ form.errors.start_time }}</p>
                </div>
                <div>
                    <p class="aura-label mb-2">Fin</p>
                    <input type="time" v-model="form.end_time" :class="inputClass" :style="inputStyle" />
                    <p v-if="form.errors.end_time" class="text-red-600 text-xs mt-1">{{ form.errors.end_time }}</p>
                </div>
                <div class="pb-2">
                    <button type="submit" class="bg-white text-aura-carbon text-sm font-semibold px-6 py-2 rounded-md border border-aura-carbon hover:bg-gray-100 transition disabled:opacity-50" :disabled="form.processing">Guardar</button>
                </div>
            </form>

            <!-- List -->
            <div v-if="availabilities.length > 0" class="border-t pt-6">
                <p class="aura-label mb-4">Horarios configurados</p>
                <div class="space-y-4">
                    <div v-for="a in availabilities" :key="a.id" class="flex items-center justify-between py-3 border-b" style="border-color:rgba(26,26,26,0.08);">
                        <div>
                            <span class="font-medium text-aura-carbon">{{ days[a.day_of_week] }}</span>
                            <span class="text-aura-muted text-sm ml-2">{{ a.start_time }} – {{ a.end_time }}</span>
                        </div>
                        <button @click="remove(a.id)" class="text-red-600 text-sm hover:underline disabled:opacity-50" :disabled="deleteForm.processing">Eliminar</button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
