import api from './api';

export default {
    list(page = 1, limit = 10, search = '') {
        const params = { page, limit };
        if (search) params.search = search;
        return api.get('/urls', { params });
    },

    get(id) {
        return api.get(`/urls/${id}`);
    },

    create(data) {
        return api.post('/urls', data);
    },

    update(id, data) {
        return api.put(`/urls/${id}`, data);
    },

    delete(id) {
        return api.delete(`/urls/${id}`);
    },
};
