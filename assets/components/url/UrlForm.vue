<template>
    <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="600" persistent>
        <v-card>
            <v-card-title>
                <span class="text-h6">{{ isEdit ? 'Edit URL' : 'Create Short URL' }}</span>
            </v-card-title>
            <v-card-text>
                <v-form ref="form" v-model="valid">
                    <v-text-field
                        v-model="form.original_url"
                        label="Original URL"
                        placeholder="https://example.com/very-long-url"
                        :rules="urlRules"
                        prepend-icon="mdi-link"
                        required
                        class="mb-3"
                    />
                    <v-text-field
                        v-model="form.title"
                        label="Title (optional)"
                        placeholder="My Link"
                        prepend-icon="mdi-format-title"
                        class="mb-3"
                    />
                    <v-switch
                        v-model="form.is_active"
                        label="Active"
                        color="primary"
                    />
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn variant="text" @click="close">Cancel</v-btn>
                <v-btn color="primary" :disabled="!valid" :loading="loading" @click="submit">
                    {{ isEdit ? 'Update' : 'Create' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import urlService from '../../services/urlService';

export default {
    name: 'UrlForm',
    props: {
        modelValue: { type: Boolean, default: false },
        url: { type: Object, default: null },
    },
    emits: ['update:modelValue', 'saved'],
    inject: ['showSnackbar'],
    data() {
        return {
            valid: false,
            loading: false,
            form: {
                original_url: '',
                title: '',
                is_active: true,
            },
            urlRules: [
                v => !!v || 'URL is required',
                v => /^https?:\/\/.+/.test(v) || 'Must be a valid URL starting with http:// or https://',
            ],
        };
    },
    computed: {
        isEdit() {
            return !!this.url;
        },
    },
    watch: {
        modelValue(val) {
            if (val && this.url) {
                this.form.original_url = this.url.original_url;
                this.form.title = this.url.title || '';
                this.form.is_active = this.url.is_active;
            } else if (val) {
                this.resetForm();
            }
        },
    },
    methods: {
        resetForm() {
            this.form = { original_url: '', title: '', is_active: true };
        },
        close() {
            this.$emit('update:modelValue', false);
            this.resetForm();
        },
        async submit() {
            this.loading = true;
            try {
                if (this.isEdit) {
                    await urlService.update(this.url.id, this.form);
                    this.showSnackbar('URL updated successfully');
                } else {
                    await urlService.create(this.form);
                    this.showSnackbar('URL created successfully');
                }
                this.$emit('saved');
                this.close();
            } catch (error) {
                const message = error.response?.data?.errors
                    ? Object.values(error.response.data.errors).join(', ')
                    : 'An error occurred';
                this.showSnackbar(message, 'error');
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>
