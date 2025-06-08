<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Регистрация
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Или
                    <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                        войдите, если у вас уже есть аккаунт
                    </router-link>
                </p>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="name" class="sr-only">Имя</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            autocomplete="name"
                            required
                            v-model="form.name"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Имя"
                        />
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email адрес</label>
                        <input
                            id="email-address"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            v-model="form.email"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email адрес"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">Пароль</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            v-model="form.password"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Пароль"
                        />
                    </div>
                    <div>
                        <label for="password-confirmation" class="sr-only">Подтверждение пароля</label>
                        <input
                            id="password-confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            v-model="form.password_confirmation"
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Подтверждение пароля"
                        />
                    </div>
                </div>

                <div v-if="errors.length > 0" class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Ошибки валидации
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li v-for="(error, index) in errors" :key="index">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span v-else class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        {{ loading ? 'Регистрация...' : 'Зарегистрироваться' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { toast } from 'vue3-toastify';

const router = useRouter();
const loading = ref(false);
const errors = ref([]);

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

function validateForm() {
    errors.value = [];

    if (form.password.length < 8) {
        errors.value.push('Пароль должен содержать минимум 8 символов');
    }

    if (form.password !== form.password_confirmation) {
        errors.value.push('Пароли не совпадают');
    }

    return errors.value.length === 0;
}

async function handleRegister() {
    if (!validateForm()) {
        return;
    }

    loading.value = true;

    try {
        const response = await axios.post('/api/auth/register', {
            name: form.name,
            email: form.email,
            password: form.password,
            password_confirmation: form.password_confirmation
        });

        // Сохраняем токен
        localStorage.setItem('token', response.data.token);

        toast.success('Регистрация успешна!');
        router.push('/');
    } catch (error) {
        console.error('Ошибка регистрации:', error);

        if (error.response?.status === 422) {
            // Обработка ошибок валидации
            const validationErrors = error.response.data.errors;
            errors.value = Object.values(validationErrors).flat();
        } else {
            toast.error('Произошла ошибка при регистрации. Попробуйте позже.');
        }
    } finally {
        loading.value = false;
    }
}
</script>
