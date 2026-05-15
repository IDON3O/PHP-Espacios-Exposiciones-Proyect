<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors bg-primary-container text-white'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-on-primary-container hover:bg-white/5';
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
