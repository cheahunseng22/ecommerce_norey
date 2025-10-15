<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
// use App\Models\Inventory;
use App\Models\Category;
use App\Models\ParentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function searchProductByName(Request $request)
    {
        // Get search parameters from either POST body or GET query parameters
        $searchName = $request->input('name') ?? $request->route('name');
        
        // Validate the search name
        if (empty($searchName)) {
            return response()->json([
                'success' => false,
                'message' => 'Product name is required'
            ], 400);
        }

        // Build the query - only search by name
        $products = Product::with(['category', 'brand'])
            ->where('is_active', true)
            ->where('name', 'LIKE', '%' . $searchName . '%')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $products,
            'total_found' => $products->count(),
            'search_query' => $searchName
        ]);
    }

    public function getProductsByParentCategory(Request $request, $name, $subcategoryname = null)
    {
        // Find the parent category by name (case-insensitive search)
        $parentCategory = ParentCategory::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
        
        if (!$parentCategory) {
            return response()->json([
                'success' => false,
                'message' => 'Parent category not found'
            ], 404);
        }

        // If a subcategory name is provided, filter to that subcategory under this parent
        if (!empty($subcategoryname)) {
            $subcategory = Category::where('parent_id', $parentCategory->id)
                ->whereRaw('LOWER(name) = ?', [strtolower($subcategoryname)])
                ->first();

            if (!$subcategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subcategory not found under the specified parent category'
                ], 404);
            }

            $categoryIds = collect([$subcategory->id]);
        } else {
            // Get all categories that belong to this parent category
            $categoryIds = Category::where('parent_id', $parentCategory->id)->pluck('id');
        }
        
        // If no categories found under this parent category
        if ($categoryIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => [],
                'parent_category' => $parentCategory->name,
                'subcategory' => $subcategoryname,
                'total_products' => 0,
                'message' => !empty($subcategoryname)
                    ? 'No products found for the specified subcategory'
                    : 'No categories found under this parent category'
            ]);
        }
        
        // Get products from all categories under this parent category
        $products = Product::with(['category', 'brand'])
            ->whereIn('category_id', $categoryIds)
            ->where('is_active', true) // Only get active products
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'parent_category' => $parentCategory->name,
            'subcategory' => $subcategoryname,
            'total_products' => $products->count(),
            'categories_count' => $categoryIds->count()
        ]);
    }


    public function getProductsByCategory($categoryId)
    {
        $products = Product::with(['category', 'brand'])
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'total_products' => $products->count()
        ]);
    }

    public function getParentCategoriesWithStats()
    {
        $parentCategories = ParentCategories::with(['categories' => function($query) {
            $query->withCount(['products' => function($query) {
                $query->where('is_active', true);
            }]);
        }])->get();

        $result = $parentCategories->map(function($parentCategory) {
            $totalProducts = $parentCategory->categories->sum('products_count');
            
            return [
                'id' => $parentCategory->id,
                'name' => $parentCategory->name,
                'categories_count' => $parentCategory->categories->count(),
                'total_products' => $totalProducts,
                'categories' => $parentCategory->categories->map(function($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'products_count' => $category->products_count
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    public function index()
    {
        $products = Product::with(['category', 'brand'])->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'rating' => 'nullable|numeric',
            'size' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            // 'inventory_id' => 'required|exists:inventories,id',
            // 'subcategory_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|string',
            'images' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand'])->find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'rating' => 'nullable|numeric',
            'size' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            // 'inventory_id' => 'sometimes|required|exists:inventories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = url('storage/' . $imagePath);
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ((array) $request->file('images') as $imageFile) {
                if (is_array($imageFile)) {
                    foreach ($imageFile as $file) {
                        $path = $file->store('products', 'public');
                        $images[] = url('storage/' . $path);
                    }
                } else {
                    $path = $imageFile->store('products', 'public');
                    $images[] = url('storage/' . $path);
                }
            }
            $validated['images'] = $images;
        } elseif ($request->has('images') && is_array($request->input('images'))) {
            $validated['images'] = $request->input('images');
        }

        $product->update($validated);

        return response()->json(['success' => true, 'data' => $product]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted']);
    }
}
