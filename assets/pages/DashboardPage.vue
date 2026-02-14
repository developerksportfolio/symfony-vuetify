<template>
    <div>
        <h1 class="text-h5 mb-4">Dashboard</h1>

        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

        <template v-if="stats">
            <AnalyticsSummaryCards :stats="stats" class="mb-6" />

            <v-row>
                <v-col cols="12">
                    <ClicksOverTimeChart :data="stats.clicks_over_time" />
                </v-col>
            </v-row>
        </template>
    </div>
</template>

<script>
import AnalyticsSummaryCards from '../components/charts/AnalyticsSummaryCards.vue';
import ClicksOverTimeChart from '../components/charts/ClicksOverTimeChart.vue';
import analyticsService from '../services/analyticsService';

export default {
    name: 'DashboardPage',
    components: { AnalyticsSummaryCards, ClicksOverTimeChart },
    data() {
        return {
            stats: null,
            loading: false,
        };
    },
    async created() {
        await this.loadStats();
    },
    methods: {
        async loadStats() {
            this.loading = true;
            try {
                const response = await analyticsService.getDashboardStats();
                this.stats = response.data.data;
            } catch {
                this.stats = { total_urls: 0, active_urls: 0, total_clicks: 0, today_clicks: 0, clicks_over_time: [] };
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
