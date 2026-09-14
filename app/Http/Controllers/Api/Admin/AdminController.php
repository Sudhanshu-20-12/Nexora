<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Saare vendors dekho (pending waale bhi)
    public function vendors()
    {
        return response()->json(Vendor::with('user')->latest()->get());
    }

    // Vendor approve/reject karo
    public function updateVendorStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected,suspended']);

        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found.'], 404);
        }

        $vendor->update(['status' => $request->status]);

        return response()->json(['message' => 'Vendor status updated.']);
    }

    // Saare products dekho (pending approval waale bhi)
    public function products()
    {
        return response()->json(Product::with('vendor', 'category')->latest()->get());
    }

    // Product approve/reject karo
    public function updateProductStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:active,inactive,pending_approval']);

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->update(['status' => $request->status]);

        return response()->json(['message' => 'Product status updated.']);
    }

    // Dashboard stats (basic)
    public function dashboard()
    {
        return response()->json([
            'total_vendors' => Vendor::count(),
            'pending_vendors' => Vendor::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'pending_products' => Product::where('status', 'pending_approval')->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ]);
    }
}