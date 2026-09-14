<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Cart dekhna (agar nahi hai to bana do)
    private function getOrCreateCart($userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    // Cart dikhao
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->load('items.product.images', 'items.variant');

        $total = $cart->items->sum(fn($item) => $item->price * $item->quantity);

        return response()->json([
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    // Item add karo cart mein
    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->getOrCreateCart($request->user()->id);
        $product = Product::find($request->product_id);

        // Agar already cart mein hai to quantity badha do, warna naya add karo
        $existingItem = $cart->items()
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $product->discount_price ?? $product->price,
            ]);
        }

        return response()->json(['message' => 'Item added to cart.']);
    }

    // Quantity update karo
    public function updateItem(Request $request, $itemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = $this->getOrCreateCart($request->user()->id);
        $item = $cart->items()->find($itemId);

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        $item->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated.']);
    }

    // Item remove karo
    public function removeItem(Request $request, $itemId)
    {
        $cart = $this->getOrCreateCart($request->user()->id);
        $cart->items()->where('id', $itemId)->delete();

        return response()->json(['message' => 'Item removed from cart.']);
    }
}