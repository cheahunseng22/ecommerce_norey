<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PromotionController extends Controller
{
    /**
     * Display a listing of promotions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Promotion::with(['product']);

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'active':
                    $query->current();
                    break;
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'expired':
                    $query->expired();
                    break;
            }
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by product
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by discount type
        if ($request->has('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $promotions = $query->orderBy('start_date', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    /**
     * Store a newly created promotion.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_type' => ['required', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        // Check if discount value is valid for percentage type
        if ($validated['discount_type'] === 'percentage' && $validated['discount_value'] > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Percentage discount cannot exceed 100%'
            ], 422);
        }

        $promotion = Promotion::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Promotion created successfully',
            'data' => $promotion->load('product')
        ], 201);
    }

    /**
     * Display the specified promotion.
     */
    public function show(Promotion $promotion): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Update the specified promotion.
     */
    public function update(Request $request, Promotion $promotion): JsonResponse
    {
        $validated = $request->validate([
            'discount_type' => ['sometimes', Rule::in(['percentage', 'fixed'])],
            'discount_value' => 'sometimes|numeric|min:0',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'is_active' => 'sometimes|boolean'
        ]);

        // Check if discount value is valid for percentage type
        if (isset($validated['discount_type']) && $validated['discount_type'] === 'percentage' && 
            isset($validated['discount_value']) && $validated['discount_value'] > 100) {
            return response()->json([
                'success' => false,
                'message' => 'Percentage discount cannot exceed 100%'
            ], 422);
        }

        $promotion->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Promotion updated successfully',
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Remove the specified promotion.
     */
    public function destroy(Promotion $promotion): JsonResponse
    {
        $promotion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promotion deleted successfully'
        ]);
    }

    /**
     * Get current active promotions.
     */
    public function getCurrent(): JsonResponse
    {
        $promotions = Promotion::current()->with(['product'])->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    /**
     * Get upcoming promotions.
     */
    public function getUpcoming(): JsonResponse
    {
        $promotions = Promotion::upcoming()->with(['product'])->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    /**
     * Get expired promotions.
     */
    public function getExpired(): JsonResponse
    {
        $promotions = Promotion::expired()->with(['product'])->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    /**
     * Get promotions by product.
     */
    public function getByProduct(Product $product): JsonResponse
    {
        $promotions = $product->promotions()->orderBy('start_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $promotions
        ]);
    }

    /**
     * Toggle promotion active status.
     */
    public function toggleActive(Promotion $promotion): JsonResponse
    {
        $promotion->toggleActive();

        return response()->json([
            'success' => true,
            'message' => 'Promotion status updated successfully',
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Activate promotion.
     */
    public function activate(Promotion $promotion): JsonResponse
    {
        $promotion->activate();

        return response()->json([
            'success' => true,
            'message' => 'Promotion activated successfully',
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Deactivate promotion.
     */
    public function deactivate(Promotion $promotion): JsonResponse
    {
        $promotion->deactivate();

        return response()->json([
            'success' => true,
            'message' => 'Promotion deactivated successfully',
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Extend promotion end date.
     */
    public function extendEndDate(Request $request, Promotion $promotion): JsonResponse
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        $promotion->extendEndDate($validated['days']);

        return response()->json([
            'success' => true,
            'message' => 'Promotion end date extended successfully',
            'data' => $promotion->load('product')
        ]);
    }

    /**
     * Calculate discounted price for a product.
     */
    public function calculateDiscountedPrice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'original_price' => 'required|numeric|min:0'
        ]);

        $promotion = Promotion::where('product_id', $validated['product_id'])
            ->current()
            ->active()
            ->first();

        if (!$promotion) {
            return response()->json([
                'success' => true,
                'data' => [
                    'has_promotion' => false,
                    'original_price' => $validated['original_price'],
                    'discounted_price' => $validated['original_price'],
                    'discount_amount' => 0
                ]
            ]);
        }

        $discountedPrice = $promotion->getDiscountedPrice($validated['original_price']);
        $discountAmount = $promotion->getDiscountAmount($validated['original_price']);

        return response()->json([
            'success' => true,
            'data' => [
                'has_promotion' => true,
                'promotion' => $promotion,
                'original_price' => $validated['original_price'],
                'discounted_price' => $discountedPrice,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $promotion->discount_type === 'percentage' ? $promotion->discount_value : null
            ]
        ]);
    }
}
