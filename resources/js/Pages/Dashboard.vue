<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    events: { type: Array, default: () => [] },
    venues: { type: Array, default: () => [] },
});

const formatDate = (d) => new Date(d).toLocaleDateString('en-US', {
    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit',
});

const now = new Date();

// --- Aggregates --------------------------------------------------------------
const totalEvents = computed(() => props.events.length);
const activeEvents = computed(() => props.events.filter(e => e.event_status).length);
const totalVenues = computed(() => props.venues.length);
const totalVenueCapacity = computed(() =>
    props.venues.reduce((s, v) => s + (Number(v.venue_max_capacity) || 0), 0)
);
const upcomingEvents = computed(() =>
    props.events
        .filter(e => new Date(e.event_date) >= now)
        .sort((a, b) => new Date(a.event_date) - new Date(b.event_date))
);
const upcomingCount = computed(() => upcomingEvents.value.length);
const pastCount = computed(() => totalEvents.value - upcomingCount.value);

const virtualCount = computed(() => props.events.filter(e => e.event_is_virtual).length);
const inPersonCount = computed(() => totalEvents.value - virtualCount.value);
const virtualPct = computed(() => totalEvents.value
    ? Math.round((virtualCount.value / totalEvents.value) * 100) : 0);

// --- Events per month (last 6 months) ---------------------------------------
const monthlyData = computed(() => {
    const months = [];
    for (let i = 5; i >= 0; i--) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        months.push({
            key: `${d.getFullYear()}-${d.getMonth()}`,
            label: d.toLocaleDateString('en-US', { month: 'short' }),
            count: 0,
        });
    }
    const idx = Object.fromEntries(months.map((m, i) => [m.key, i]));
    props.events.forEach(e => {
        const d = new Date(e.event_date);
        const k = `${d.getFullYear()}-${d.getMonth()}`;
        if (k in idx) months[idx[k]].count++;
    });
    return months;
});
const monthlyMax = computed(() => Math.max(1, ...monthlyData.value.map(m => m.count)));

// --- Top venues by event count ----------------------------------------------
const venueStats = computed(() => {
    const counts = {};
    props.events.forEach(e => {
        const id = e.fk_venue_event;
        if (!id) return;
        counts[id] = (counts[id] || 0) + 1;
    });
    return props.venues.map(v => ({
        ...v,
        eventCount: counts[v.id_venue] || 0,
    })).sort((a, b) => b.eventCount - a.eventCount).slice(0, 5);
});
const venueStatsMax = computed(() => Math.max(1, ...venueStats.value.map(v => v.eventCount)));

// --- Donut math: stroke-dasharray on a circle (r=42, C ≈ 263.89) ------------
const donut = computed(() => {
    const C = 2 * Math.PI * 42;
    const virtualLen = totalEvents.value ? (virtualCount.value / totalEvents.value) * C : 0;
    return {
        circumference: C,
        virtualLen,
        inPersonLen: C - virtualLen,
    };
});
</script>

