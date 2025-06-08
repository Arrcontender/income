import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import VueApexCharts from 'vue3-apexcharts'
import 'vue3-toastify/dist/index.css'


const app = createApp(App)

app.use(router)
app.use(VueApexCharts)
app.component('apexchart', VueApexCharts)

app.mount('#app')
