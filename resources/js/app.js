import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import VueApexCharts from 'vue3-apexcharts';


const app = createApp(App);

// Подключаем плагины
app.use(router);
// app.use(toast);

if (!app._context.components.apexchart) {
    app.component('apexchart', VueApexCharts);
}

app.mount('#app');
