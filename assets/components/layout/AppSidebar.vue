<template>
    <v-navigation-drawer :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)">
        <v-list-item
            prepend-icon="mdi-link-variant"
            title="Short URL"
            subtitle="URL Manager"
            class="mb-2"
        />

        <v-divider />

        <v-list density="compact" nav>
            <v-list-item
                prepend-icon="mdi-view-dashboard"
                title="Dashboard"
                :to="{ name: 'dashboard' }"
                exact
            />
            <v-list-item
                prepend-icon="mdi-link"
                title="URLs"
                :to="{ name: 'urls' }"
                exact
            />
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    name: 'AppSidebar',
    props: {
        modelValue: { type: Boolean, default: true },
    },
    emits: ['update:modelValue'],
};
</script>
