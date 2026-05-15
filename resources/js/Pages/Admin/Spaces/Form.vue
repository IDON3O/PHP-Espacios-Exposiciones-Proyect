<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ space: Object });

const form = useForm({
    venue_name:         props.space?.venue_name         ?? '',
    venue_type:         props.space?.venue_type         ?? 'exhibition',
    venue_description:  props.space?.venue_description  ?? '',
    venue_rules:        props.space?.venue_rules         ?? '',
    venue_address:      props.space?.venue_address      ?? '',
    venue_max_capacity: props.space?.venue_max_capacity ?? '',
    price_per_hour:     props.space?.price_per_hour     ?? 0,
    is_active:          props.space?.is_active          ?? true,
    venue_image:        null,
});

const submit = () => {
    if (props.space) {
        form.put(route('spaces.update', props.space.slug), { forceFormData: true });
    } else {
        form.post(route('spaces.store'), { forceFormData: true });
    }
};

const inputClass = "w-full border-0 border-b bg-transparent px-0 py-2.5 text-sm text-aura-carbon placeholder-aura-muted/40 focus:ring-0 focus:outline-none transition-colors duration-300";
const inputStyle = "border-color:rgba(26,26,26,0.2); border-bottom-width:1px;";
</script>

<template>
    <Head :title="space ? 'Editar espacio' : 'Nuevo espacio'" />
    <AppLayout :title="space ? 'Editar espacio' : 'Nuevo espacio'">
        <div class="max-w-2xl mx-auto space-y-10">

            <!-- Heading -->
            <div class="border-b pb-8" style="border-color:rgba(26,26,26,0.08);">
                <p class="aura-label mb-3">{{ space ? 'Editar' : 'Nuevo' }}</p>
                <h1 class="font-display font-black text-5xl text-aura-carbon">
                    {{ space ? space.venue_name : 'Nuevo espacio' }}
                </h1>
            </div>

            <form @submit.prevent="submit" class="space-y-10">

                <div class="space-y-8">
                    <p class="aura-label">Información general</p>

                    <div class="grid sm:grid-cols-2 gap-8">
                        <div>
                            <label class="aura-label block mb-3">Nombre</label>
                            <input v-model="form.venue_name" type="text" :class="inputClass" :style="inputStyle" placeholder="Nombre del espacio"/>
                            <p v-if="form.errors.venue_name" class="text-red-600 text-xs mt-1">{{ form.errors.venue_name }}</p>
                        </div>
                        <div>
                            <label class="aura-label block mb-3">Tipo</label>
                            <input v-model="form.venue_type" type="text" :class="inputClass" :style="inputStyle" placeholder="exhibition"/>
                        </div>
                    </div>

                    <div>
                        <label class="aura-label block mb-3">Dirección</label>
                        <input v-model="form.venue_address" type="text" :class="inputClass" :style="inputStyle" placeholder="Ubicación del espacio"/>
                        <p v-if="form.errors.venue_address" class="text-red-600 text-xs mt-1">{{ form.errors.venue_address }}</p>
                    </div>

                    <div>
                        <label class="aura-label block mb-3">Descripción</label>
                        <textarea v-model="form.venue_description" rows="3" :class="inputClass" :style="inputStyle" placeholder="Descripción del espacio..." class="resize-none"/>
                    </div>

                    <div>
                        <label class="aura-label block mb-3">Reglas de uso</label>
                        <textarea v-model="form.venue_rules" rows="3" :class="inputClass" :style="inputStyle" placeholder="Normas y restricciones..." class="resize-none"/>
                    </div>
                </div>

                <hr style="border-color:rgba(26,26,26,0.08);" />

                <div class="space-y-8">
                    <p class="aura-label">Capacidad y tarifa</p>

                    <div class="grid sm:grid-cols-2 gap-8">
                        <div>
                            <label class="aura-label block mb-3">Capacidad máxima</label>
                            <input v-model="form.venue_max_capacity" type="number" min="1" :class="inputClass" :style="inputStyle" placeholder="0"/>
                            <p v-if="form.errors.venue_max_capacity" class="text-red-600 text-xs mt-1">{{ form.errors.venue_max_capacity }}</p>
                        </div>
                        <div>
                            <label class="aura-label block mb-3">Precio por hora (COP)</label>
                            <input v-model="form.price_per_hour" type="number" min="0" step="1000" :class="inputClass" :style="inputStyle" placeholder="0"/>
                            <p v-if="form.errors.price_per_hour" class="text-red-600 text-xs mt-1">{{ form.errors.price_per_hour }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <input v-model="form.is_active" type="checkbox" id="is_active" class="rounded-none border" style="border-color:rgba(26,26,26,0.3); color:#1a1a1a;"/>
                        <label for="is_active" class="aura-label cursor-pointer">Espacio activo y visible al público</label>
                    </div>
                </div>

                <hr style="border-color:rgba(26,26,26,0.08);" />

                <div class="space-y-4">
                    <p class="aura-label">Imagen</p>
                    <input
                        type="file" accept="image/*"
                        @change="e => form.venue_image = e.target.files[0]"
                        class="w-full text-xs text-aura-muted file:mr-4 file:py-2 file:px-4 file:border file:text-xs file:font-semibold file:tracking-widest file:uppercase file:cursor-pointer file:transition-colors file:duration-300"
                        style="file:border-color:rgba(26,26,26,0.3); file:background:transparent; file:color:#1a1a1a;"
                    />
                </div>

                <div class="flex gap-4 pt-4">
                    <a :href="route('spaces.index')" class="btn-ghost">Cancelar</a>
                    <button type="submit" :disabled="form.processing" class="btn-solid disabled:opacity-40">
                        {{ form.processing ? 'Guardando...' : (space ? 'Actualizar espacio' : 'Crear espacio') }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
