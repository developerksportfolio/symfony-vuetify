<template>
    <v-card>
        <v-card-title class="text-subtitle-1">Top Referrers</v-card-title>
        <v-card-text>
            <Bar v-if="chartData" :data="chartData" :options="options" />
            <div v-else class="text-center text-grey py-8">No referrer data available</div>
        </v-card-text>
    </v-card>
</template>

<script>
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

export default {
    name: 'TopReferrersChart',
    components: { Bar },
    props: {
        data: { type: Array, default: () => [] },
    },
    computed: {
        chartData() {
            if (!this.data || this.data.length === 0) return null;
            const colors = ['#1976D2', '#388E3C', '#F57C00', '#D32F2F', '#7B1FA2', '#0097A7', '#5D4037', '#455A64', '#C2185B', '#00796B'];
            return {
                labels: this.data.map(d => d.referrer),
                datasets: [{
                    label: 'Clicks',
                    data: this.data.map(d => parseInt(d.clicks)),
                    backgroundColor: this.data.map((_, i) => colors[i % colors.length]),
                }],
            };
        },
        options() {
            return {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: { beginAtZero: true, ticks: { precision: 0 } },
                },
            };
        },
    },
};
</script>
