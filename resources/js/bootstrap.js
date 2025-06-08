import axios from 'axios';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

// Настройка Axios для отправки AJAX-запросов
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Добавляем токен к каждому запросу
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Обработка ответов
axios.interceptors.response.use(
    response => response,
    error => {
        // Обработка ошибок аутентификации
        if (error.response?.status === 401) {
            if (!error.config.url.includes('/api/auth/login')) {
                localStorage.removeItem('token');
                toast.error('Сессия истекла. Пожалуйста, войдите снова.');
                if (window.location.pathname !== '/login') {
                    window.location.href = '/login';
                }
            }
        }

        // Обработка ошибок валидации
        if (error.response?.status === 422) {
            const errors = error.response.data.errors;
            if (errors) {
                const firstError = Object.values(errors)[0];
                let msg = '';
                if (Array.isArray(firstError) && firstError.length > 0) {
                    msg = typeof firstError[0] === 'string' ? firstError[0] : JSON.stringify(firstError[0]);
                } else if (typeof firstError === 'string') {
                    msg = firstError;
                } else if (firstError) {
                    msg = JSON.stringify(firstError);
                }
                toast.error(msg);
            }
        }

        // Обработка других ошибок
        if (error.response?.status >= 500) {
            toast.error('Произошла ошибка сервера. Пожалуйста, попробуйте позже.');
        }

        return Promise.reject(error);
    }
);
