<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Create a new customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCustomer(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6',
            'mobile_no' => 'required|string|unique:customers|max:15',
            'age' => 'required|integer',
            'address' => 'required|string',
            'pincode' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('customers');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file to public directory
            $image->move($directory, $imageName);
            $imagePath = 'customers/' . $imageName;
        }

        // Create customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'age' => $request->age,
            'address' => $request->address,
            'pincode' => $request->pincode,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    /**
     * Get all customers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers()
    {
        $customers = Customer::all();
        return response()->json([
            'status' => true,
            'message' => 'Customers retrieved successfully',
            'data'=>$customers
        ]);
    }

    /**
     * Get a specific customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomer($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Customer retrieved successfully',
            'data' =>$customer
        ]);
    }

    /**
     * Update a customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:customers,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'mobile_no' => 'sometimes|required|string',
            'age' => 'sometimes|required|integer',
            'address' => 'sometimes|required|string',
            'pincode' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($customer->image) {
                $oldImagePath = public_path($customer->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('customers');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file to public directory
            $image->move($directory, $imageName);
            $customer->image = 'customers/' . $imageName;
        }

        // Update customer fields if provided
        if ($request->has('name')) $customer->name = $request->name;
        if ($request->has('email')) $customer->email = $request->email;
        if ($request->has('password')) $customer->password = Hash::make($request->password);
        if ($request->has('mobile_no')) $customer->mobile_no = $request->mobile_no;
        if ($request->has('age')) $customer->age = $request->age;
        if ($request->has('address')) $customer->address = $request->address;
        if ($request->has('pincode')) $customer->pincode = $request->pincode;

        $customer->save();

        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully',
            'data' =>$customer
        ]);
    }

    /**
     * Delete a customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCustomer($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'status' => true,
            'message' => 'Customer deleted successfully',
        ]);
    }

    /**
     * Register a new customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6',
            'mobile_no' => 'required|string',
            'age' => 'required|integer',
            'address' => 'required|string',
            'pincode' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('customers');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file to public directory
            $image->move($directory, $imageName);
            $imagePath = 'customers/' . $imageName;
        }

        // Create customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'age' => $request->age,
            'address' => $request->address,
            'pincode' => $request->pincode,
            'image' => $imagePath,
        ]);

        // Generate token for the customer
        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Customer registered successfully',
            'data' => [
                'user' => $customer,
                'user_type' => 'customer',
                'token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }
}