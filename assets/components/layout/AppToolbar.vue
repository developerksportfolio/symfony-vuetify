<template>
    <v-app-bar color="primary" density="comfortable">
        <v-app-bar-nav-icon @click="$emit('toggle-drawer')" />
        <v-toolbar-title>Short URL Manager</v-toolbar-title>
    </v-app-bar>
</template>

<script>
export default {
    name: 'AppToolbar',
    emits: ['toggle-drawer'],
};
</script>
