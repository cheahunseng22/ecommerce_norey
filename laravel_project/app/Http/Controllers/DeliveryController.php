<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Delivery::with(['order', 'shipping']);

        // Filter by state
        if ($request->has('state')) {
            $query->where('state', $request->state);
        }

        // Filter by order ID
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        // Filter by tracking number
        if ($request->has('tracking_number')) {
            $query->where('tracking_number', 'like', '%' . $request->tracking_number . '%');
        }

        $deliveries = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $deliveries
        ]);
    }

    /**
     * Store a newly created delivery.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'shipping_id' => 'required|exists:shipping,id',
            'tracking_number' => 'required|string|unique:deliveries,tracking_number',
            'state' => ['sometimes', Rule::in(['pending', 'processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned'])],
            'delivery_at' => 'nullable|date',
            'shipping_at' => 'nullable|date'
        ]);

        $delivery = Delivery::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery created successfully',
            'data' => $delivery->load(['order', 'shipping'])
        ], 201);
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $delivery->load(['order', 'shipping'])
        ]);
    }

    /**
     * Update the specified delivery.
     */
    public function update(Request $request, Delivery $delivery): JsonResponse
    {
        $validated = $request->validate([
            'shipping_id' => 'sometimes|exists:shipping,id',
            'tracking_number' => ['sometimes', 'string', Rule::unique('deliveries')->ignore($delivery->id)],
            'state' => ['sometimes', Rule::in(['pending', 'processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned'])],
            'delivery_at' => 'nullable|date',
            'shipping_at' => 'nullable|date'
        ]);

        $delivery->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Delivery updated successfully',
            'data' => $delivery->load(['order', 'shipping'])
        ]);
    }

    /**
     * Remove the specified delivery.
     */
    public function destroy(Delivery $delivery): JsonResponse
    {
        $delivery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delivery deleted successfully'
        ]);
    }

    /**
     * Update delivery state.
     */
    public function updateState(Request $request, Delivery $delivery): JsonResponse
    {
        $validated = $request->validate([
            'state' => ['required', Rule::in(['pending', 'processing', 'shipped', 'in_transit', 'out_for_delivery', 'delivered', 'failed', 'returned'])]
        ]);

        $oldState = $delivery->state;
        $newState = $validated['state'];

        // Update timestamps based on state change
        if ($newState === 'shipped' && $oldState !== 'shipped') {
            $delivery->shipping_at = now();
        }

        if ($newState === 'delivered' && $oldState !== 'delivered') {
            $delivery->delivery_at = now();
        }

        $delivery->update(['state' => $newState]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery state updated successfully',
            'data' => $delivery->load(['order', 'shipping'])
        ]);
    }

    /**
     * Mark delivery as shipped.
     */
    public function markAsShipped(Delivery $delivery): JsonResponse
    {
        $delivery->markAsShipped();

        return response()->json([
            'success' => true,
            'message' => 'Delivery marked as shipped successfully',
            'data' => $delivery->load(['order', 'shipping'])
        ]);
    }

    /**
     * Mark delivery as delivered.
     */
    public function markAsDelivered(Delivery $delivery): JsonResponse
    {
        $delivery->markAsDelivered();

        return response()->json([
            'success' => true,
            'message' => 'Delivery marked as delivered successfully',
            'data' => $delivery->load(['order', 'shipping'])
        ]);
    }

    /**
     * Get delivery by tracking number.
     */
    public function getByTrackingNumber(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string'
        ]);

        $delivery = Delivery::where('tracking_number', $validated['tracking_number'])
            ->with(['order', 'shipping'])
            ->first();

        if (!$delivery) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $delivery
        ]);
    }

    /**
     * Get deliveries by order.
     */
    public function getByOrder(Order $order): JsonResponse
    {
        $deliveries = $order->deliveries()->with(['shipping'])->get();

        return response()->json([
            'success' => true,
            'data' => $deliveries
        ]);
    }
}
