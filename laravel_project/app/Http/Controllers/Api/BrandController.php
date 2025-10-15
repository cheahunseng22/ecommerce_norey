<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Brand::all()
        ]);
    }

    public function store(Request $request)
    {
        $brand = Brand::create($request->only(['name','image']));
        return response()->json($brand, 201);
    }
public function show($name)
    {
        $brand = Brand::where('name', $name)
            ->with('products') // assuming Brand hasMany Products
            ->firstOrFail();

        return response()->json([
            'data' => $brand->products
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($request->only(['name', 'image']));
        return response()->json($brand);
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return response()->json(['message' => 'Brand deleted']);
    }
}
