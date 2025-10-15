<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        return CartItem::with(['product', 'cart'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
            
        ]);

        $cart = Cart::firstOrCreate(['customer_id' => $data['customer_id']]);
        $product = Product::findOrFail($data['product_id']);

        // Check if same product with same options exists, merge quantity
        $query = $cart->items()->where('product_id', $data['product_id']);
        if (isset($data['options'])) {
            $query->where('options', json_encode($data['options']));
        }
        $existing = $query->first();

        if ($existing) {
            $existing->quantity += $data['quantity'];
            $existing->save();
            $item = $existing;
        } else {
            $item = $cart->items()->create([
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'price' => $product->price,
                'options' => $data['options'] ?? null
            ]);
        }

        $item->load(['product', 'cart']);

        return response()->json([
            'message' => 'Cart item added successfully',
            'data' => $item
        ], 201);
    }

    public function show($id)
    {
        $item = CartItem::with(['product', 'cart'])->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = CartItem::findOrFail($id);

        $data = $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $item->update($data);
        $item->load(['product', 'cart']);

        return response()->json([
            'message' => 'Cart item updated',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Cart item removed']);
    }
}
