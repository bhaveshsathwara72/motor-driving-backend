<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Create a new vehicle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createVehicle(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'car_no' => 'required|string|unique:vehicles',
            'model_no' => 'required|string',
            'color' => 'required|string',
            'description' => 'required|string',
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
            $directory = public_path('vehicles');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file to public directory
            $image->move($directory, $imageName);
            $imagePath = 'vehicles/' . $imageName;
        }

        // Create vehicle
        $vehicle = Vehicle::create([
            'name' => $request->name,
            'car_no' => $request->car_no,
            'model_no' => $request->model_no,
            'color' => $request->color,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Vehicle created successfully',
            'data' => $vehicle
        ], 201);
    }

    /**
     * Get all vehicles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehicles()
    {
        $vehicles = Vehicle::all();
        return response()->json([
            'status' => true,
            'message' => 'Vehicles retrieved successfully',
            'data' => $vehicles
        ]);
    }

    /**
     * Get a specific vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVehicle($id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'status' => false,
                'message' => 'Vehicle not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Vehicle retrieved successfully',
            'data' =>$vehicle
        ]);
    }

    /**
     * Update a vehicle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVehicle(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'status' => false,
                'message' => 'Vehicle not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'car_no' => 'sometimes|required|string|unique:vehicles,car_no,' . $id,
            'model_no' => 'sometimes|required|string',
            'color' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
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
            if ($vehicle->image) {
                $oldImagePath = public_path($vehicle->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $directory = public_path('vehicles');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file to public directory
            $image->move($directory, $imageName);
            $vehicle->image = 'vehicles/' . $imageName;
        }

        // Update vehicle fields if provided
        if ($request->has('name')) $vehicle->name = $request->name;
        if ($request->has('car_no')) $vehicle->car_no = $request->car_no;
        if ($request->has('model_no')) $vehicle->model_no = $request->model_no;
        if ($request->has('color')) $vehicle->color = $request->color;
        if ($request->has('description')) $vehicle->description = $request->description;

        $vehicle->save();

        return response()->json([
            'status' => true,
            'message' => 'Vehicle updated successfully',
            'data' =>$vehicle
        ]);
    }

    /**
     * Delete a vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'status' => false,
                'message' => 'Vehicle not found',
                'data' => null
            ], 404);
        }

        $vehicle->delete();

        return response()->json([
            'status' => true,
            'message' => 'Vehicle deleted successfully',
            'data' => null
        ]);
    }
}