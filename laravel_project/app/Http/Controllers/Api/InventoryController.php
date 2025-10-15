<?php

namespace App\Http\Controllers\Api;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of all inventory items.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Inventory::with(['product', 'supplier']);

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            }

            // Filter by low stock (items that need reordering)
            if ($request->boolean('low_stock')) {
                $query->whereRaw('quantity_in_stock <= reorder_point');
            }

            // Filter by supplier
            if ($request->has('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $inventories = $query->paginate($perPage);

            // Add reorder status to each item
            // $inventories->getCollection()->transform(function ($inventory) {
            //     $inventory->needs_reorder = $inventory->needsReorder();
            //     $inventory->reorder_info = $inventory->getReorderInfo();
            //     return $inventory;
            // });

            return response()->json([
                'success' => true,
                'data' => $inventories,
                'message' => 'Inventory items retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve inventory items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'quantity_in_stock' => 'required|integer|min:0',
                'last_restock_date' => 'nullable|date',
                'reorder_point' => 'required|integer|min:0',
                'reorder_quantity' => 'required|integer|min:1'
            ]);

            // Check if inventory already exists for this product-supplier combination
         

           

            // Set last_restock_date to now if not provided and quantity > 0
            if (!isset($validated['last_restock_date']) && $validated['quantity_in_stock'] > 0) {
                $validated['last_restock_date'] = now()->format('Y-m-d');
            }

            $inventory = Inventory::create($validated);
            $inventory->load(['product', 'supplier']);

            return response()->json([
                'success' => true,
                'data' => $inventory,
                'message' => 'Inventory item created successfully'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create inventory item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified inventory item.
     */
    public function show($id): JsonResponse
    {
        try {
            $inventory = Inventory::with(['product', 'supplier'])->findOrFail($id);
            
            // Add additional information
            $inventory->needs_reorder = $inventory->needsReorder();
            $inventory->reorder_info = $inventory->getReorderInfo();

            return response()->json([
                'success' => true,
                'data' => $inventory,
                'message' => 'Inventory item retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Inventory item not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $inventory = Inventory::findOrFail($id);

            $validated = $request->validate([
                'product_id' => 'sometimes|exists:products,id',
                'supplier_id' => 'sometimes|exists:suppliers,id',
                'quantity_in_stock' => 'sometimes|integer|min:0',
                'last_restock_date' => 'nullable|date',
                'reorder_point' => 'sometimes|integer|min:0',
                'reorder_quantity' => 'sometimes|integer|min:1'
            ]);

            // Check for duplicate product-supplier combination if either is being updated
            if (isset($validated['product_id']) || isset($validated['supplier_id'])) {
                $productId = $validated['product_id'] ?? $inventory->product_id;
                $supplierId = $validated['supplier_id'] ?? $inventory->supplier_id;

                $duplicate = Inventory::where('product_id', $productId)
                    ->where('supplier_id', $supplierId)
                    ->where('id', '!=', $id)
                    ->first();

                if ($duplicate) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Inventory already exists for this product-supplier combination'
                    ], 409);
                }
            }

            $inventory->update($validated);
            $inventory->load(['product', 'supplier']);

            return response()->json([
                'success' => true,
                'data' => $inventory,
                'message' => 'Inventory item updated successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update inventory item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified inventory item.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventory item deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete inventory item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjust inventory stock (add or remove stock).
     */
    public function adjustStock(Request $request, $id): JsonResponse
    {
        try {
            $inventory = Inventory::findOrFail($id);

            $validated = $request->validate([
                'adjustment' => 'required|integer',
                'reason' => 'nullable|string|max:255'
            ]);

            $adjustment = $validated['adjustment'];
            $newQuantity = $inventory->quantity_in_stock + $adjustment;

            if ($newQuantity < 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock for this adjustment'
                ], 400);
            }

            $inventory->update([
                'quantity_in_stock' => $newQuantity,
                'last_restock_date' => $adjustment > 0 ? now()->format('Y-m-d') : $inventory->last_restock_date
            ]);

            // Log the adjustment (you might want to create a separate table for this)
            // \Log::info("Stock adjustment for inventory ID {$id}: {$adjustment} units. Reason: " . ($validated['reason'] ?? 'No reason provided'));

            $inventory->load(['product', 'supplier']);

            return response()->json([
                'success' => true,
                'data' => $inventory,
                'message' => 'Stock adjusted successfully'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust stock',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory items that need reordering.
     */
    public function lowStock(): JsonResponse
    {
        try {
            $lowStockItems = Inventory::with(['product', 'supplier'])
                ->whereRaw('quantity_in_stock <= reorder_point')
                ->orderBy('quantity_in_stock', 'asc')
                ->get();

            $lowStockItems->transform(function ($inventory) {
                $inventory->needs_reorder = true;
                $inventory->reorder_info = $inventory->getReorderInfo();
                return $inventory;
            });

            return response()->json([
                'success' => true,
                'data' => $lowStockItems,
                'count' => $lowStockItems->count(),
                'message' => 'Low stock items retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve low stock items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get inventory dashboard statistics.
     */
    public function dashboard(): JsonResponse
    {
        try {
            $totalItems = Inventory::count();
            $lowStockCount = Inventory::whereRaw('quantity_in_stock <= reorder_point')->count();
            $outOfStockCount = Inventory::where('quantity_in_stock', 0)->count();
            $totalValue = Inventory::with('product')
                ->get()
                ->sum(function ($inventory) {
                    return $inventory->quantity_in_stock * ($inventory->product->price ?? 0);
                });

            $recentRestocks = Inventory::with(['product', 'supplier'])
                ->whereNotNull('last_restock_date')
                ->orderBy('last_restock_date', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => [
                        'total_items' => $totalItems,
                        'low_stock_count' => $lowStockCount,
                        'out_of_stock_count' => $outOfStockCount,
                        'total_inventory_value' => round($totalValue, 2)
                    ],
                    'recent_restocks' => $recentRestocks
                ],
                'message' => 'Dashboard data retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get products and suppliers for dropdowns.
     */
    public function getFormData(): JsonResponse
    {
        try {
            $products = Product::select('id', 'name', 'price')->orderBy('name')->get();
            $suppliers = Supplier::select('id', 'name')->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'products' => $products,
                    'suppliers' => $suppliers
                ],
                'message' => 'Form data retrieved successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve form data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}