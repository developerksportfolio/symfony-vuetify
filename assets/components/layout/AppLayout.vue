<template>
    <v-layout>
        <AppSidebar v-model="drawer" />
        <AppToolbar @toggle-drawer="drawer = !drawer" />
        <v-main>
            <v-container fluid class="pa-6">
                <slot />
            </v-container>
        </v-main>
    </v-layout>
</template>

<script>
import AppSidebar from './AppSidebar.vue';
import AppToolbar from './AppToolbar.vue';

export default {
    name: 'AppLayout',
    components: { AppSidebar, AppToolbar },
    data() {
        return {
            drawer: true,
        };
    },
};
</script>
