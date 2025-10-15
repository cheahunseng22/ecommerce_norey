<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Address;
use App\Models\Customer;
use App\Events\UserStatusUpdated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
public function getProfile(Request $request)
{
    // 1️⃣ Get authenticated user
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated. Please log in.',
        ], 401);
    }

    // 2️⃣ Try to find a related customer profile
    $customer = Customer::with('addresses')->where('email', $user->email)->first();

    // 3️⃣ If no customer found, create one automatically
    if (!$customer) {
        $customer = Customer::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => 'Phnom Penh', // ✅ Default fallback or you can pull from request
            'city' => 'Phnom Penh',    // ✅ Default fallback
            'country' => 'Cambodia',   // ✅ Default fallback
            'password' => $user->password, // hashed password reuse
        ]);
    }

    // 4️⃣ Return unified response
    return response()->json([
        'success' => true,
        'message' => 'User profile retrieved successfully.',
        'data' => [
            'user' => $user,
            'customer' => $customer,
        ],
    ], 200);
}

public function updateProfile(Request $request)
{
    try {
        // Get the authenticated user from JWT token
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email'=>'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'status' => 'sometimes|in:active,inactive',
            'is_online' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update only fields present in the request
        $user->fill($request->only(['name', 'email','phone', 'status', 'is_online']));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => $user->type,
                'phone' => $user->phone,
                'status' => $user->status,
                'is_online' => $user->is_online,
                'email_verified_at' => $user->email_verified_at ?? null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 200);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token has expired',
            'error' => 'token_expired'
        ], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token is invalid',
            'error' => 'token_invalid'
        ], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Token not provided',
            'error' => 'token_not_provided'
        ], 401);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong',
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
        ], 500);
    }
}
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
    'name'     => 'required|string|max:255',
    'email'    => 'required|string|email|max:255|unique:customers,email',
    'phone'    => 'required|digits_between:8,15|unique:customers,phone', // <-- ensures uniqueness
    'password' => 'required|string|min:8|confirmed',
    'address'  => 'required|string|max:500',
    'country'  => 'required|string|max:255',
    'city'     => 'required|string|max:255',
    
        ]);
        

    DB::beginTransaction();

    try {
        // Create User
    $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            // 'type'     => $request->type,
            // 'status'   => $request->status,
         
        ]);

        // Create Customer
      $customer = Customer::create([
               'user_id' => $user->id, 
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'address'  => $request->address,
                'country'  => $request->country,
                'city'     => $request->city,
                'status'   => $request->status ?? 'active',
            ]);
                

            // Create Address
            // Address::create([
            //     'customer_id'   => $customer->id,
            //     'name'          => $request->name,
            //     'phone'         => $request->phone,
            //     'country'       => $request->country,
            //     'city'          => $request->city,
            //     'address_detail'=> 'N/A', // optional field
            // ]);

        DB::commit();

        // Create JWT Token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message'  => 'Registration successful',
            'user'     => $user,
            'customer' => $customer,
            'token'    => $token,
            'role'     => $user->type,
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Registration failed',
            'error'   => config('app.debug') ? $e->getMessage() : 'Please try again later.'
        ], 500);
    }



        $token = JWTAuth::fromUser($user);

        $cookie = cookie(
            'refresh_token',
            $token,
            60,
            '/',
            null,
            true,
            true,
            false,
            'None'
        );
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
            'token' => $token,
            'role' => $user->type,
            'status' => $user->status,
        ], 201)->withCookie($cookie);
    }


    /**
     * Log in an existing user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt login and get access token
        if (! $accessToken = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = Auth::user();

        // Update user status if needed
        if ($user instanceof User) {
            $user->update(['is_online' => true]);
            event(new UserStatusUpdated($user));
        }

        // Create a refresh token with custom claim
        $refreshToken = JWTAuth::customClaims(['type' => 'refresh'])->fromUser($user);

        // Set refresh token in HttpOnly cookie
        $cookie = cookie(
            'refresh_token',
            $refreshToken,
            60 * 24 * 7, // 7 days in minutes
            '/',
            null,
            true, // Secure (set to false for local HTTP testing)
            true, // HttpOnly
            false,
            'None' // SameSite
        );

        return response()->json([
            'message' => 'Login successful!',
            'id' => $user->id,
            'name' => $user->name,
            'token' => $accessToken,
            'role' => $user->type, // Assuming 'type' column holds the user role
        ])->withCookie($cookie);
    }

    /**
     * Log out the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->update(['is_online' => false]); // Mark user as offline
            // Broadcast the offline status update
            event(new UserStatusUpdated($user));
        }

        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'message' => 'Logged out successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to logout!',
            ], 500);
        }
    }

    /**
     * Refresh the access token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'token' => $newToken
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }

    /**
     * Create a default seller user if one doesn't exist.
     * This function is for development/seeding purposes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSellerDefault()
    {
        $email = 'seller@example.com';
        $password = 'password'; // Use a strong password in production

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Default Seller',
                'password' => Hash::make($password),
                'type' => 'seller', // Assuming 'type' column defines the role
                'status' => '',
                'is_online' => false,
            ]
        );

        if ($user->wasRecentlyCreated) {
            return response()->json([
                'message' => 'Default seller created successfully!',
                'user' => $user,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Default seller already exists.',
                'user' => $user,
            ], 200);
        }
    }
}
