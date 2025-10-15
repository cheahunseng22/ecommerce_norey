<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartController extends Controller
{
    // Show the current user's cart
public function show()
{
    try {
        $customer = JWTAuth::parseToken()->authenticate();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $cart = Cart::firstOrCreate(['customer_id' => $customer->id]);
        $cart->load(['items.product']);

        return response()->json([
            'data' => $cart,
            'total' => $cart->items->sum(fn($item) => $item->product->price * $item->quantity),
        ]);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['success' => false, 'message' => 'Token expired'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['success' => false, 'message' => 'Token invalid'], 401);
    }
}


    // Add item to cart
  public function addItem(Request $request)
{
    try {
        $customer = JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cart = Cart::firstOrCreate(['customer_id' => $customer->id]);

    $item = $cart->items()->where('product_id', $validated['product_id'])->first();

    if ($item) {
        $item->increment('quantity', $validated['quantity']);
    } else {
        $product = Product::find($validated['product_id']);
        $item = $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'price' => $product->price, // store unit price
        ]);
    }

    return response()->json(['message' => 'Item added to cart', 'item' => $item], 201);
}


    // Update item quantity
   public function updateItem(Request $request, CartItem $item)
{
    $customer = JWTAuth::parseToken()->authenticate();

    if ($item->cart->customer_id !== $customer->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $item->update(['quantity' => $validated['quantity']]);

    return response()->json(['message' => 'Item updated', 'item' => $item]);
}



    // Remove item from cart
  public function removeItem(CartItem $item)
{
    $customer = JWTAuth::parseToken()->authenticate();

    if ($item->cart->customer_id !== $customer->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $item->delete();

    return response()->json(['message' => 'Item removed']);
}

    // Clear all items from cart
   public function clear()
{
    $customer = JWTAuth::parseToken()->authenticate();

    $cart = Cart::where('customer_id', $customer->id)->first();

    if ($cart) {
        $cart->items()->delete();
    }

    return response()->json(['message' => 'Cart cleared']);
}

}
