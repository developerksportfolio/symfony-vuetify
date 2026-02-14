<template>
    <v-app>
        <AppLayout>
            <router-view />
        </AppLayout>
        <v-snackbar
            v-model="snackbar.show"
            :color="snackbar.color"
            :timeout="3000"
            location="bottom right"
        >
            {{ snackbar.text }}
            <template #actions>
                <v-btn variant="text" @click="snackbar.show = false">Close</v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
import AppLayout from './components/layout/AppLayout.vue';

export default {
    name: 'App',
    components: { AppLayout },
    data() {
        return {
            snackbar: {
                show: false,
                text: '',
                color: 'success',
            },
        };
    },
    provide() {
        return {
            showSnackbar: this.showSnackbar,
        };
    },
    methods: {
        showSnackbar(text, color = 'success') {
            this.snackbar.text = text;
            this.snackbar.color = color;
            this.snackbar.show = true;
        },
    },
};
</script>
