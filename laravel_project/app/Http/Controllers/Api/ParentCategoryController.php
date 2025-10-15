<?php

namespace App\Http\Controllers\Api;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\ParentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ParentCategoryController extends Controller
{
    /**
     * Display a listing of the parent categories.
     */
    public function index()
    {
        $parents = ParentCategory::with('subcategories')->get();
        return response()->json(['data' => $parents], 200);
    }

    /**
     * Store a newly created parent category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $parent = ParentCategory::create($validated);
        return response()->json(['data' => $parent], 201);
    }

    /**
     * Display the specified parent category.
     */
    public function show(ParentCategory $parentCategory)
    {
        $parentCategory->load('categories');
        return response()->json(['data' => $parentCategory], 200);
    }

    /**
     * Update the specified parent category in storage.
     */
    public function update(Request $request, ParentCategory $parentCategory)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            if ($request->has('name')) {
                $parentCategory->name = $validated['name'];
            }

            $parentCategory->save();

            return response()->json([
                'success' => true,
                'message' => 'Parent category updated successfully!',
                'data' => $parentCategory,
            ]);
        } catch (Exception $e) {
            Log::error('ParentCategory Update Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update parent category!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified parent category from storage.
     */
    public function destroy(ParentCategory $parentCategory)
    {
        $parentCategory->delete();
        return response()->json(['message' => 'Parent category deleted successfully'], 200);
    }
}


