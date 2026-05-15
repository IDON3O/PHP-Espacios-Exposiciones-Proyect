<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    events: Array,
    venues: Array,
    message: String,
});

const selectedVenue = ref('');
const showDeleteModal = ref(false);
const eventToDelete = ref(null);

const filteredEvents = computed(() => {
    if (!selectedVenue.value) return props.events;
    return props.events.filter(e => e.fk_venue_event == selectedVenue.value);
});

const createEventLink = computed(() => {
    const baseUrl = route('events.create');
    return selectedVenue.value ? `${baseUrl}?venue_id=${selectedVenue.value}` : baseUrl;
});

const formatDate = (s) => {
    const d = new Date(s);
    return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const confirmDelete = (event) => {
    eventToDelete.value = event;
    showDeleteModal.value = true;
};

const closeModal = () => { showDeleteModal.value = false; };

const deleteForm = useForm({});

const deleteEvent = () => {
    deleteForm.delete(route('events.destroy', eventToDelete.value.id_event), {
        onSuccess: () => { showDeleteModal.value = false; },
    });
};

// --- CSV export -------------------------------------------------------------
const csvEscape = (val) => {
    if (val === null || val === undefined) return '';
    const s = String(val);
    return /[",\n\r]/.test(s) ? `"${s.replace(/"/g, '""')}"` : s;
};

const exportCSV = () => {
    const headers = ['ID', 'Name', 'Date', 'Virtual', 'Speaker', 'Max Capacity', 'Venue', 'Status'];
    const rows = filteredEvents.value.map(e => [
        e.id_event,
        e.event_name,
        e.event_date,
        e.event_is_virtual ? 'Yes' : 'No',
        e.event_speaker_name,
        e.event_max_capacity,
        e.venue?.venue_name ?? '',
        e.event_status ? 'Active' : 'Inactive',
    ]);
    const csv = [headers, ...rows].map(r => r.map(csvEscape).join(',')).join('\r\n');
    const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `events-${new Date().toISOString().slice(0, 10)}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
};
</script>

<template>
    <Head title="Events" />

    <AppLayout title="Events">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">Events</h1>
                    <p class="text-sm text-on-surface-variant mt-1">Manage all scheduled events</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="exportCSV"
                        :disabled="filteredEvents.length === 0"
                        class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container disabled:opacity-50"
                    >
                        <span class="material-symbols-outlined text-base">download</span>
                        Export CSV
                    </button>
                    <Link
                        :href="createEventLink"
                        class="bg-primary text-on-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                    >
                        <span class="material-symbols-outlined text-base">add</span>
                        New Event
                    </Link>
                </div>
            </div>

            <!-- Flash -->
            <div
                v-if="message"
                class="bg-secondary-container text-on-secondary-container px-4 py-3 rounded-xl text-sm font-medium"
                role="alert"
            >{{ message }}</div>

            <!-- Filters -->
            <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center gap-3">
                <label for="venue-filter" class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">Filter by Venue</label>
                <select
                    id="venue-filter"
                    v-model="selectedVenue"
                    class="flex-1 sm:flex-none sm:min-w-64 border-outline-variant bg-surface-container-low text-on-surface rounded-xl text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary"
                >
                    <option value="">All Venues</option>
                    <option v-for="venue in venues" :key="venue.id_venue" :value="venue.id_venue">
                        {{ venue.venue_name }}
                    </option>
                </select>
                <span class="text-xs text-on-surface-variant sm:ml-auto">
                    Showing <strong class="text-primary">{{ filteredEvents.length }}</strong> of {{ events.length }}
                </span>
            </div>

            <!-- Table -->
            <section class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden">
                <div v-if="filteredEvents.length === 0" class="p-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl mb-2 block">event_busy</span>
                    No events match the current filter.
                </div>
                <div v-else class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low">
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">ID</th>
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Event</th>
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Date</th>
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Modality</th>
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Speaker</th>
                                <th class="px-6 py-4 text-left text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Venue</th>
                                <th class="px-6 py-4 text-right text-[10px] font-extrabold text-on-surface-variant uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/20">
                            <tr v-for="event in filteredEvents" :key="event.id_event" class="hover:bg-primary-fixed/20 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm font-bold text-primary">#{{ event.id_event }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-on-surface">{{ event.event_name }}</td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant whitespace-nowrap">{{ formatDate(event.event_date) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="event.event_is_virtual"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-fixed/50 text-primary rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                                    >
                                        <span class="material-symbols-outlined text-[14px]">videocam</span>
                                        Virtual
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                                    >
                                        <span class="material-symbols-outlined text-[14px]">place</span>
                                        In-person
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ event.event_speaker_name }}</td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant">{{ event.venue?.venue_name || '—' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <Link
                                            :href="route('events.show', event.id_event)"
                                            class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors"
                                            title="View"
                                        >
                                            <span class="material-symbols-outlined text-base">visibility</span>
                                        </Link>
                                        <Link
                                            :href="route('events.edit', event.id_event)"
                                            class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors"
                                            title="Edit"
                                        >
                                            <span class="material-symbols-outlined text-base">edit</span>
                                        </Link>
                                        <button
                                            @click="confirmDelete(event)"
                                            class="p-2 rounded-xl text-on-surface-variant hover:bg-error-container hover:text-error transition-colors"
                                            title="Delete"
                                        >
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <Modal :show="showDeleteModal" @close="closeModal">
            <div class="p-6 bg-surface-container-lowest">
                <h2 class="text-lg font-bold text-on-surface font-headline">
                    Delete this event?
                </h2>
                <p class="mt-1 text-sm text-on-surface-variant">
                    This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <DangerButton
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deleteEvent"
                    >Delete</DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
