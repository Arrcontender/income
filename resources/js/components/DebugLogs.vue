<template>
    <div v-if="showLogs" class="fixed bottom-0 right-0 w-1/2 h-1/2 bg-black bg-opacity-90 text-white p-4 overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Console Logs</h3>
            <div class="space-x-2">
                <button @click="clearConsole" class="px-2 py-1 bg-red-600 rounded hover:bg-red-700">Clear</button>
                <button @click="showLogs = false" class="px-2 py-1 bg-gray-600 rounded hover:bg-gray-700">Close</button>
            </div>
        </div>
        <div class="space-y-2">
            <div v-for="(log, index) in consoleLogs" :key="index" 
                 :class="['p-2 rounded', log.type === 'error' ? 'bg-red-900' : 'bg-gray-800']">
                <div class="text-xs text-gray-400">{{ log.timestamp }}</div>
                <div class="font-mono">{{ log.message }}</div>
                <pre v-if="log.data" class="mt-1 text-xs overflow-x-auto">{{ JSON.stringify(log.data, null, 2) }}</pre>
            </div>
        </div>
    </div>
    <button v-else @click="showLogs = true" 
            class="fixed bottom-4 right-4 px-4 py-2 bg-gray-800 text-white rounded-full shadow-lg hover:bg-gray-700">
        Show Logs
    </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const showLogs = ref(false);
const consoleLogs = ref([]);
let originalConsole = {
    log: console.log,
    error: console.error,
    warn: console.warn,
    info: console.info
};

function interceptConsole() {
    console.log = (...args) => {
        originalConsole.log(...args);
        addLog('log', ...args);
    };
    console.error = (...args) => {
        originalConsole.error(...args);
        addLog('error', ...args);
    };
    console.warn = (...args) => {
        originalConsole.warn(...args);
        addLog('warn', ...args);
    };
    console.info = (...args) => {
        originalConsole.info(...args);
        addLog('info', ...args);
    };
}

function restoreConsole() {
    console.log = originalConsole.log;
    console.error = originalConsole.error;
    console.warn = originalConsole.warn;
    console.info = originalConsole.info;
}

function addLog(type, ...args) {
    const message = args.map(arg => 
        typeof arg === 'object' ? JSON.stringify(arg) : String(arg)
    ).join(' ');
    
    consoleLogs.value.push({
        timestamp: new Date().toLocaleTimeString(),
        type,
        message,
        data: args.length > 1 ? args : null
    });
    
    // Ограничиваем количество логов
    if (consoleLogs.value.length > 100) {
        consoleLogs.value.shift();
    }
}

function clearConsole() {
    consoleLogs.value = [];
    window.console.clear();
}

onMounted(() => {
    interceptConsole();
});

onUnmounted(() => {
    restoreConsole();
});
</script> 