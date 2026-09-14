<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Order place karo (checkout)
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Database transaction — agar beech mein kuch fail ho to sab undo ho jaye
        $order = DB::transaction(function () use ($cart, $request, $user) {
            $total = $cart->items->sum(fn($item) => $item->price * $item->quantity);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'placed',
            ]);

            // Har cart item ke liye order_item banao (vendor_id ke saath)
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'vendor_id' => $item->product->vendor_id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'item_status' => 'pending',
                ]);

                // Stock kam karo
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Cart khali kar do order place hone ke baad
            $cart->items()->delete();

            return $order;
        });

        return response()->json([
            'message' => 'Order placed successfully.',
            'order' => $order->load('items'),
        ], 201);
    }

    // Customer ke saare orders
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product.images')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    // Ek order ki detail
    public function show(Request $request, $id)
    {
        $order = Order::where('user_id', $request->user()->id)
            ->with('items.product.images', 'items.vendor')
            ->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found.'], 404);
        }

        return response()->json($order);
    }

    // VENDOR: apne order items dekhe
    public function vendorOrders(Request $request)
    {
        $vendor = $request->user()->vendor;

        $items = \App\Models\OrderItem::where('vendor_id', $vendor->id)
            ->with('order', 'product')
            ->latest()
            ->get();

        return response()->json($items);
    }

    // VENDOR: order item ka status update kare (shipped/delivered)
    public function updateItemStatus(Request $request, $itemId)
    {
        $request->validate(['status' => 'required|in:pending,shipped,delivered,cancelled']);

        $vendor = $request->user()->vendor;
        $item = \App\Models\OrderItem::where('vendor_id', $vendor->id)->find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Order item not found.'], 404);
        }

        $item->update(['item_status' => $request->status]);

        return response()->json(['message' => 'Status updated.']);
    }
}