<template>
    <AppLayout title="Dashboard">
        <Head title="Dashboard" />

        <div class="space-y-6">
            <!-- Greeting -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-primary font-headline">Event Dashboard</h1>
                <p class="text-sm text-on-surface-variant mt-1">Overview of your events and venues</p>
            </div>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Total events -->
                <div class="bg-gradient-to-br from-primary to-primary-container p-6 rounded-xl text-white relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="flex justify-between items-start relative">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest opacity-70">Total Events</p>
                            <h4 class="text-4xl font-extrabold mt-1 font-headline">{{ totalEvents }}</h4>
                            <p class="text-xs mt-2 opacity-80">{{ activeEvents }} active · {{ totalEvents - activeEvents }} inactive</p>
                        </div>
                        <div class="p-2 bg-white/10 rounded-xl">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                    </div>
                </div>

                <!-- Venues -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Total Venues</p>
                            <h4 class="text-4xl font-extrabold mt-1 text-primary font-headline">{{ totalVenues }}</h4>
                            <p class="text-xs mt-2 text-on-surface-variant">Total capacity: {{ totalVenueCapacity.toLocaleString() }}</p>
                        </div>
                        <div class="p-2 bg-secondary-container/30 rounded-xl">
                            <span class="material-symbols-outlined text-secondary">stadium</span>
                        </div>
                    </div>
                </div>

                <!-- Upcoming -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Upcoming Events</p>
                            <h4 class="text-4xl font-extrabold mt-1 text-primary font-headline">{{ upcomingCount }}</h4>
                            <p class="text-xs mt-2 text-on-surface-variant">{{ pastCount }} past events</p>
                        </div>
                        <div class="p-2 bg-tertiary-fixed/40 rounded-xl">
                            <span class="material-symbols-outlined text-on-tertiary-container">schedule</span>
                        </div>
                    </div>
                </div>

                <!-- Virtual ratio -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 p-6 rounded-xl">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Virtual Share</p>
                            <h4 class="text-4xl font-extrabold mt-1 text-primary font-headline">{{ virtualPct }}<span class="text-sm font-medium ml-1">%</span></h4>
                            <p class="text-xs mt-2 text-on-surface-variant">{{ virtualCount }} virtual · {{ inPersonCount }} in-person</p>
                        </div>
                        <div class="p-2 bg-primary-fixed/60 rounded-xl">
                            <span class="material-symbols-outlined text-primary">videocam</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Bar chart: events per month -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6 lg:col-span-2">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-extrabold text-primary font-headline">Events per Month</h3>
                            <p class="text-sm text-on-surface-variant">Last 6 months</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">bar_chart</span>
                    </div>
                    <div class="flex items-end justify-between gap-2 sm:gap-4 h-48">
                        <div
                            v-for="m in monthlyData"
                            :key="m.key"
                            class="flex-1 flex flex-col items-center gap-2 h-full"
                        >
                            <div class="flex-1 w-full flex flex-col justify-end">
                                <div
                                    class="w-full bg-primary rounded-xl transition-all relative group"
                                    :style="{ height: `${(m.count / monthlyMax) * 100}%`, minHeight: m.count ? '6px' : '0' }"
                                >
                                    <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs font-bold text-primary opacity-0 group-hover:opacity-100">
                                        {{ m.count }}
                                    </span>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">{{ m.label }}</span>
                            <span class="text-sm font-bold text-primary">{{ m.count }}</span>
                        </div>
                    </div>
                </div>

                <!-- Donut: virtual vs in-person -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-primary font-headline">Modality</h3>
                            <p class="text-sm text-on-surface-variant">Virtual vs in-person</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">donut_small</span>
                    </div>

                    <div v-if="totalEvents === 0" class="text-sm text-on-surface-variant py-12 text-center">
                        No events yet
                    </div>
                    <div v-else class="flex flex-col items-center">
                        <div class="relative w-40 h-40">
                            <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                                <circle cx="50" cy="50" r="42" fill="none" stroke="rgb(var(--color-surface-container))" stroke-width="14" />
                                <circle
                                    cx="50" cy="50" r="42" fill="none"
                                    stroke="rgb(var(--color-primary))"
                                    stroke-width="14"
                                    :stroke-dasharray="`${donut.virtualLen} ${donut.circumference}`"
                                    stroke-linecap="butt"
                                />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-extrabold text-primary font-headline">{{ virtualPct }}%</span>
                                <span class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Virtual</span>
                            </div>
                        </div>
                        <div class="mt-4 w-full space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2">
                                    <span class="w-3 h-3 bg-primary rounded-xl"></span>
                                    <span class="text-on-surface-variant">Virtual</span>
                                </span>
                                <span class="font-bold text-primary">{{ virtualCount }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-xl bg-surface-container"></span>
                                    <span class="text-on-surface-variant">In-person</span>
                                </span>
                                <span class="font-bold text-primary">{{ inPersonCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom row: upcoming + top venues -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
                <!-- Upcoming events list -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl overflow-hidden lg:col-span-2">
                    <div class="p-6 border-b border-outline-variant/30 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-extrabold text-primary font-headline">Upcoming Events</h3>
                            <p class="text-sm text-on-surface-variant">Next 5 scheduled</p>
                        </div>
                        <Link :href="route('events.index')" class="text-sm text-primary font-bold hover:underline flex items-center gap-1">
                            View all <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </Link>
                    </div>
                    <div v-if="upcomingEvents.length === 0" class="p-6 text-center text-on-surface-variant">
                        No upcoming events.
                    </div>
                    <ul v-else class="divide-y divide-outline-variant/30">
                        <li
                            v-for="event in upcomingEvents.slice(0, 5)"
                            :key="event.id_event"
                            class="p-4 sm:p-6 flex items-center gap-4 hover:bg-surface-container-low/50 transition-colors"
                        >
                            <div class="w-12 h-12 rounded-xl bg-primary-fixed/40 flex flex-col items-center justify-center shrink-0">
                                <span class="text-[10px] font-bold uppercase text-primary">{{ new Date(event.event_date).toLocaleDateString('en-US', { month: 'short' }) }}</span>
                                <span class="text-lg font-extrabold text-primary leading-none">{{ new Date(event.event_date).getDate() }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-on-surface truncate">{{ event.event_name }}</p>
                                <p class="text-xs text-on-surface-variant truncate">
                                    {{ formatDate(event.event_date) }}
                                    <span v-if="event.venue"> · {{ event.venue.venue_name }}</span>
                                </p>
                            </div>
                            <span
                                v-if="event.event_is_virtual"
                                class="hidden sm:inline-flex items-center gap-1 px-3 py-1 bg-primary-fixed/40 text-primary rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                            >
                                <span class="material-symbols-outlined text-[14px]">videocam</span> Virtual
                            </span>
                            <span
                                v-else
                                class="hidden sm:inline-flex items-center gap-1 px-3 py-1 bg-secondary-container text-on-secondary-container rounded-full text-[10px] font-extrabold uppercase tracking-tight"
                            >
                                <span class="material-symbols-outlined text-[14px]">place</span> In-person
                            </span>
                            <Link :href="route('events.show', event.id_event)" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container">
                                <span class="material-symbols-outlined">chevron_right</span>
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Top venues by event count -->
                <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-xl p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-primary font-headline">Top Venues</h3>
                            <p class="text-sm text-on-surface-variant">By events hosted</p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant">leaderboard</span>
                    </div>
                    <div v-if="venueStats.length === 0" class="text-sm text-on-surface-variant text-center py-8">
                        No venues yet
                    </div>
                    <ul v-else class="space-y-4">
                        <li v-for="v in venueStats" :key="v.id_venue">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-on-surface truncate">{{ v.venue_name }}</span>
                                <span class="text-xs font-bold text-primary ml-2">{{ v.eventCount }}</span>
                            </div>
                            <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                                <div
                                    class="bg-primary h-full rounded-full"
                                    :style="{ width: `${(v.eventCount / venueStatsMax) * 100}%` }"
                                ></div>
                            </div>
                            <p class="text-[10px] text-on-surface-variant uppercase tracking-wider mt-1">
                                Capacity: {{ Number(v.venue_max_capacity).toLocaleString() }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
