<template>
    <div>
        <div class="d-flex align-center mb-4">
            <h1 class="text-h5">URLs</h1>
            <v-spacer />
            <v-btn color="primary" prepend-icon="mdi-plus" @click="showCreateDialog = true">
                Create URL
            </v-btn>
        </div>

        <v-card>
            <v-card-text>
                <v-text-field
                    v-model="search"
                    prepend-inner-icon="mdi-magnify"
                    label="Search URLs..."
                    single-line
                    hide-details
                    clearable
                    class="mb-4"
                    @update:model-value="onSearch"
                />

                <v-data-table-server
                    :headers="headers"
                    :items="urls"
                    :items-length="total"
                    :loading="loading"
                    :page="page"
                    :items-per-page="limit"
                    @update:page="page = $event; loadUrls()"
                    @update:items-per-page="limit = $event; page = 1; loadUrls()"
                >
                    <template #item.short_code="{ item }">
                        <a :href="'/' + item.short_code" target="_blank" class="text-decoration-none">
                            {{ item.short_code }}
                        </a>
                    </template>

                    <template #item.original_url="{ item }">
                        <span class="text-truncate d-inline-block" style="max-width: 300px;" :title="item.original_url">
                            {{ item.original_url }}
                        </span>
                    </template>

                    <template #item.is_active="{ item }">
                        <v-chip :color="item.is_active ? 'success' : 'grey'" size="small">
                            {{ item.is_active ? 'Active' : 'Inactive' }}
                        </v-chip>
                    </template>

                    <template #item.created_at="{ item }">
                        {{ formatDate(item.created_at) }}
                    </template>

                    <template #item.actions="{ item }">
                        <v-btn icon="mdi-chart-line" size="small" variant="text" :to="{ name: 'url-detail', params: { id: item.id } }" />
                        <v-btn icon="mdi-pencil" size="small" variant="text" @click="editUrl(item)" />
                        <v-btn icon="mdi-delete" size="small" variant="text" color="error" @click="deleteUrl(item)" />
                    </template>
                </v-data-table-server>
            </v-card-text>
        </v-card>

        <UrlForm v-model="showCreateDialog" :url="editingUrl" @saved="onSaved" />
        <UrlDeleteDialog v-model="showDeleteDialog" :url="deletingUrl" @deleted="onDeleted" />
    </div>
</template>

<script>
import urlService from '../services/urlService';
import UrlForm from '../components/url/UrlForm.vue';
import UrlDeleteDialog from '../components/url/UrlDeleteDialog.vue';

export default {
    name: 'UrlsPage',
    components: { UrlForm, UrlDeleteDialog },
    data() {
        return {
            urls: [],
            total: 0,
            page: 1,
            limit: 10,
            search: '',
            loading: false,
            showCreateDialog: false,
            showDeleteDialog: false,
            editingUrl: null,
            deletingUrl: null,
            searchTimeout: null,
            headers: [
                { title: 'Short Code', key: 'short_code', sortable: false },
                { title: 'Original URL', key: 'original_url', sortable: false },
                { title: 'Title', key: 'title', sortable: false },
                { title: 'Clicks', key: 'click_count', sortable: false },
                { title: 'Status', key: 'is_active', sortable: false },
                { title: 'Created', key: 'created_at', sortable: false },
                { title: 'Actions', key: 'actions', sortable: false, align: 'end' },
            ],
        };
    },
    async created() {
        await this.loadUrls();
    },
    methods: {
        async loadUrls() {
            this.loading = true;
            try {
                const response = await urlService.list(this.page, this.limit, this.search);
                this.urls = response.data.data;
                this.total = response.data.meta.total;
            } catch {
                this.urls = [];
                this.total = 0;
            } finally {
                this.loading = false;
            }
        },
        onSearch() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.page = 1;
                this.loadUrls();
            }, 300);
        },
        editUrl(url) {
            this.editingUrl = { ...url };
            this.showCreateDialog = true;
        },
        deleteUrl(url) {
            this.deletingUrl = url;
            this.showDeleteDialog = true;
        },
        onSaved() {
            this.editingUrl = null;
            this.loadUrls();
        },
        onDeleted() {
            this.deletingUrl = null;
            this.loadUrls();
        },
        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
            });
        },
    },
    watch: {
        showCreateDialog(val) {
            if (!val) this.editingUrl = null;
        },
    },
};
</script>
