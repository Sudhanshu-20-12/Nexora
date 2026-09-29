import React, { useEffect, useState } from 'react';
import Navbar from '../../components/Navbar';
import Footer from '../../components/Footer';
import api from '../../api/axios';

export default function Home() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        api.get('/products')
            .then((res) => setProducts(res.data.data))
            .catch((err) => console.error(err))
            .finally(() => setLoading(false));
    }, []);

    return (
        <div className="min-h-screen bg-gray-50 flex flex-col">
            <Navbar />

            {/* Hero Banner */}
            <div className="bg-primary-light">
                <div className="max-w-7xl mx-auto px-6 py-20 text-center">
                    <p className="text-primary font-semibold text-sm tracking-wide uppercase mb-3">
                        New Season
                    </p>
                    <h1 className="font-heading font-bold text-4xl md:text-5xl text-ink mb-6">
                        Premium picks, everyday prices
                    </h1>
                    <p className="text-gray-600 mb-8 max-w-xl mx-auto">
                        Shop from thousands of trusted sellers across every category — quality guaranteed.
                    </p>
                    <button className="bg-accent hover:bg-accent-dark text-white font-semibold px-8 py-3 transition-colors">
                        Shop Now
                    </button>
                </div>
            </div>

            {/* Products Section */}
            <div className="max-w-7xl mx-auto px-6 py-12 flex-1">
                <h2 className="font-heading font-semibold text-2xl text-ink mb-6">
                    Trending Products
                </h2>

                {loading ? (
                    <p className="text-gray-500">Loading products...</p>
                ) : products.length === 0 ? (
                    <p className="text-gray-500">No products available yet.</p>
                ) : (
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
                        {products.map((product) => (
                            <div key={product.id} className="bg-white border border-gray-200 p-4">
                                <div className="h-40 bg-gray-100 mb-4 flex items-center justify-center overflow-hidden">
                                    {product.images && product.images.length > 0 ? (
                                        <img
                                            src={`http://127.0.0.1:8000/storage/${product.images[0].image_path}`}
                                            alt={product.name}
                                            className="h-full w-full object-cover"
                                        />
                                    ) : (
                                        <span className="text-gray-400 text-sm">No Image</span>
                                    )}
                                </div>
                                <p className="text-sm text-gray-800 mb-1 truncate">{product.name}</p>
                                <p className="font-semibold text-success mb-3">
                                    ₹{product.discount_price || product.price}
                                </p>
                                <button className="w-full bg-ink hover:bg-primary text-white text-sm font-medium py-2 transition-colors">
                                    Add to Cart
                                </button>
                            </div>
                        ))}
                    </div>
                )}
            </div>

            <Footer />
        </div>
    );
}
