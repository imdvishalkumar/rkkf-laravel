import { defineStore } from 'pinia';
import api from '@/api/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: localStorage.getItem('token') || null,
        user: (localStorage.getItem('user') && localStorage.getItem('user') !== 'undefined') ? JSON.parse(localStorage.getItem('user')) : null,
        loading: false,
        error: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token
    },

    actions: {
        async login(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const response = await api.post('/admin/login', credentials);
                console.log('Login Response:', response.data); // Debug

                // Adjust based on your actual API response structure
                // Assuming response.data.data.token or response.data.token
                // Based on previous verification: response.data.data.token
                const data = response.data.data ? response.data.data : response.data;
                const token = data.token;
                const user = data.user;

                if (token) {
                    this.token = token;
                    this.user = user;
                    localStorage.setItem('token', token);
                    localStorage.setItem('user', JSON.stringify(user));
                    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                    return true;
                } else {
                    throw new Error("Token not received");
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
                console.error("Login Error:", error);
                return false;
            } finally {
                this.loading = false;
            }
        },

        logout() {
            this.token = null;
            this.user = null;
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            // Optional: Call logout API
            // api.post('/logout'); 
            delete api.defaults.headers.common['Authorization'];
            // Router redirect handled in component or interceptor
        }
    }
});
