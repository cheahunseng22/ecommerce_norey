<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $customers = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'from' => $customers->firstItem(),
            'to' => $customers->lastItem(),
            'total' => $customers->total(),
            'per_page' => $customers->perPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:customers,email',
            'phone'    => 'required|digits_between:8,15|unique:customers,phone',
            'password' => 'required|string|min:8',
            'address'  => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'country'  => 'required|string|max:255',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->except('avatar');

        // ✅ Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        // ✅ Create Customer
        $customer = Customer::create($data);

        // ✅ Create linked Address (uses relation)
        $customer->addresses()->create([
            'name'           => $customer->name,
            'phone'          => $customer->phone,
            'address_detail' => $request->input('address'),
            'city'           => $request->input('city'),
            'country'        => $request->input('country'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer and address created successfully!',
            'data' => $customer->load('addresses'),
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'data' => $customer->load('addresses'),
        ]);
    }

    public function user(Customer $customer)
    {
        $user = User::where('email', $customer->email)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'customer' => $customer,
                'user' => $user,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Delete related addresses
        $customer->addresses()->delete();
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer and related addresses deleted successfully.',
        ]);
    }
}
