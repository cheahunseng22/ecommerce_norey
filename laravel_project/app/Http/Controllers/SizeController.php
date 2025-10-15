<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SizeController extends Controller
{
    /**
     * Display a listing of sizes.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Size::query();

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sizes = $query->orderBy('name', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $sizes
        ]);
    }

    /**
     * Store a newly created size.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sizes,name',
           
            'is_active' => 'boolean'
        ]);

        $size = Size::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Size created successfully',
            'data' => $size
        ], 201);
    }

    /**
     * Display the specified size.
     */
    public function show(Size $size): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $size->load('products')
        ]);
    }

    /**
     * Update the specified size.
     */
    public function update(Request $request, Size $size): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:sizes,name,' . $size->id,
            // 'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);

        $size->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Size updated successfully',
            'data' => $size
        ]);
    }

    /**
     * Remove the specified size.
     */
    public function destroy(Size $size): JsonResponse
    {
        // Check if size is being used by any products
        if ($size->isInUse()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete size. It is being used by products.'
            ], 422);
        }

        $size->delete();

        return response()->json([
            'success' => true,
            'message' => 'Size deleted successfully'
        ]);
    }

    /**
     * Get active sizes.
     */
    public function getActive(): JsonResponse
    {
        $sizes = Size::active()->orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $sizes
        ]);
    }

    /**
     * Toggle size active status.
     */
    public function toggleActive(Size $size): JsonResponse
    {
        $size->toggleActive();

        return response()->json([
            'success' => true,
            'message' => 'Size status updated successfully',
            'data' => $size
        ]);
    }

    /**
     * Activate size.
     */
    public function activate(Size $size): JsonResponse
    {
        $size->activate();

        return response()->json([
            'success' => true,
            'message' => 'Size activated successfully',
            'data' => $size
        ]);
    }

    /**
     * Deactivate size.
     */
    public function deactivate(Size $size): JsonResponse
    {
        $size->deactivate();

        return response()->json([
            'success' => true,
            'message' => 'Size deactivated successfully',
            'data' => $size
        ]);
    }

    /**
     * Get products by size.
     */
    public function getProducts(Size $size): JsonResponse
    {
        $products = $size->products()->with(['category', 'brand'])->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Bulk create sizes.
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sizes' => 'required|array|min:1',
            'sizes.*.name' => 'required|string|max:255|distinct',
            // 'sizes.*.description' => 'nullable|string',
            'sizes.*.is_active' => 'boolean'
        ]);

        $createdSizes = [];
        $errors = [];

        foreach ($validated['sizes'] as $sizeData) {
            try {
                $size = Size::create($sizeData);
                $createdSizes[] = $size;
            } catch (\Exception $e) {
                $errors[] = "Failed to create size '{$sizeData['name']}': " . $e->getMessage();
            }
        }

        $response = [
            'success' => true,
            'message' => count($createdSizes) . ' sizes created successfully',
            'data' => $createdSizes
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, 201);
    }
}
