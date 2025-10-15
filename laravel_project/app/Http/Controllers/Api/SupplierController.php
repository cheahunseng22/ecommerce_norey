<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:suppliers,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            // 'company'     => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json([
            'message'  => 'Supplier created successfully.',
            'supplier' => $supplier
        ], 201);
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'email'       => 'nullable|email|unique:suppliers,email,' . $supplier->id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
       
        ]);

        $supplier->update($validated);

        return response()->json([
            'message'  => 'Supplier updated successfully.',
            'supplier' => $supplier
        ]);
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully.'
        ]);
    }
 //
}
