<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    event: Object,
});

const formatDate = (s) => {
    const d = new Date(s);
    return d.toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Event Details" />

    <AppLayout title="Event Details">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">{{ event.event_name }}</h1>
                    <p class="text-sm text-on-surface-variant mt-1 font-mono">#{{ event.id_event }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <Link
                        :href="route('events.index')"
                        class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container"
                    >
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Back
                    </Link>
                    <Link
                        :href="route('events.edit', event.id_event)"
                        class="bg-primary text-on-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                    >
                        <span class="material-symbols-outlined text-base">edit</span>
                        Edit
                    </Link>
                </div>
            </div>

            <section class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 sm:p-8">
                <h2 class="text-lg font-extrabold text-primary font-headline mb-6">Event Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Date &amp; Time</dt>
                        <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">schedule</span>
                            {{ formatDate(event.event_date) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Modality</dt>
                        <dd class="mt-1">
                            <span
                                v-if="event.event_is_virtual"
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-fixed/50 text-primary rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                            >
                                <span class="material-symbols-outlined text-[14px]">videocam</span> Virtual
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                            >
                                <span class="material-symbols-outlined text-[14px]">place</span> In-person
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Speaker</dt>
                        <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">person</span>
                            {{ event.event_speaker_name }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Max Capacity</dt>
                        <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">groups</span>
                            {{ event.event_max_capacity }}
                        </dd>
                    </div>

                    <div class="sm:col-span-2">
                        <dt class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Venue</dt>
                        <dd class="mt-1 text-sm text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-base text-primary">stadium</span>
                            {{ event.venue ? event.venue.venue_name : 'No venue assigned' }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>
    </AppLayout>
</template>
