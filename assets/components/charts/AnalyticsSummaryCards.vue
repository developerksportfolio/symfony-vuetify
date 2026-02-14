<template>
    <v-row>
        <v-col v-for="card in cards" :key="card.title" cols="12" sm="6" md="3">
            <v-card>
                <v-card-text class="d-flex align-center">
                    <v-avatar :color="card.color" size="48" class="mr-4">
                        <v-icon color="white">{{ card.icon }}</v-icon>
                    </v-avatar>
                    <div>
                        <div class="text-h5 font-weight-bold">{{ card.value }}</div>
                        <div class="text-caption text-grey">{{ card.title }}</div>
                    </div>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    name: 'AnalyticsSummaryCards',
    props: {
        stats: { type: Object, default: () => ({}) },
    },
    computed: {
        cards() {
            return [
                {
                    title: 'Total URLs',
                    value: this.stats.total_urls ?? 0,
                    icon: 'mdi-link',
                    color: 'primary',
                },
                {
                    title: 'Active URLs',
                    value: this.stats.active_urls ?? 0,
                    icon: 'mdi-check-circle',
                    color: 'success',
                },
                {
                    title: 'Total Clicks',
                    value: this.stats.total_clicks ?? 0,
                    icon: 'mdi-cursor-default-click',
                    color: 'info',
                },
                {
                    title: 'Today\'s Clicks',
                    value: this.stats.today_clicks ?? 0,
                    icon: 'mdi-calendar-today',
                    color: 'warning',
                },
            ];
        },
    },
};
</script>
