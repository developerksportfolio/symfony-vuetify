import { createRouter, createWebHistory } from 'vue-router';
import DashboardPage from '../pages/DashboardPage.vue';
import UrlsPage from '../pages/UrlsPage.vue';
import UrlDetailPage from '../pages/UrlDetailPage.vue';

const routes = [
    {
        path: '/app/dashboard',
        name: 'dashboard',
        component: DashboardPage,
    },
    {
        path: '/app/urls',
        name: 'urls',
        component: UrlsPage,
    },
    {
        path: '/app/urls/:id',
        name: 'url-detail',
        component: UrlDetailPage,
        props: true,
    },
    {
        path: '/app',
        redirect: '/app/dashboard',
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/app/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
