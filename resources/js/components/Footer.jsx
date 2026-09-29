import React from 'react';
import { Link } from 'react-router-dom';

export default function Footer() {
    return (
        <footer className="bg-ink text-gray-300 mt-16">
            <div className="max-w-7xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-8">

                {/* Brand */}
                <div>
                    <h3 className="font-heading font-bold text-xl text-white mb-3">nexora</h3>
                    <p className="text-sm text-gray-400">
                        Your trusted marketplace for everything — from everyday essentials to premium picks.
                    </p>
                </div>

                {/* Shop Links */}
                <div>
                    <h4 className="text-white font-semibold text-sm mb-3">Shop</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link to="/" className="hover:text-white transition-colors">All Products</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Categories</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Deals</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">New Arrivals</Link></li>
                    </ul>
                </div>

                {/* Sell Links */}
                <div>
                    <h4 className="text-white font-semibold text-sm mb-3">Sell</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link to="/vendor/register" className="hover:text-white transition-colors">Become a Seller</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Seller Login</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Seller Support</Link></li>
                    </ul>
                </div>

                {/* Support Links */}
                <div>
                    <h4 className="text-white font-semibold text-sm mb-3">Support</h4>
                    <ul className="space-y-2 text-sm">
                        <li><Link to="/" className="hover:text-white transition-colors">Help Center</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Track Order</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Returns</Link></li>
                        <li><Link to="/" className="hover:text-white transition-colors">Contact Us</Link></li>
                    </ul>
                </div>
            </div>

            <div className="border-t border-gray-800">
                <div className="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-2">
                    <p>© 2026 Nexora. All rights reserved.</p>
                    <div className="flex gap-4">
                        <Link to="/" className="hover:text-white transition-colors">Privacy Policy</Link>
                        <Link to="/" className="hover:text-white transition-colors">Terms of Service</Link>
                    </div>
                </div>
            </div>
        </footer>
    );
}