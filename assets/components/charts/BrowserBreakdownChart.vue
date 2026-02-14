<template>
    <v-card>
        <v-card-title class="text-subtitle-1">Browser Breakdown</v-card-title>
        <v-card-text>
            <Doughnut v-if="chartData" :data="chartData" :options="options" />
            <div v-else class="text-center text-grey py-8">No browser data available</div>
        </v-card-text>
    </v-card>
</template>

<script>
import { Doughnut } from 'vue-chartjs';
import {
    Chart as ChartJS,
    ArcElement,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

export default {
    name: 'BrowserBreakdownChart',
    components: { Doughnut },
    props: {
        data: { type: Array, default: () => [] },
    },
    computed: {
        chartData() {
            if (!this.data || this.data.length === 0) return null;
            const colors = ['#1976D2', '#388E3C', '#F57C00', '#D32F2F', '#7B1FA2', '#0097A7', '#5D4037', '#455A64', '#C2185B', '#00796B'];
            return {
                labels: this.data.map(d => d.browser),
                datasets: [{
                    data: this.data.map(d => parseInt(d.clicks)),
                    backgroundColor: this.data.map((_, i) => colors[i % colors.length]),
                }],
            };
        },
        options() {
            return {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                },
            };
        },
    },
};
</script>
