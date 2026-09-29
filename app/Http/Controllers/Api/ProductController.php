<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Saare active products (koi bhi dekh sakta hai, login nahi chahiye)
    public function index(Request $request)
    {
        $query = Product::where('status', 'active')
            ->with('images', 'vendor', 'category');

        // Category filter (optional)
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search (optional)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        return response()->json($products);
    }

    // Ek product ki detail (product page ke liye)
    public function show($id)
    {
        $product = Product::where('status', 'active')
            ->with('images', 'variants', 'vendor', 'category', 'reviews.user')
            ->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json($product);
    }
}