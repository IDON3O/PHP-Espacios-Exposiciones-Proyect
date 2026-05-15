<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({ spaces: Array, message: String });

const toDelete  = ref(null);
const deleteForm = useForm({});

const deleteSpace = () => {
    deleteForm.delete(route('spaces.destroy', toDelete.value.slug), {
        onSuccess: () => toDelete.value = null,
    });
};

const formatPrice = (p) =>
    p > 0 ? new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(p) : 'Gratis';
</script>

<template>
    <Head title="Espacios" />
    <AppLayout title="Espacios">
        <div class="max-w-5xl mx-auto space-y-10">

            <!-- Heading -->
            <div class="flex items-end justify-between border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <div>
                    <p class="aura-label mb-3">Gestión</p>
                    <h1 class="font-display font-black text-5xl text-aura-carbon">Espacios</h1>
                </div>
                <Link :href="route('spaces.create')" class="btn-solid">+ Nuevo espacio</Link>
            </div>

            <!-- Flash -->
            <div v-if="message" class="text-xs font-semibold tracking-wide py-3 px-4 border" style="border-color:rgba(26,26,26,0.12); color:#2d6a4f; background:rgba(45,106,79,0.05);">
                {{ message }}
            </div>

            <!-- Table -->
            <div class="border-t" style="border-color:rgba(26,26,26,0.08);">
                <div
                    v-for="space in spaces" :key="space.id_venue"
                    class="flex items-center gap-6 py-6 border-b"
                    style="border-color:rgba(26,26,26,0.08);"
                >
                    <!-- Image thumb -->
                    <div class="w-16 h-16 shrink-0 overflow-hidden border" style="border-color:rgba(26,26,26,0.08); background:#dbdad9;">
                        <img v-if="space.venue_image" :src="'/storage/'+space.venue_image" :alt="space.venue_name" class="w-full h-full object-cover"/>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-aura-carbon text-sm">{{ space.venue_name }}</p>
                        <p class="aura-label mt-0.5">{{ space.venue_address }}</p>
                    </div>

                    <!-- Cap -->
                    <div class="hidden md:block text-center">
                        <p class="font-display font-bold text-xl text-aura-carbon">{{ space.venue_max_capacity }}</p>
                        <p class="aura-label">personas</p>
                    </div>

                    <!-- Price -->
                    <div class="hidden md:block text-center">
                        <p class="font-semibold text-sm text-aura-carbon">{{ formatPrice(space.price_per_hour) }}</p>
                        <p class="aura-label">por hora</p>
                    </div>

                    <!-- Status -->
                    <span class="aura-pill hidden sm:inline-block"
                          :style="space.is_active ? 'color:#2d6a4f; border-color:#2d6a4f;' : 'color:#6b6b6b; border-color:#6b6b6b;'">
                        {{ space.is_active ? 'Activo' : 'Inactivo' }}
                    </span>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 shrink-0">
                        <Link :href="route('spaces.availabilities.index', space.slug)" class="aura-label hover:text-aura-carbon transition-colors duration-300">Horarios</Link>
                        <Link :href="route('spaces.blocked-slots.index', space.slug)" class="aura-label hover:text-aura-carbon transition-colors duration-300">Bloqueos</Link>
                        <Link :href="route('spaces.edit', space.slug)" class="aura-label hover:text-aura-carbon transition-colors duration-300">Editar</Link>
                        <button @click="toDelete = space" class="aura-label transition-colors duration-300 hover:text-red-700">Eliminar</button>
                    </div>
                </div>

                <div v-if="spaces.length === 0" class="py-20 text-center aura-label">
                    Sin espacios registrados.
                </div>
            </div>
        </div>

        <!-- Delete modal -->
        <div v-if="toDelete" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="background:rgba(26,26,26,0.5);">
            <div class="max-w-sm w-full p-10 space-y-6" style="background:#fbf9f9;">
                <p class="aura-label">Confirmar eliminación</p>
                <h2 class="font-display font-bold text-2xl text-aura-carbon">¿Eliminar «{{ toDelete.venue_name }}»?</h2>
                <p class="text-sm text-aura-muted">Esta acción no se puede deshacer.</p>
                <div class="flex gap-3 pt-2">
                    <button @click="toDelete = null" class="btn-ghost flex-1 justify-center">Cancelar</button>
                    <button @click="deleteSpace" :disabled="deleteForm.processing" class="btn-solid flex-1 justify-center">Eliminar</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
