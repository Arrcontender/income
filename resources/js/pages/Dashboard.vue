<template>
    <div class="max-w-xl mx-auto mt-20 space-y-8 px-6">
        <h1 class="text-3xl font-bold text-center text-indigo-800">
            Баланс по всем биржам
        </h1>

        <!-- Кнопка обновления -->
        <div class="text-center">
            <button
                @click="fetchBalances"
                :disabled="loading"
                :class="[
                    'px-5 py-2 rounded-md text-white font-medium transition duration-300 ease-in-out bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50',
                    loading ? 'cursor-not-allowed' : 'cursor-pointer hover:scale-105 hover:shadow-lg'
                ]"
            >
                🔄 Обновить данные
            </button>
        </div>

        <Transition name="fade" mode="out-in">
            <div v-if="loading" key="loading" class="space-y-6">
                <!-- Skeleton для заголовка -->
                <div class="h-8 bg-gray-300 rounded w-3/5 mx-auto animate-pulse"></div>

                <!-- Skeleton для карточек -->
                <div class="flex justify-between bg-gray-200 rounded-lg p-5 animate-pulse">
                    <div class="flex space-x-3 items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        <div class="h-6 bg-gray-300 rounded w-24"></div>
                    </div>
                    <div class="h-6 bg-gray-300 rounded w-20"></div>
                </div>

                <div class="flex justify-between bg-gray-200 rounded-lg p-5 animate-pulse">
                    <div class="flex space-x-3 items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                        <div class="h-6 bg-gray-300 rounded w-24"></div>
                    </div>
                    <div class="h-6 bg-gray-300 rounded w-20"></div>
                </div>

                <!-- Skeleton для итогового баланса -->
                <div class="h-12 bg-gray-300 rounded w-full animate-pulse"></div>

                <!-- Skeleton для даты обновления -->
                <div class="h-4 bg-gray-300 rounded w-1/3 mx-auto animate-pulse"></div>
            </div>

            <div v-else key="content" class="space-y-6">
                <!-- Основной контент -->
                <h2 class="text-xl font-semibold text-center text-indigo-700">
                    Итоговый баланс: {{ formatCurrency(total) }}
                </h2>

                <div
                    v-for="(item, idx) in [{ name: 'Bybit', val: bybit }, { name: 'TInvest', val: tinvest }]"
                    :key="idx"
                    class="flex justify-between bg-gray-100 rounded-lg p-4"
                >
                    <span>{{ item.name }}</span>
                    <span>{{ formatCurrency(item.val) }}</span>
                </div>

                <p class="text-center text-sm text-gray-500">Обновлено: {{ updatedAt }}</p>

                <!-- График -->
                <div class="mt-10 flex justify-center">
                    <apexchart
                        width="100%"
                        type="pie"
                        :options="chartOptions"
                        :series="[bybit, tinvest]"
                    ></apexchart>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'
import { toast } from 'vue3-toastify'

const bybit = ref(0)
const tinvest = ref(0)
const total = ref(0)
const loading = ref(true)
const updatedAt = ref('—')

const chartOptions = ref({
    labels: ['Bybit', 'TInvest'],
    chart: {
        type: 'pie',
        animations: {
            enabled: true,
            easing: 'easeout',
            speed: 800,
            animateGradually: {
                enabled: true,
                delay: 150,
            },
            dynamicAnimation: {
                enabled: true,
                speed: 350,
            },
        },
        dropShadow: {
            enabled: true,
            top: 2,
            left: 0,
            blur: 4,
            opacity: 0.1,
        },
    },
    legend: {
        position: 'bottom',
        fontSize: '14px',
        labels: {
            colors: '#4B5563', // мягкий серый (Tailwind text-gray-600)
        },
        markers: {
            width: 14,
            height: 14,
            radius: 7,
        },
        itemMargin: {
            horizontal: 10,
            vertical: 5,
        },
    },
    colors: ['#818cf8', '#34d399'], // более мягкий индиго и зелёный (светлее)
    tooltip: {
        fillSeriesColor: false,
    },
    plotOptions: {
        pie: {
            dataLabels: {
                offset: -20 // подтягивает метки внутрь сектора
            }
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val.toFixed(1) + '%';
        },
        style: {
            fontSize: '16px',
            fontWeight: '700',
            colors: ['#ffffff'],
            textShadow: '0 0 5px rgba(0,0,0,0.8)',
        },
        background: {
            enabled: true,
            foreColor: '#111827',
            borderRadius: 6,
            padding: 6,
            opacity: 0.7,
            borderWidth: 0,
        },
        dropShadow: {
            enabled: true,
            top: 1,
            left: 1,
            blur: 2,
            opacity: 0.5,
        },
    },

});

async function fetchBalances() {
    loading.value = true
    try {
        const [bybitRes, tinvestRes, totalRes] = await Promise.all([
            axios.get('/api/bybit/get_balance'),
            axios.get('/api/t_invest/get_balance'),
            axios.get('/api/aggregation/get_total_balance'),
        ])

        bybit.value = bybitRes.data.bybit_total
        tinvest.value = tinvestRes.data.t_invest_total
        total.value = totalRes.data.total_balance
        updatedAt.value = new Date().toLocaleString('ru-RU')
    } catch (err) {
        console.error('Ошибка получения данных:', err)
        let message = 'Ошибка получения данных';
        if (typeof err === 'string') message = err;
        else if (err && typeof err === 'object') {
            if (err.message) message = err.message;
            else if (err.response && err.response.data && err.response.data.message) message = err.response.data.message;
            else message = JSON.stringify(err, Object.getOwnPropertyNames(err));
        }
        toast.error(typeof message === 'string' ? message : JSON.stringify(message));
    } finally {
        loading.value = false
    }
}

onMounted(fetchBalances)

function formatCurrency(value) {
    return Number(value).toLocaleString('ru-RU', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
