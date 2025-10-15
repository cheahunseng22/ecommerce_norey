<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['customer', 'product']);

        // Filter by product
        if ($request->has('product_id')) {
            $query->byProduct($request->product_id);
        }

        // Filter by customer
        if ($request->has('customer_id')) {
            $query->byCustomer($request->customer_id);
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->byRating($request->rating);
        }

        // Filter by approval status
        if ($request->has('is_approved')) {
            if ($request->boolean('is_approved')) {
                $query->approved();
            } else {
                $query->pending();
            }
        }

        // Filter by recent reviews
        if ($request->has('recent_days')) {
            $query->recent($request->recent_days);
        }

        $reviews = $query->ordered()->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        // Check if customer has already reviewed this product
        $existingReview = Review::where('customer_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product'
            ], 422);
        }

        $review = Review::create([
            'customer_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'review_date' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review->load(['customer', 'product'])
        ], 201);
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $review->load(['customer', 'product'])
        ]);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        // Only the customer who wrote the review can update it
        if ($review->customer_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own reviews'
            ], 403);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|max:1000'
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => $review->load(['customer', 'product'])
        ]);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review): JsonResponse
    {
        // Only the customer who wrote the review can delete it
        if ($review->customer_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own reviews'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ]);
    }

    /**
     * Approve a review (admin only).
     */
    public function approve(Review $review): JsonResponse
    {
        $review->approve();

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully'
        ]);
    }

    /**
     * Disapprove a review (admin only).
     */
    public function disapprove(Review $review): JsonResponse
    {
        $review->disapprove();

        return response()->json([
            'success' => true,
            'message' => 'Review disapproved successfully'
        ]);
    }

    /**
     * Toggle review approval status (admin only).
     */
    public function toggleApproval(Review $review): JsonResponse
    {
        $review->toggleApproval();

        $status = $review->is_approved ? 'approved' : 'disapproved';

        return response()->json([
            'success' => true,
            'message' => "Review {$status} successfully"
        ]);
    }

    /**
     * Get reviews by product.
     */
    public function getByProduct(Product $product): JsonResponse
    {
        $reviews = $product->approvedReviews()
            ->with(['customer'])
            ->ordered()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'product' => $product,
                'reviews' => $reviews,
                'average_rating' => $product->average_rating,
                'reviews_count' => $product->reviews_count
            ]
        ]);
    }

    /**
     * Get reviews by customer.
     */
    public function getByCustomer(Customer $customer): JsonResponse
    {
        $reviews = $customer->reviews()
            ->with(['product'])
            ->ordered()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Get pending reviews for approval.
     */
    public function getPendingReviews(): JsonResponse
    {
        $reviews = Review::pending()
            ->with(['customer', 'product'])
            ->ordered()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Get review statistics.
     */
    public function getStatistics(): JsonResponse
    {
        $stats = [
            'total_reviews' => Review::count(),
            'approved_reviews' => Review::approved()->count(),
            'pending_reviews' => Review::pending()->count(),
            'average_rating' => Review::approved()->avg('rating') ?? 0,
            'rating_distribution' => [
                '5_stars' => Review::approved()->byRating(5)->count(),
                '4_stars' => Review::approved()->byRating(4)->count(),
                '3_stars' => Review::approved()->byRating(3)->count(),
                '2_stars' => Review::approved()->byRating(2)->count(),
                '1_star' => Review::approved()->byRating(1)->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Search reviews by comment content.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        $searchQuery = $validated['query'];

        $reviews = Review::where('comment', 'like', '%' . $searchQuery . '%')
            ->with(['customer', 'product'])
            ->ordered()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }
}
