import React from 'react';
import { createRoot } from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import '../css/app.css';

import Login from './pages/customer/Login';
import Register from './pages/customer/Register';
import Home from './pages/customer/Home';
import VendorRegister from './pages/vendor/VendorRegister';

function App() {
    return (
        <div className="font-sans">
            <BrowserRouter>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/register" element={<Register />} />
                    <Route path="/vendor/register" element={<VendorRegister />} />
                </Routes>
            </BrowserRouter>
        </div>
    );
}

createRoot(document.getElementById('app')).render(<App />);