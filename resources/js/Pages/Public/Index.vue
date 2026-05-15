<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
  spaces: Array,
  filterDate: String|null
});

const formatPrice = (p) =>
    p > 0
        ? new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(p)
        : 'Entrada libre';

const form = useForm({
  date: props.filterDate || ''
});

const trackForm = useForm({
  slug: ''
});

const submit = () => {
  form.get(route('home'), {
    preserveState: true,
    preserveScroll: true
  });
};

const reset = () => {
  form.date = '';
  submit();
};

const trackReservation = () => {
  if (trackForm.slug.trim()) {
    window.location.href = route('reservations.show', trackForm.slug.trim());
  }
};
</script>

<template>
    <Head title="Espacios para Exposiciones" />

    <div class="aura min-h-screen">

        <!-- Nav -->
                        <nav class="border-b aura-border px-8 py-5 flex items-center justify-between">
                            <span class="font-display font-bold text-lg tracking-tight text-aura-carbon">
                                Espacios · Exposiciones
                            </span>
                            <div class="flex items-center gap-4">
                                <form @submit.prevent="trackReservation" class="flex items-center gap-2">
                                    <input
                                        type="text"
                                        v-model="trackForm.slug"
                                        placeholder="Código de reserva"
                                        class="w-32 border-0 border-b border-aura-carbon/30 bg-transparent px-0 py-1 text-xs text-aura-carbon placeholder-aura-muted/50 focus:border-aura-carbon focus:ring-0 transition-colors duration-300"
                                    />
                                    <button
                                        type="submit"
                                        class="aura-label hover:text-aura-carbon transition-colors duration-300"
                                        title="Consultar estado"
                                    >
                                        Buscar
                                    </button>
                                </form>
                                <span class="text-aura-grey">·</span>
                                <a href="/login" class="aura-label hover:text-aura-carbon transition-colors duration-300">
                                    Admin →
                                </a>
                            </div>
                        </nav>

         <!-- Hero -->
         <header class="px-8 pt-24 pb-16 max-w-5xl mx-auto">
             <p class="aura-label mb-4">Colección activa · {{ spaces.length }} espacios</p>
             <h1 class="font-display font-black text-5xl md:text-7xl leading-[1.05] tracking-tight text-aura-carbon mb-6">
                 Espacios para<br>Exposiciones
             </h1>
             <p class="text-aura-muted text-lg max-w-xl leading-relaxed">
                 Ambientes diseñados para dar vida a tu obra. Reserva el espacio ideal y lleva tu exposición al siguiente nivel.
             </p>

             <!-- Date filter -->
             <div class="mt-6 flex flex-col sm:flex-row sm:items-start sm:gap-4">
                 <label for="date-filter" class="block mb-2 text-sm font-medium text-aura-carbon">
                     Filtrar por fecha de disponibilidad
                 </label>
                 <div class="flex w-full sm:w-auto">
                     <input
                         type="date"
                         id="date-filter"
                         v-model="form.date"
                         @change="submit"
                         class="border-0 border-b bg-transparent px-0 py-2 text-sm text-aura-carbon focus:ring-0 focus:outline-none"
                         :style="{ borderColor: 'rgba(26,26,26,0.2)', borderBottomWidth: '1px' }"
                         :min="new Date().toISOString().split('T')[0]"
                     >
                     <button
                         v-if="form.date"
                         @click="reset"
                         class="mt-2 sm:mt-0 sm:ml-2 text-aura-muted hover:text-aura-carbon underline"
                     >
                         Limpiar filtro
                     </button>
                 </div>
             </div>
         </header>

        <hr class="aura-divider mx-8" />

        <!-- Grid -->
        <main class="px-8 py-16 max-w-6xl mx-auto">
            <div v-if="spaces.length === 0" class="text-center py-32 text-aura-muted">
                <p class="font-display text-2xl">Sin espacios disponibles</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                <article v-for="space in spaces" :key="space.id_venue" class="group flex flex-col">

                    <!-- Imagen 4:5 -->
                    <div class="aura-card-img aura-border mb-5" style="aspect-ratio:4/5;">
                        <img
                            v-if="space.venue_image"
                            :src="'/storage/' + space.venue_image"
                            :alt="space.venue_name"
                        />
                        <div v-else class="w-full h-full bg-aura-grey flex items-center justify-center">
                            <span class="aura-label">Sin imagen</span>
                        </div>
                    </div>

                    <!-- Meta -->
                    <div class="flex items-start justify-between gap-4 mb-2">
                        <h2 class="font-display font-bold text-xl leading-tight text-aura-carbon">
                            {{ space.venue_name }}
                        </h2>
                        <span class="aura-label shrink-0 mt-1">{{ formatPrice(space.price_per_hour) }}/h</span>
                    </div>

                    <p class="text-sm text-aura-muted leading-relaxed mb-1 flex-1">
                        {{ space.venue_description }}
                    </p>

                    <p class="aura-label mb-5">
                        Cap. {{ space.venue_max_capacity }} personas · {{ space.venue_address }}
                    </p>

                    <Link
                        :href="route('spaces.show', space.slug)"
                        class="btn-ghost w-full justify-center"
                    >
                        Ver disponibilidad
                    </Link>
                </article>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t aura-border px-8 py-8 text-center">
            <p class="aura-label">Espacios para Exposiciones · Plataforma de reservas</p>
        </footer>
    </div>
</template>
