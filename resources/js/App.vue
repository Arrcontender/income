<template>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r shadow-sm">
            <div class="p-6 font-bold text-indigo-700 text-2xl">
                💼 MyNetWorth
            </div>
            <nav class="mt-6 space-y-2">
                <RouterLink
                    v-for="item in navItems"
                    :key="item.name"
                    :to="item.path"
                    class="block px-6 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition rounded-r-full"
                    :class="{ 'bg-indigo-100 text-indigo-700 font-semibold': $route.path === item.path }"
                >
                    {{ item.name }}
                </RouterLink>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6">
            <router-view />
        </main>
    </div>
</template>

<script setup>
const navItems = [
    { name: 'Обзор', path: '/' },
    { name: 'Активы', path: '/assets' },
    { name: 'История', path: '/history' },
    { name: 'Настройки', path: '/settings' },
]
</script>
