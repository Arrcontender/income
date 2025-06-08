import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from '../pages/Dashboard.vue';
import Assets from '../pages/Assets.vue'
import History from '../pages/History.vue'
import Settings from '../pages/Settings.vue'


const routes = [
    {
        path: '/',
        component: Dashboard,
    },
    { path: '/assets', component: Assets },
    { path: '/history', component: History },
    { path: '/settings', component: Settings },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
