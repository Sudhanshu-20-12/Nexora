import React from 'react';
import { createRoot } from 'react-dom/client';
import '../css/app.css';

function App() {
    return (
        <div className="min-h-screen flex items-center justify-center">
            <h1 className="text-5xl font-bold text-blue-600">
                Nexora Multi Vendor E-commerce
            </h1>
        </div>
    );
}

createRoot(document.getElementById('app')).render(
    <App />
);