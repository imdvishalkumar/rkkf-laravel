import api from '@/api/axios';

export const userService = {
    getUsers(params) {
        return api.get('/admin/users', { params });
    },
    createUser(data) {
        return api.post('/admin/unified-users', data);
    },
    getUser(id) {
        return api.get(`/admin/users/${id}`);
    },
    updateUser(id, data) {
        return api.put(`/admin/users/${id}`, data);
    },
    deleteUser(id) {
        return api.delete(`/admin/users/${id}`);
    },
    getBranches() {
        return api.get('/admin/branches');
    },
    getBelts() {
        return api.get('/admin/belts');
    }
};
