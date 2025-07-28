<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller
{
    /**
     * Create a new driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDriver(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:drivers',
            'password' => 'required|string|min:6',
            'mobile_no' => 'required|string||unique:drivers|max:15',
            'age' => 'required|integer',
            'address' => 'required|string',
            'licence_no' => 'required|string|unique:drivers',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create driver
        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'age' => $request->age,
            'address' => $request->address,
            'licence_no' => $request->licence_no,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Driver created successfully',
            'data' =>$driver
        ], 201);
    }

    /**
     * Get all drivers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrivers()
    {
        $drivers = Driver::all();
        return response()->json([
            'status' => true,
            'message' => 'Drivers retrieved successfully',
            'data' =>$drivers
        ]);
    }

    /**
     * Get a specific driver.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDriver($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Driver retrieved successfully',
            'data' =>$driver
        ]);
    }

    /**
     * Update a driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDriver(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:drivers,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'mobile_no' => 'sometimes|required|string',
            'age' => 'sometimes|required|integer',
            'address' => 'sometimes|required|string',
            'licence_no' => 'sometimes|required|string|unique:drivers,licence_no,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update driver fields if provided
        if ($request->has('name')) $driver->name = $request->name;
        if ($request->has('email')) $driver->email = $request->email;
        if ($request->has('password')) $driver->password = Hash::make($request->password);
        if ($request->has('mobile_no')) $driver->mobile_no = $request->mobile_no;
        if ($request->has('age')) $driver->age = $request->age;
        if ($request->has('address')) $driver->address = $request->address;
        if ($request->has('licence_no')) $driver->licence_no = $request->licence_no;

        $driver->save();

        return response()->json([
            'status' => true,
            'message' => 'Driver updated successfully',
            'data' =>$driver
        ]);
    }

    /**
     * Delete a driver.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDriver($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found',
                'data' => null
            ], 404);
        }

        $driver->delete();

        return response()->json([
            'status' => true,
            'message' => 'Driver deleted successfully',
            'data' => null
        ]);
    }
}