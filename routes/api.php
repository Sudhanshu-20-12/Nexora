<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\AdminController;



// Public routes (bina login ke access ho sakte hai)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-vendor', [AuthController::class, 'registerVendor']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (sirf logged-in user access kare)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Sirf vendor access kar sakta hai
    Route::middleware('role:vendor')->group(function () {
        Route::get('/vendor/test', function () {
            return response()->json(['message' => 'Welcome Vendor! Yeh route sirf vendors ke liye hai.']);
        });
    });

    // Sirf admin access kar sakta hai
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/test', function () {
            return response()->json(['message' => 'Welcome Admin! Yeh route sirf admin ke liye hai.']);
        });
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // ... pehle wale routes (logout, me) ...

    // Sirf vendor access kar sake (role middleware ke andar)
    Route::middleware('role:vendor')->prefix('vendor')->group(function () {
        Route::get('/products', [VendorProductController::class, 'index']);
        Route::post('/products', [VendorProductController::class, 'store']);
        Route::get('/products/{id}', [VendorProductController::class, 'show']);
        Route::post('/products/{id}', [VendorProductController::class, 'update']); // POST use kiya (kyunki images ke saath PUT thoda tricky hota hai)
        Route::delete('/products/{id}', [VendorProductController::class, 'destroy']);
    });
});


Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // ... existing routes ...

    // Cart routes (customer ke liye, but koi bhi logged-in user use kar sakta hai)
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::put('/cart/item/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/cart/item/{itemId}', [CartController::class, 'removeItem']);
});
Route::middleware('auth:sanctum')->group(function () {
    // Customer order routes
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    // Vendor order routes
    Route::middleware('role:vendor')->prefix('vendor')->group(function () {
        Route::get('/orders', [OrderController::class, 'vendorOrders']);
        Route::put('/orders/item/{itemId}/status', [OrderController::class, 'updateItemStatus']);
    });
});
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/vendors', [AdminController::class, 'vendors']);
    Route::put('/vendors/{id}/status', [AdminController::class, 'updateVendorStatus']);
    Route::get('/products', [AdminController::class, 'products']);
    Route::put('/products/{id}/status', [AdminController::class, 'updateProductStatus']);
});