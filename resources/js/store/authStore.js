import { create } from 'zustand';
import api from '../api/axios';

const useAuthStore = create((set) => ({
    user: JSON.parse(localStorage.getItem('nexora_user')) || null,
    token: localStorage.getItem('nexora_token') || null,
    loading: false,

    // Login karo
    login: async (email, password) => {
        const res = await api.post('/login', { email, password });
        localStorage.setItem('nexora_token', res.data.token);
        localStorage.setItem('nexora_user', JSON.stringify(res.data.user));
        set({ user: res.data.user, token: res.data.token });
        return res.data;
    },

    // Customer register karo
    register: async (name, email, password, password_confirmation) => {
        const res = await api.post('/register', { name, email, password, password_confirmation });
        localStorage.setItem('nexora_token', res.data.token);
        localStorage.setItem('nexora_user', JSON.stringify(res.data.user));
        set({ user: res.data.user, token: res.data.token });
        return res.data;
    },

    // Vendor register karo
    registerVendor: async (data) => {
        const res = await api.post('/register-vendor', data);
        localStorage.setItem('nexora_token', res.data.token);
        localStorage.setItem('nexora_user', JSON.stringify(res.data.user));
        set({ user: res.data.user, token: res.data.token });
        return res.data;
    },

    // Logout karo
    logout: async () => {
        try {
            await api.post('/logout');
        } catch (e) {}
        localStorage.removeItem('nexora_token');
        localStorage.removeItem('nexora_user');
        set({ user: null, token: null });
    },
}));

export default useAuthStore;