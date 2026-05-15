<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({ title: String });

const page  = usePage();
const user  = computed(() => page.props.auth?.user);
const sidebarOpen = ref(false);

const logout = () => router.post(route('logout'));

const navItems = [
    { label: 'Dashboard',  route: 'dashboard',          pattern: 'dashboard' },
    { label: 'Espacios',   route: 'spaces.index',       pattern: 'spaces.*' },
    { label: 'Reservas',   route: 'reservations.index', pattern: 'reservations.*' },
    { label: 'Calendario', route: 'calendar.index',     pattern: 'calendar.*' },
];

const isActive = (pattern) => { try { return route().current(pattern); } catch { return false; } };
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />

        <div class="flex h-screen overflow-hidden" style="background:#fbf9f9; color:#1a1a1a;">

            <!-- Mobile overlay -->
            <div v-if="sidebarOpen" class="fixed inset-0 z-30 bg-black/40 lg:hidden" @click="sidebarOpen = false" />

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-40 w-56 flex flex-col shrink-0 transform transition-transform duration-300 lg:relative lg:translate-x-0 border-r"
                style="background:#1a1a1a; color:#fbf9f9; border-color:rgba(251,249,249,0.08);"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
                <!-- Logo -->
                <div class="px-7 py-8 border-b" style="border-color:rgba(251,249,249,0.08);">
                    <p style="font-size:0.6rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:rgba(251,249,249,0.45); margin-bottom:6px;">
                        Panel Admin
                    </p>
                    <h1 class="font-display font-bold text-lg leading-tight" style="color:#fbf9f9;">
                        Espacios<br>Exposiciones
                    </h1>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <Link
                        v-for="item in navItems"
                        :key="item.route"
                        :href="route(item.route)"
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold tracking-widest uppercase transition-all duration-300"
                        :style="isActive(item.pattern)
                            ? 'background:rgba(251,249,249,0.12); color:#fbf9f9; border-left: 2px solid #fbf9f9; padding-left: calc(0.75rem - 2px);'
                            : 'color:rgba(251,249,249,0.5); border-left: 2px solid transparent; padding-left: calc(0.75rem - 2px);'"
                    >
                        {{ item.label }}
                    </Link>
                </nav>

                <!-- User -->
                <div v-if="user" class="px-7 py-5 border-t" style="border-color:rgba(251,249,249,0.08);">
                    <p class="text-xs font-semibold truncate" style="color:#fbf9f9;">{{ user.name }}</p>
                    <p class="truncate mt-0.5" style="font-size:0.65rem; color:rgba(251,249,249,0.4);">{{ user.email }}</p>
                    <button @click="logout" class="mt-4 text-xs font-semibold tracking-widest uppercase transition-colors duration-300 hover:text-white" style="color:rgba(251,249,249,0.4); letter-spacing:0.14em;">
                        Cerrar sesión →
                    </button>
                </div>
            </aside>

            <!-- Main -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Topbar -->
                <header class="h-14 flex items-center justify-between px-6 sm:px-10 border-b shrink-0" style="background:#fbf9f9; border-color:rgba(26,26,26,0.08);">
                    <div class="flex items-center gap-4">
                        <button class="lg:hidden" @click="sidebarOpen = true" style="color:#6b6b6b;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                        </button>
                        <span v-if="title" style="font-size:0.65rem; font-weight:700; letter-spacing:0.18em; text-transform:uppercase; color:#6b6b6b;">
                            {{ title }}
                        </span>
                    </div>

                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center gap-2" style="color:#6b6b6b;">
                                <div class="w-7 h-7 flex items-center justify-center border text-xs font-bold" style="border-color:rgba(26,26,26,0.2); color:#1a1a1a; background:#dbdad9;">
                                    {{ user?.name?.[0]?.toUpperCase() }}
                                </div>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.show')">Perfil</DropdownLink>
                            <div class="border-t" style="border-color:rgba(26,26,26,0.08);"></div>
                            <form @submit.prevent="logout">
                                <DropdownLink as="button">Cerrar sesión</DropdownLink>
                            </form>
                        </template>
                    </Dropdown>
                </header>

                <!-- Canvas -->
                <main class="flex-1 overflow-y-auto px-6 sm:px-10 py-10" style="background:#fbf9f9;">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
