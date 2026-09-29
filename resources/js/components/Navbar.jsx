import React from 'react';
import { Link } from 'react-router-dom';
import useAuthStore from '../store/authStore';

export default function Navbar() {
    const { user, logout } = useAuthStore();

    return (
        <nav className="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

                {/* Logo */}
                <Link to="/" className="font-heading font-bold text-2xl text-ink tracking-tight">
                    nexora
                </Link>

                {/* Middle Links */}
                <div className="hidden md:flex gap-8 text-sm text-gray-600 font-medium">
                    <Link to="/" className="hover:text-primary transition-colors">Categories</Link>
                    <Link to="/" className="hover:text-primary transition-colors">Deals</Link>
                    <Link to="/vendor/register" className="hover:text-primary transition-colors">Sell on Nexora</Link>
                </div>

                {/* Right Side */}
                <div className="flex items-center gap-4">
                    {user && (
                        <Link to="/cart" className="text-gray-600 hover:text-primary text-sm font-medium">
                            Cart
                        </Link>
                    )}

                    {user ? (
                        <div className="flex items-center gap-3">
                            <span className="text-sm text-gray-700">
                                Hi, <span className="font-semibold">{user.name.split(' ')[0]}</span>
                            </span>
                            <button
                                onClick={logout}
                                className="text-sm bg-gray-100 hover:bg-gray-200 text-ink px-4 py-2 font-medium transition-colors"
                            >
                                Logout
                            </button>
                        </div>
                    ) : (
                        <div className="flex items-center gap-3">
                            <Link to="/login" className="text-sm font-medium text-gray-700 hover:text-primary">
                                Login
                            </Link>
                            <Link
                                to="/register"
                                className="text-sm bg-primary hover:bg-primary-dark text-white px-5 py-2 font-medium transition-colors"
                            >
                                Sign Up
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </nav>
    );
}