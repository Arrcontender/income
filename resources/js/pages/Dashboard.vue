<template>
    <div class="max-w-md mx-auto mt-16 p-6 bg-white rounded-lg shadow-lg border border-gray-200">
        <h1 class="text-2xl font-bold mb-6 text-center text-indigo-700">Баланс по всем биржам</h1>

        <div v-if="loading" class="text-center text-gray-500">Загрузка...</div>

        <div v-else>
            <div class="mb-4 flex justify-between items-center border-b pb-2">
                <span class="text-lg font-medium text-gray-700">Bybit</span>
                <span class="text-indigo-600 font-semibold">{{ formatCurrency(bybit) }}</span>
            </div>

            <div class="mb-4 flex justify-between items-center border-b pb-2">
                <span class="text-lg font-medium text-gray-700">TInvest</span>
                <span class="text-indigo-600 font-semibold">{{ formatCurrency(tinvest) }}</span>
            </div>

            <div class="mt-6 pt-4 border-t flex justify-between items-center text-xl font-bold text-indigo-800">
                <span>Общий баланс</span>
                <span>{{ formatCurrency(total) }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const bybit = ref(0)
const tinvest = ref(0)
const total = ref(0)
const loading = ref(true)

onMounted(async () => {
    try {
        const [bybitRes, tinvestRes, totalRes] = await Promise.all([
            axios.get('/api/bybit/get_balance'),
            axios.get('/api/t_invest/get_balance'),
            axios.get('/api/aggregation/get_total_balance'),
        ])

        bybit.value = bybitRes.data.bybit_total
        tinvest.value = tinvestRes.data.t_invest_total
        total.value = totalRes.data.total_balance
    } catch (err) {
        console.error('Ошибка получения данных:', err)
    } finally {
        loading.value = false
    }
})

function formatCurrency(value) {
    return Number(value).toLocaleString('ru-RU', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    })
}
</script>
