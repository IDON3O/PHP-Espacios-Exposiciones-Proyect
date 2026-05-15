<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    venues: Array,
    message: String,
});

const showDeleteModal = ref(false);
const venueToDelete = ref(null);

const confirmDelete = (venue) => {
    venueToDelete.value = venue;
    showDeleteModal.value = true;
};

const closeModal = () => { showDeleteModal.value = false; };
const deleteForm = useForm({});

const deleteVenue = () => {
    deleteForm.delete(route('venues.destroy', venueToDelete.value.id_venue), {
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
    const headers = ['ID', 'Name', 'Address', 'Maximum Capacity'];
    const rows = props.venues.map(v => [
        v.id_venue,
        v.venue_name,
        v.venue_address,
        v.venue_max_capacity,
    ]);
    const csv = [headers, ...rows].map(r => r.map(csvEscape).join(',')).join('\r\n');
    const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `venues-${new Date().toISOString().slice(0, 10)}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
};
</script>

<template>
    <Head title="Venues" />

    <AppLayout title="Venues">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">Venues</h1>
                    <p class="text-sm text-on-surface-variant mt-1">Manage event venues and capacities</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="exportCSV"
                        :disabled="venues.length === 0"
                        class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container disabled:opacity-50"
                    >
                        <span class="material-symbols-outlined text-base">download</span>
                        Export CSV
                    </button>
                    <Link
                        :href="route('venues.create')"
                        class="bg-primary text-on-primary px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                    >
                        <span class="material-symbols-outlined text-base">add</span>
                        New Venue
                    </Link>
                </div>
            </div>

            <!-- Flash -->
            <div
                v-if="message"
                class="bg-secondary-container text-on-secondary-container px-4 py-3 rounded-xl text-sm font-medium"
                role="alert"
            >{{ message }}</div>

            <!-- Card grid -->
            <div v-if="venues.length === 0" class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-12 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl mb-2 block">stadium</span>
                No venues available yet.
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                <article
                    v-for="venue in venues"
                    :key="venue.id_venue"
                    class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden flex flex-col hover:shadow-lg transition-shadow"
                >
                    <div class="aspect-video w-full bg-surface-container overflow-hidden">
                        <img
                            v-if="venue.venue_image"
                            :src="'/storage/' + venue.venue_image"
                            :alt="venue.venue_name"
                            class="w-full h-full object-cover"
                        >
                        <div v-else class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl">image</span>
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-bold text-on-surface font-headline">{{ venue.venue_name }}</h3>
                            <span class="font-mono text-xs font-bold text-primary shrink-0">#{{ venue.id_venue }}</span>
                        </div>
                        <p class="text-sm text-on-surface-variant flex items-center gap-1 mb-3">
                            <span class="material-symbols-outlined text-base">location_on</span>
                            <span class="truncate">{{ venue.venue_address }}</span>
                        </p>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-base text-secondary">groups</span>
                            <span class="text-sm text-on-surface-variant">Max capacity:</span>
                            <span class="text-sm font-bold text-primary ml-auto">{{ Number(venue.venue_max_capacity).toLocaleString() }}</span>
                        </div>
                        <div class="mt-auto flex items-center gap-1 pt-3 border-t border-outline-variant/30">
                            <Link
                                :href="route('venues.show', venue.id_venue)"
                                class="flex-1 px-3 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors text-center"
                            >View</Link>
                            <Link
                                :href="route('venues.edit', venue.id_venue)"
                                class="flex-1 px-3 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors text-center"
                            >Edit</Link>
                            <button
                                @click="confirmDelete(venue)"
                                class="flex-1 px-3 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-error-container hover:text-error transition-colors"
                            >Delete</button>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <Modal :show="showDeleteModal" @close="closeModal">
            <div class="p-6 bg-surface-container-lowest">
                <h2 class="text-lg font-bold text-on-surface font-headline">
                    Delete this venue?
                </h2>
                <p class="mt-1 text-sm text-on-surface-variant">
                    This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                    <DangerButton
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deleteVenue"
                    >Delete</DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
