<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Unified login method for both customers and admins.
     * Auto-detects user type based on email.
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $password = $request->password;

        // Check in Customer table
        $customer = Customer::where('email', $email)->first();
        if ($customer && Hash::check($password, $customer->password)) {
            $token = $customer->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Customer logged in successfully',
                'data' => [
                    'user' => $customer,
                    'user_type' => 'customer',
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        // Check in Admin table
        $admin = Admin::where('email', $email)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            $token = $admin->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Admin logged in successfully',
                'data' => [
                    'user' => $admin,
                    'user_type' => 'admin',
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ]);
        }

        // If not found in either table
        return response()->json([
            'status' => false,
            'message' => 'Invalid login credentials',
            'errors' => [
                'email' => ['Invalid email or password']
            ]
        ], 401);
    }

    /**
     * Logout the authenticated user.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
            'data' => null
        ]);
    }
}
