<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    event: Object,
    venues: Array,
});

const form = useForm({
    event_name: props.event.event_name,
    event_date: '',
    event_is_virtual: props.event.event_is_virtual,
    event_speaker_name: props.event.event_speaker_name,
    event_max_capacity: props.event.event_max_capacity,
    fk_venue_event: props.event.fk_venue_event,
});

const submit = () => form.put(route('events.update', props.event.id_event));

onMounted(() => {
    if (props.event.event_date) {
        const d = new Date(props.event.event_date);
        d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
        form.event_date = d.toISOString().slice(0, 16);
    }
});
</script>

<template>
    <Head title="Edit Event" />

    <AppLayout title="Edit Event">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">Edit Event</h1>
                    <p class="text-sm text-on-surface-variant mt-1">Update event details</p>
                </div>
                <Link
                    :href="route('events.index')"
                    class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container w-fit"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Back to Events
                </Link>
            </div>

            <section class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="event_name" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Event Name</label>
                        <input
                            id="event_name"
                            type="text"
                            v-model="form.event_name"
                            required
                            autofocus
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                        <InputError class="mt-2" :message="form.errors.event_name" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="event_date" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Event Date</label>
                            <input
                                id="event_date"
                                type="datetime-local"
                                v-model="form.event_date"
                                required
                                class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            >
                            <InputError class="mt-2" :message="form.errors.event_date" />
                        </div>
                        <div>
                            <label for="event_max_capacity" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Max Capacity</label>
                            <input
                                id="event_max_capacity"
                                type="number"
                                min="1"
                                v-model="form.event_max_capacity"
                                required
                                class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                            >
                            <InputError class="mt-2" :message="form.errors.event_max_capacity" />
                        </div>
                    </div>

                    <div>
                        <label for="event_speaker_name" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Speaker Name</label>
                        <input
                            id="event_speaker_name"
                            type="text"
                            v-model="form.event_speaker_name"
                            required
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                        <InputError class="mt-2" :message="form.errors.event_speaker_name" />
                    </div>

                    <div>
                        <label for="fk_venue_event" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Venue</label>
                        <select
                            id="fk_venue_event"
                            v-model="form.fk_venue_event"
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                            <option value="">Select a venue</option>
                            <option v-for="venue in venues" :key="venue.id_venue" :value="venue.id_venue">
                                {{ venue.venue_name }} (Capacity: {{ venue.venue_max_capacity }})
                            </option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.fk_venue_event" />
                    </div>

                    <label class="flex items-center gap-3 p-4 rounded-xl bg-surface-container-low cursor-pointer">
                        <input
                            id="event_is_virtual"
                            type="checkbox"
                            v-model="form.event_is_virtual"
                            class="rounded border-outline-variant text-primary focus:ring-primary/30"
                        >
                        <div>
                            <span class="text-sm font-bold text-on-surface">Virtual event</span>
                            <p class="text-xs text-on-surface-variant">Held online via meet-up URL.</p>
                        </div>
                    </label>
                    <InputError :message="form.errors.event_is_virtual" />

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                        <Link
                            :href="route('events.index')"
                            class="px-4 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container"
                        >Cancel</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-50': form.processing }"
                            class="bg-primary text-on-primary px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                        >
                            <span class="material-symbols-outlined text-base">save</span>
                            Update Event
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </AppLayout>
</template>
