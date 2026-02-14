<template>
    <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="450">
        <v-card>
            <v-card-title class="text-h6">Delete URL</v-card-title>
            <v-card-text>
                Are you sure you want to delete this short URL?
                <strong v-if="url">{{ url.short_code }}</strong>
                <br>
                This will also delete all associated click data. This action cannot be undone.
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn variant="text" @click="$emit('update:modelValue', false)">Cancel</v-btn>
                <v-btn color="error" :loading="loading" @click="confirmDelete">Delete</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import urlService from '../../services/urlService';

export default {
    name: 'UrlDeleteDialog',
    props: {
        modelValue: { type: Boolean, default: false },
        url: { type: Object, default: null },
    },
    emits: ['update:modelValue', 'deleted'],
    inject: ['showSnackbar'],
    data() {
        return { loading: false };
    },
    methods: {
        async confirmDelete() {
            if (!this.url) return;
            this.loading = true;
            try {
                await urlService.delete(this.url.id);
                this.showSnackbar('URL deleted successfully');
                this.$emit('deleted');
                this.$emit('update:modelValue', false);
            } catch {
                this.showSnackbar('Failed to delete URL', 'error');
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
