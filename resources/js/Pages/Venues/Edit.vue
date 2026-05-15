<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    venue: Object,
});

const form = useForm({
    _method: 'put',
    venue_name: props.venue.venue_name,
    venue_address: props.venue.venue_address,
    venue_max_capacity: props.venue.venue_max_capacity,
    venue_image: null,
    imagePreview: props.venue.venue_image ? `/storage/${props.venue.venue_image}` : null,
});

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.venue_image = file;
        form.imagePreview = URL.createObjectURL(file);
    }
};

const submit = () => form.post(route('venues.update', props.venue.id_venue), { forceFormData: true });
</script>

<template>
    <Head title="Edit Venue" />

    <AppLayout title="Edit Venue">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">Edit Venue</h1>
                    <p class="text-sm text-on-surface-variant mt-1">Update venue details</p>
                </div>
                <Link
                    :href="route('venues.index')"
                    class="bg-surface-container-low text-on-surface-variant px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-surface-container w-fit"
                >
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Back to Venues
                </Link>
            </div>

            <section class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 sm:p-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="venue_name" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Venue Name</label>
                        <input
                            id="venue_name"
                            type="text"
                            v-model="form.venue_name"
                            required
                            autofocus
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                        <InputError class="mt-2" :message="form.errors.venue_name" />
                    </div>

                    <div>
                        <label for="venue_address" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Venue Address</label>
                        <input
                            id="venue_address"
                            type="text"
                            v-model="form.venue_address"
                            required
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                        <InputError class="mt-2" :message="form.errors.venue_address" />
                    </div>

                    <div>
                        <label for="venue_max_capacity" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Maximum Capacity</label>
                        <input
                            id="venue_max_capacity"
                            type="number"
                            min="1"
                            v-model="form.venue_max_capacity"
                            required
                            class="w-full bg-surface-container-low border-outline-variant text-on-surface rounded-xl focus:ring-2 focus:ring-primary/30 focus:border-primary"
                        >
                        <InputError class="mt-2" :message="form.errors.venue_max_capacity" />
                    </div>

                    <div>
                        <label for="venue_image" class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Venue Image</label>
                        <input
                            id="venue_image"
                            type="file"
                            accept="image/*"
                            @change="handleImageChange"
                            class="w-full text-sm text-on-surface-variant
                                   file:mr-4 file:py-2 file:px-4
                                   file:rounded-xl file:border-0
                                   file:text-sm file:font-bold
                                   file:bg-primary file:text-on-primary
                                   hover:file:bg-primary-container cursor-pointer"
                        >
                        <InputError class="mt-2" :message="form.errors.venue_image" />
                    </div>

                    <div v-if="form.imagePreview">
                        <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Preview</p>
                        <img :src="form.imagePreview" alt="Venue preview" class="w-full max-w-xs aspect-video object-cover rounded-xl">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                        <Link
                            :href="route('venues.index')"
                            class="px-4 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-surface-container"
                        >Cancel</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            :class="{ 'opacity-50': form.processing }"
                            class="bg-primary text-on-primary px-5 py-2 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-primary-container transition-colors"
                        >
                            <span class="material-symbols-outlined text-base">save</span>
                            Update Venue
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </AppLayout>
</template>
