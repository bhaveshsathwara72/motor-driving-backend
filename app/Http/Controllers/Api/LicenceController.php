<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Licence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenceController extends Controller
{
    /**
     * Create a new licence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createLicence(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create licence
        $licence = Licence::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Licence created successfully',
            'data' => $licence
        ], 201);
    }

    /**
     * Get all licences.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLicences()
    {
        $licences = Licence::all();
        return response()->json([
            'status' => true,
            'message' => 'Licences retrieved successfully',
            'data' => $licences
        ]);
    }

    /**
     * Get a specific licence.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLicence($id)
    {
        $licence = Licence::find($id);

        if (!$licence) {
            return response()->json([
                'status' => false,
                'message' => 'Licence not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Licence retrieved successfully',
            'data' => $licence
        ]);
    }

    /**
     * Update a licence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLicence(Request $request, $id)
    {
        $licence = Licence::find($id);

        if (!$licence) {
            return response()->json([
                'status' => false,
                'message' => 'Licence not found',
                'data' => null
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update licence fields if provided
        if ($request->has('name')) $licence->name = $request->name;
        if ($request->has('price')) $licence->price = $request->price;
        if ($request->has('description')) $licence->description = $request->description;

        $licence->save();

        return response()->json([
            'status' => true,
            'message' => 'Licence updated successfully',
            'data' => $licence
        ]);
    }

    /**
     * Delete a licence.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLicence($id)
    {
        $licence = Licence::find($id);

        if (!$licence) {
            return response()->json([
                'status' => false,
                'message' => 'Licence not found',
                'data' => null
            ], 404);
        }

        $licence->delete();

        return response()->json([
            'status' => true,
            'message' => 'Licence deleted successfully',
            'data' => null
        ]);
    }
}