<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    venue: Object,
});
</script>

<template>
    <Head title="Venue Details" />

    <AppLayout title="Venue Details">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">{{ venue.venue_name }}</h1>
                    <p class="text-sm text-on-surface-variant mt-1 font-mono">#{{ venue.id_venue }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link
                        :href="route('venues.index')"
                        class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container"
                    >
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Back
                    </Link>
                    <Link
                        :href="route('venues.edit', venue.id_venue)"
                        class="bg-primary text-on-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                    >
                        <span class="material-symbols-outlined text-base">edit</span>
                        Edit
                    </Link>
                </div>
            </div>

            <section class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden">
                <div v-if="venue.venue_image" class="aspect-video w-full bg-surface-container">
                    <img :src="'/storage/' + venue.venue_image" :alt="venue.venue_name" class="w-full h-full object-cover">
                </div>
                <div class="p-6 sm:p-8">
                    <h2 class="text-lg font-extrabold text-primary font-headline mb-6">Venue Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Address</dt>
                            <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-primary">location_on</span>
                                {{ venue.venue_address }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Maximum Capacity</dt>
                            <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-base text-primary">groups</span>
                                {{ Number(venue.venue_max_capacity).toLocaleString() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
