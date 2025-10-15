<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    // GET /api/public/address?customer_id=1
    public function index(Request $request)
    {
        $query = Address::query();

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $addresses = $query->get();

        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    // GET /api/public/address/customer/{customer_id}
    public function getByCustomerId($customer_id)
    {
        $addresses = Address::where('customer_id', $customer_id)->get();

        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    // POST /api/public/address/customer/{customer_id}
  public function storeByCustomerId(Request $request, $customer_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $address = new Address();
        $address->customer_id = $customer_id;
        $address->fill($validated);
        $address->save();

        return response()->json([
            'message' => 'Address created successfully!',
            'data' => $address,
        ]);
    }

    // PUT /api/public/address/{id}
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_detail' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully!',
            'data' => $address,
        ]);
    }

    // DELETE /api/public/address/{id}
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully!',
        ]);
    }
}
