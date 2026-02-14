<template>
    <v-card>
        <v-card-title class="text-subtitle-1">Clicks Over Time</v-card-title>
        <v-card-text>
            <Line v-if="chartData" :data="chartData" :options="options" />
            <div v-else class="text-center text-grey py-8">No click data available</div>
        </v-card-text>
    </v-card>
</template>

<script>
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

export default {
    name: 'ClicksOverTimeChart',
    components: { Line },
    props: {
        data: { type: Array, default: () => [] },
    },
    computed: {
        chartData() {
            if (!this.data || this.data.length === 0) return null;
            return {
                labels: this.data.map(d => d.date),
                datasets: [{
                    label: 'Clicks',
                    data: this.data.map(d => parseInt(d.clicks)),
                    borderColor: '#1976D2',
                    backgroundColor: 'rgba(25, 118, 210, 0.1)',
                    fill: true,
                    tension: 0.3,
                }],
            };
        },
        options() {
            return {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            };
        },
    },
};
</script>
