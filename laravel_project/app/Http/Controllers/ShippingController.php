<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    /**
     * Display a listing of shipping methods.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Shipping::query();

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filter by cost range
        if ($request->has('min_cost')) {
            $query->where('cost', '>=', $request->min_cost);
        }

        if ($request->has('max_cost')) {
            $query->where('cost', '<=', $request->max_cost);
        }

        // Filter by estimated days
        if ($request->has('max_days')) {
            $query->where('estimated_days', '<=', $request->max_days);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $shipping = $query->orderBy('cost', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }

    /**
     * Store a newly created shipping method.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:shipping,name',
            'description' => 'nullable|string',
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'required|integer|min:1|max:30',
            'is_active' => 'boolean'
        ]);

        $shipping = Shipping::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shipping method created successfully',
            'data' => $shipping
        ], 201);
    }

    /**
     * Display the specified shipping method.
     */
    public function show(Shipping $shipping): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }

    /**
     * Update the specified shipping method.
     */
    public function update(Request $request, Shipping $shipping): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:shipping,name,' . $shipping->id,
            'description' => 'nullable|string',
            'cost' => 'sometimes|numeric|min:0',
            'estimated_days' => 'sometimes|integer|min:1|max:30',
            'is_active' => 'sometimes|boolean'
        ]);

        $shipping->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shipping method updated successfully',
            'data' => $shipping
        ]);
    }

    /**
     * Remove the specified shipping method.
     */
    public function destroy(Shipping $shipping): JsonResponse
    {
        // Check if shipping method is being used by any deliveries
        if ($shipping->deliveries()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete shipping method. It is being used by deliveries.'
            ], 422);
        }

        $shipping->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shipping method deleted successfully'
        ]);
    }

    /**
     * Get active shipping methods.
     */
    public function getActive(): JsonResponse
    {
        $shipping = Shipping::active()->orderBy('cost', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }

    /**
     * Get shipping methods by cost range.
     */
    public function getByCostRange(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'min_cost' => 'nullable|numeric|min:0',
            'max_cost' => 'nullable|numeric|min:0'
        ]);

        $shipping = Shipping::byCost($validated['min_cost'] ?? null, $validated['max_cost'] ?? null)
            ->active()
            ->orderBy('cost', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }

    /**
     * Get shipping methods by estimated delivery time.
     */
    public function getByEstimatedDays(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'max_days' => 'required|integer|min:1|max:30'
        ]);

        $shipping = Shipping::byEstimatedDays($validated['max_days'])
            ->active()
            ->orderBy('estimated_days', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $shipping
        ]);
    }

    /**
     * Toggle shipping method active status.
     */
    public function toggleActive(Shipping $shipping): JsonResponse
    {
        $shipping->toggleActive();

        return response()->json([
            'success' => true,
            'message' => 'Shipping method status updated successfully',
            'data' => $shipping
        ]);
    }

    /**
     * Activate shipping method.
     */
    public function activate(Shipping $shipping): JsonResponse
    {
        $shipping->activate();

        return response()->json([
            'success' => true,
            'message' => 'Shipping method activated successfully',
            'data' => $shipping
        ]);
    }

    /**
     * Deactivate shipping method.
     */
    public function deactivate(Shipping $shipping): JsonResponse
    {
        $shipping->deactivate();

        return response()->json([
            'success' => true,
            'message' => 'Shipping method deactivated successfully',
            'data' => $shipping
        ]);
    }
}
