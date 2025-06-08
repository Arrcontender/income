import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import Assets from '../pages/Assets.vue'
import History from '../pages/History.vue'
import Settings from '../pages/Settings.vue'
import Login from '../pages/Auth/Login.vue'
import Register from '../pages/Auth/Register.vue'

const routes = [
    {
        path: '/',
        component: Dashboard,
        meta: { requiresAuth: true, guest: false }
    },
    {
        path: '/assets',
        component: Assets,
        meta: { requiresAuth: true, guest: false }
    },
    {
        path: '/history',
        component: History,
        meta: { requiresAuth: true, guest: false }
    },
    {
        path: '/settings',
        component: Settings,
        meta: { requiresAuth: true, guest: false }
    },
    {
        path: '/login',
        component: Login,
        meta: { requiresAuth: false, guest: true }
    },
    {
        path: '/register',
        component: Register,
        meta: { requiresAuth: false, guest: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    const isAuthenticated = !!token;

    if (to.meta.requiresAuth === true && !isAuthenticated) {
        next('/login');
    }
    else if (to.meta.guest === true && isAuthenticated) {
        next('/');
    }
    else {
        next();
    }
});

export default router;
