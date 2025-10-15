<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the order items.
     */
    public function index(): Response
    {
        $orderItems = OrderItem::all();
        return response(['data' => $orderItems], 200);
    }

    /**
     * Store a newly created order item in storage.
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $orderItem = OrderItem::create($request->all());
        return response(['data' => $orderItem], 201);
    }

    /**
     * Display the specified order item.
     */
    public function show(OrderItem $orderItem): Response
    {
        return response(['data' => $orderItem], 200);
    }

    /**
     * Update the specified order item in storage.
     */
    public function update(Request $request, OrderItem $orderItem): Response
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'sometimes|required|exists:orders,id',
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $orderItem->update($request->all());
        return response(['data' => $orderItem], 200);
    }

    /**
     * Remove the specified order item from storage.
     */
    public function destroy(OrderItem $orderItem): Response
    {
        $orderItem->delete();
        return response(['message' => 'Order item deleted successfully'], 204);
    }
}