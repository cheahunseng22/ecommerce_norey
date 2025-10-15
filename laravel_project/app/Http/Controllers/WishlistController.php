<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;


class WishlistController extends Controller
{
     public function show($customerId)
    {
        $wishlist = Wishlist::with('product')
            ->where('customer_id', $customerId)
            ->get();

        return response()->json(['data' => $wishlist], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::create($data);
        return response()->json(['data' => $wishlist], 201);
    }

    public function destroy($id)
    {
        $item = Wishlist::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Removed from wishlist'], 200);
    }
}
