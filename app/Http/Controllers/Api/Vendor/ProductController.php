<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // 1. VENDOR KE APNE SAARE PRODUCTS LIST KARO
    public function index(Request $request)
    {
        $vendor = $request->user()->vendor; // logged-in vendor ka vendor record

        $products = Product::where('vendor_id', $vendor->id)
            ->with('images', 'category') // related data bhi saath le aao
            ->latest()
            ->get();

        return response()->json($products);
    }

    // 2. NAYA PRODUCT ADD KARO
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048', // har image max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vendor = $request->user()->vendor;

        // Product create karo
        $product = Product::create([
            'vendor_id' => $vendor->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(6),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'status' => 'pending_approval', // admin approve karega
        ]);

        // Images upload karo
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public'); // storage/app/public/products mein save hoga

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $index === 0, // pehli image ko "primary" bana do
            ]);
        }

        return response()->json([
            'message' => 'Product added successfully. Waiting for admin approval.',
            'product' => $product->load('images'),
        ], 201);
    }

    // 3. EK PRODUCT KI DETAIL DEKHO (edit form khol ne se pehle)
    public function show(Request $request, $id)
    {
        $vendor = $request->user()->vendor;

        $product = Product::where('vendor_id', $vendor->id)
            ->with('images', 'variants')
            ->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found or not yours.'], 404);
        }

        return response()->json($product);
    }

    // 4. PRODUCT UPDATE/EDIT KARO
    public function update(Request $request, $id)
    {
        $vendor = $request->user()->vendor;

        $product = Product::where('vendor_id', $vendor->id)->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found or not yours.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->only([
            'name', 'category_id', 'description', 'price', 'discount_price', 'stock_quantity'
        ]));

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product,
        ]);
    }

    // 5. PRODUCT DELETE KARO
    public function destroy(Request $request, $id)
    {
        $vendor = $request->user()->vendor;

        $product = Product::where('vendor_id', $vendor->id)->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found or not yours.'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}