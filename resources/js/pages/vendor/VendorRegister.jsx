import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import useAuthStore from '../../store/authStore';

export default function VendorRegister() {
    const navigate = useNavigate();
    const registerVendor = useAuthStore((state) => state.registerVendor);

    const [formData, setFormData] = useState({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        store_name: '',
        phone: '',
        address: '',
    });
    const [errors, setErrors] = useState({});
    const [loading, setLoading] = useState(false);

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setErrors({});
        setLoading(true);

        try {
            await registerVendor(formData);
            navigate('/'); // baad mein isko '/vendor/dashboard' bhejenge
        } catch (err) {
            setErrors(err.response?.data?.errors || { general: ['Registration failed.'] });
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 px-6 py-12">
            <div className="w-full max-w-lg">

                <Link to="/" className="block text-center font-heading font-bold text-3xl text-ink mb-2">
                    nexora
                </Link>
                <p className="text-center text-accent font-medium text-sm mb-8">for business</p>

                <div className="bg-white border border-gray-200 p-8">
                    <h2 className="font-heading font-semibold text-xl text-ink mb-1">
                        Start selling on Nexora
                    </h2>
                    <p className="text-gray-500 text-sm mb-6">
                        Set up your store in minutes and reach thousands of customers
                    </p>

                    <form onSubmit={handleSubmit} className="space-y-4">

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Your Name
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value={formData.name}
                                    onChange={handleChange}
                                    className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                    required
                                />
                                {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name[0]}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Phone
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    value={formData.phone}
                                    onChange={handleChange}
                                    className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                    required
                                />
                                {errors.phone && <p className="text-red-500 text-xs mt-1">{errors.phone[0]}</p>}
                            </div>
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value={formData.email}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                required
                            />
                            {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email[0]}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Store Name
                            </label>
                            <input
                                type="text"
                                name="store_name"
                                value={formData.store_name}
                                onChange={handleChange}
                                placeholder="e.g. Amit Electronics"
                                className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                required
                            />
                            {errors.store_name && <p className="text-red-500 text-xs mt-1">{errors.store_name[0]}</p>}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                Store Address
                            </label>
                            <textarea
                                name="address"
                                value={formData.address}
                                onChange={handleChange}
                                rows="2"
                                className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                required
                            />
                            {errors.address && <p className="text-red-500 text-xs mt-1">{errors.address[0]}</p>}
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    value={formData.password}
                                    onChange={handleChange}
                                    className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                    required
                                />
                                {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password[0]}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1.5">
                                    Confirm Password
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    value={formData.password_confirmation}
                                    onChange={handleChange}
                                    className="w-full border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                                    required
                                />
                            </div>
                        </div>

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full bg-accent hover:bg-accent-dark text-white font-semibold py-3 text-sm transition-colors disabled:opacity-50"
                        >
                            {loading ? 'Creating store...' : 'Create Seller Account'}
                        </button>
                    </form>

                    <p className="text-center text-sm text-gray-500 mt-6">
                        Already selling on Nexora?{' '}
                        <Link to="/login" className="text-primary font-medium">
                            Login
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}