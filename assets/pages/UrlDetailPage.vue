<template>
    <div>
        <v-btn variant="text" prepend-icon="mdi-arrow-left" :to="{ name: 'urls' }" class="mb-4">
            Back to URLs
        </v-btn>

        <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

        <template v-if="url">
            <v-card class="mb-6">
                <v-card-title class="d-flex align-center">
                    <span class="text-h6">{{ url.title || url.short_code }}</span>
                    <v-chip :color="url.is_active ? 'success' : 'grey'" size="small" class="ml-3">
                        {{ url.is_active ? 'Active' : 'Inactive' }}
                    </v-chip>
                    <v-spacer />
                    <v-btn icon="mdi-pencil" size="small" variant="text" @click="showEditDialog = true" />
                    <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="showDeleteDialog = true" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <div class="text-caption text-grey">Short URL</div>
                            <a :href="'/' + url.short_code" target="_blank" class="text-body-1">
                                {{ baseUrl }}/{{ url.short_code }}
                            </a>
                        </v-col>
                        <v-col cols="12" md="6">
                            <div class="text-caption text-grey">Original URL</div>
                            <a :href="url.original_url" target="_blank" class="text-body-1 text-truncate d-block" :title="url.original_url">
                                {{ url.original_url }}
                            </a>
                        </v-col>
                        <v-col cols="6" md="3">
                            <div class="text-caption text-grey">Total Clicks</div>
                            <div class="text-h6">{{ url.click_count }}</div>
                        </v-col>
                        <v-col cols="6" md="3">
                            <div class="text-caption text-grey">Created</div>
                            <div class="text-body-1">{{ formatDate(url.created_at) }}</div>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <div class="d-flex align-center mb-4">
                <h2 class="text-h6">Analytics</h2>
                <v-spacer />
                <v-btn-toggle v-model="dateRange" mandatory density="compact" variant="outlined" class="mr-3">
                    <v-btn value="7">7 days</v-btn>
                    <v-btn value="30">30 days</v-btn>
                    <v-btn value="90">90 days</v-btn>
                </v-btn-toggle>
            </div>

            <v-progress-linear v-if="analyticsLoading" indeterminate color="primary" class="mb-4" />

            <template v-if="analytics">
                <v-row class="mb-4">
                    <v-col cols="12">
                        <ClicksOverTimeChart :data="analytics.clicks_over_time" />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12" md="6">
                        <TopReferrersChart :data="analytics.top_referrers" />
                    </v-col>
                    <v-col cols="12" md="6">
                        <BrowserBreakdownChart :data="analytics.browsers" />
                    </v-col>
                </v-row>
            </template>
        </template>

        <UrlForm v-model="showEditDialog" :url="url" @saved="loadUrl" />
        <UrlDeleteDialog v-model="showDeleteDialog" :url="url" @deleted="$router.push({ name: 'urls' })" />
    </div>
</template>

<script>
import urlService from '../services/urlService';
import analyticsService from '../services/analyticsService';
import ClicksOverTimeChart from '../components/charts/ClicksOverTimeChart.vue';
import TopReferrersChart from '../components/charts/TopReferrersChart.vue';
import BrowserBreakdownChart from '../components/charts/BrowserBreakdownChart.vue';
import UrlForm from '../components/url/UrlForm.vue';
import UrlDeleteDialog from '../components/url/UrlDeleteDialog.vue';

export default {
    name: 'UrlDetailPage',
    components: { ClicksOverTimeChart, TopReferrersChart, BrowserBreakdownChart, UrlForm, UrlDeleteDialog },
    props: {
        id: { type: [String, Number], required: true },
    },
    data() {
        return {
            url: null,
            analytics: null,
            loading: false,
            analyticsLoading: false,
            showEditDialog: false,
            showDeleteDialog: false,
            dateRange: '30',
        };
    },
    computed: {
        baseUrl() {
            return window.location.origin;
        },
        dateFrom() {
            const d = new Date();
            d.setDate(d.getDate() - parseInt(this.dateRange));
            return d.toISOString().split('T')[0] + ' 00:00:00';
        },
        dateTo() {
            return new Date().toISOString().split('T')[0] + ' 23:59:59';
        },
    },
    async created() {
        await this.loadUrl();
        await this.loadAnalytics();
    },
    watch: {
        dateRange() {
            this.loadAnalytics();
        },
    },
    methods: {
        async loadUrl() {
            this.loading = true;
            try {
                const response = await urlService.get(this.id);
                this.url = response.data.data;
            } catch {
                this.$router.push({ name: 'urls' });
            } finally {
                this.loading = false;
            }
        },
        async loadAnalytics() {
            this.analyticsLoading = true;
            try {
                const response = await analyticsService.getFullAnalytics(this.id, this.dateFrom, this.dateTo);
                this.analytics = response.data.data;
            } catch {
                this.analytics = null;
            } finally {
                this.analyticsLoading = false;
            }
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },
    },
};
</script>
