<template>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar (только для авторизованных пользователей) -->
        <aside v-if="isAuthenticated" class="w-64 bg-white border-r shadow-sm">
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

            <!-- Кнопка выхода -->
            <div class="mt-auto p-6">
                <button
                    @click="handleLogout"
                    class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd" />
                    </svg>
                    Выйти
                </button>
            </div>
        </aside>

        <!-- Main content -->
        <main :class="isAuthenticated ? 'flex-1 p-6' : 'w-full'">
            <router-view />
            <DebugLogs />
        </main>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DebugLogs from './components/DebugLogs.vue';
import { toast } from 'vue3-toastify';

const router = useRouter();
const isAuthenticated = ref(false);

const navItems = [
    { name: 'Обзор', path: '/' },
    { name: 'Активы', path: '/assets' },
    { name: 'История', path: '/history' },
    { name: 'Настройки', path: '/settings' },
];

// Проверяем аутентификацию при загрузке
onMounted(() => {
    checkAuth();
});

// Проверка аутентификации
function checkAuth() {
    const token = localStorage.getItem('token');
    isAuthenticated.value = !!token;
}

// Обработка выхода
async function handleLogout() {
    try {
        // Вызываем API для выхода (если есть)
        await axios.post('/api/auth/logout');
    } catch (error) {
        console.error('Ошибка при выходе:', error);
    } finally {
        // В любом случае удаляем токен и перенаправляем на страницу входа
        localStorage.removeItem('token');
        isAuthenticated.value = false;
        // toast.success('Вы успешно вышли из системы');
        router.push('/login');
    }
}

// Слушаем изменения маршрута для обновления статуса аутентификации
router.afterEach(() => {
    checkAuth();
});
</script>
