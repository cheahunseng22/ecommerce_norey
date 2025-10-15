<?php

namespace App\Http\Controllers\Api;

use App\Events\UserStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //Show all user
    public function index()
    {
        try {
            $users = User::orderByDesc('created_at')->get();

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {

            Log::error('Error fetching users:', ['error' => $e->getMessage()]);

            // Return an error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch users.'
            ], 500);
        }
    }

    //show user
    public function show(User $user)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing user:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to show user'
            ], 500);
        }
    }

    // get linked customer by matching email
    public function customer(User $user)
    {
        try {
            $customer = Customer::where('email', $user->email)->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'customer' => $customer,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user customer:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch linked customer',
            ], 500);
        }
    }


    //create user
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
               'phone' => 'required|string|digits_between:8,15',

                'password' => 'required|string|min:8',
                'type' => 'required|in:user,admin',
                'status' => 'required|in:active,inactive',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'type' => $request->type,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating user:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create user'
            ], 500);
        }
    }


    //update user
    public function update(Request $request, User $user)
    {


        try {

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'sometimes|string|max:255',
                'password' => 'sometimes|string|min:8',
                'type' => 'sometimes|string|min:255',
                'status' => 'sometimes|string|max:255',

            ]);

            // Handle validation failure
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update password if provided
            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

            // Update other fields
            $user->fill($request->only(['name', 'email']));
            $user->save(); // Ensure all changes are saved

            // Return success response
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating user:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }
    }


    //delete user
    public function destroy(User $user)
    {
        try {
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User Deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            Log::error('Error deleting user:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }
    }
}
