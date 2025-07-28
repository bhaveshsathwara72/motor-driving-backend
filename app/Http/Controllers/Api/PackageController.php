<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Create a new package.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPackage(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'vehicle_id' => 'required|exists:vehicles,id',
            'days' => 'required|integer|min:1',
            'km' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create package
        $package = Package::create([
            'name' => $request->name,
            'vehicle_id' => $request->vehicle_id,
            'days' => $request->days,
            'km' => $request->km,
            'price' => $request->price,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Package created successfully',
            'data' =>$package
        ], 201);
    }

    /**
     * Get all packages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackages()
    {
        $packages = Package::with('vehicle')->get();
        return response()->json([
            'status' => true,
            'message' => 'Packages retrieved successfully',
            'data' => $packages
        ]);
    }

    /**
     * Get a specific package.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPackage($id)
    {
        $package = Package::with('vehicle')->find($id);

        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Package details retrieved successfully',
            'data' =>$package
        ]);
    }

    /**
     * Update a package.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePackage(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'vehicle_id' => 'sometimes|required|exists:vehicles,id',
            'days' => 'sometimes|required|integer|min:1',
            'km' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update package fields if provided
        if ($request->has('name')) $package->name = $request->name;
        if ($request->has('vehicle_id')) $package->vehicle_id = $request->vehicle_id;
        if ($request->has('days')) $package->days = $request->days;
        if ($request->has('km')) $package->km = $request->km;
        if ($request->has('price')) $package->price = $request->price;

        $package->save();

        return response()->json([
            'status' => true,
            'message' => 'Package updated successfully',
            'data' =>$package
        ]);
    }

    /**
     * Delete a package.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePackage($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => false,
                'message' => 'Package not found',
                'data' => null
            ], 404);
        }

        $package->delete();

        return response()->json([
            'status' => true,
            'message' => 'Package deleted successfully',
            'data' => null
        ]);
    }
}