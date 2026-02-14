import api from './api';

export default {
    getFullAnalytics(urlId, from, to) {
        const params = {};
        if (from) params.from = from;
        if (to) params.to = to;
        return api.get(`/urls/${urlId}/analytics`, { params });
    },

    getClicksOverTime(urlId, from, to) {
        const params = {};
        if (from) params.from = from;
        if (to) params.to = to;
        return api.get(`/urls/${urlId}/analytics/clicks`, { params });
    },

    getTopReferrers(urlId) {
        return api.get(`/urls/${urlId}/analytics/referrers`);
    },

    getBrowserBreakdown(urlId) {
        return api.get(`/urls/${urlId}/analytics/browsers`);
    },

    getDashboardStats() {
        return api.get('/analytics/dashboard');
    },
};
