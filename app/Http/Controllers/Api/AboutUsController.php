<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    /**
     * Create a new About Us record.
     */
    public function createAboutUs(Request $request)
    {
        $rules = [
          
            'about_phone_no_1' => 'required|string|max:15|unique:about_us',
            'about_phone_no_2' => 'nullable|string|max:15|unique:about_us',
            'address' => 'required|string',
            'opening_hours' => 'required|string',
            'about_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle image uploads
        $aboutImage1Path = null;
        if ($request->hasFile('about_image_1')) {
            $image = $request->file('about_image_1');
            $imageName = 'about_image_1_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('about_us');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $image->move($directory, $imageName);
            $aboutImage1Path = 'about_us/' . $imageName;
        }

        $aboutImage2Path = null;
        if ($request->hasFile('about_image_2')) {
            $image = $request->file('about_image_2');
            $imageName = 'about_image_2_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('about_us');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $image->move($directory, $imageName);
            $aboutImage2Path = 'about_us/' . $imageName;
        }

        // Create new record
        $aboutUs = AboutUs::create([
            'about_phone_no_1' => $request->about_phone_no_1,
            'about_phone_no_2' => $request->about_phone_no_2,
            'address' => $request->address,
            'opening_hours' => $request->opening_hours,
            'about_image_1' => $aboutImage1Path,
            'about_image_2' => $aboutImage2Path,
        ]);
        
        // Force the accessors to be applied by accessing the attributes
        $aboutUs->about_image_1 = $aboutUs->about_image_1;
        $aboutUs->about_image_2 = $aboutUs->about_image_2;

        return response()->json([
            'status' => true,
            'message' => 'About us information created successfully',
            'data' => $aboutUs
        ], 201);
    }

    /**
     * Retrieve a single About Us record (the latest one).
     */
    public function getAboutUs()
    {
        $aboutUs = AboutUs::latest()->first();

        if (!$aboutUs) {
            return response()->json([
                'status' => false,
                'message' => 'About us information not found',
                'data' => null
            ], 404);
        }
        
        // Force the accessors to be applied by accessing the attributes
        $aboutUs->about_image_1 = $aboutUs->about_image_1;
        $aboutUs->about_image_2 = $aboutUs->about_image_2;

        return response()->json([
            'status' => true,
            'message' => 'About us information retrieved successfully',
            'data' => $aboutUs
        ]);
    }

    /**
     * Retrieve a specific About Us record by ID.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAboutUsById($id)
    {
        $aboutUs = AboutUs::find($id);

        if (!$aboutUs) {
            return response()->json([
                'status' => false,
                'message' => 'About us information not found',
                'data' => null
            ], 404);
        }
        
        // Force the accessors to be applied by accessing the attributes
        $aboutUs->about_image_1 = $aboutUs->about_image_1;
        $aboutUs->about_image_2 = $aboutUs->about_image_2;

        return response()->json([
            'status' => true,
            'message' => 'About us information retrieved successfully',
            'data' => $aboutUs
        ]);
    }

    /**
     * Update a specific About Us record.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAboutUs(Request $request, $id)
    {
        $aboutUs = AboutUs::find($id);

        if (!$aboutUs) {
            return response()->json([
                'status' => false,
                'message' => 'About us information not found',
                'data' => null
            ], 404);
        }

        $rules = [
            'about_phone_no_1' => 'sometimes|required|string|max:15|unique:about_us,about_phone_no_1,' . $id,
            'about_phone_no_2' => 'nullable|string|max:15|unique:about_us,about_phone_no_2,' . $id,
            'address' => 'sometimes|required|string',
            'opening_hours' => 'sometimes|required|string',
            'about_image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about_image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('about_phone_no_1')) $aboutUs->about_phone_no_1 = $request->about_phone_no_1;
        if ($request->has('about_phone_no_2')) $aboutUs->about_phone_no_2 = $request->about_phone_no_2;
        if ($request->has('address')) $aboutUs->address = $request->address;
        if ($request->has('opening_hours')) $aboutUs->opening_hours = $request->opening_hours;

        if ($request->hasFile('about_image_1')) {
            if ($aboutUs->about_image_1 && file_exists(public_path($aboutUs->about_image_1))) {
                unlink(public_path($aboutUs->about_image_1));
            }
            $image = $request->file('about_image_1');
            $imageName = 'about_image_1_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('about_us');
            if (!file_exists($directory)) mkdir($directory, 0755, true);
            $image->move($directory, $imageName);
            $aboutUs->about_image_1 = 'about_us/' . $imageName;
        }

        if ($request->hasFile('about_image_2')) {
            if ($aboutUs->about_image_2 && file_exists(public_path($aboutUs->about_image_2))) {
                unlink(public_path($aboutUs->about_image_2));
            }
            $image = $request->file('about_image_2');
            $imageName = 'about_image_2_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('about_us');
            if (!file_exists($directory)) mkdir($directory, 0755, true);
            $image->move($directory, $imageName);
            $aboutUs->about_image_2 = 'about_us/' . $imageName;
        }

        $aboutUs->save();
        
        // Force the accessors to be applied by accessing the attributes
        $aboutUs->about_image_1 = $aboutUs->about_image_1;
        $aboutUs->about_image_2 = $aboutUs->about_image_2;

        return response()->json([
            'status' => true,
            'message' => 'About us information updated successfully',
            'data' => $aboutUs
        ]);
    }

    /**
     * Delete a specific About Us record.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAboutUs($id)
    {
        $aboutUs = AboutUs::find($id);

        if (!$aboutUs) {
            return response()->json([
                'status' => false,
                'message' => 'About us information not found',
                'data' => null
            ], 404);
        }

        if ($aboutUs->about_image_1 && file_exists(public_path($aboutUs->about_image_1))) {
            unlink(public_path($aboutUs->about_image_1));
        }

        if ($aboutUs->about_image_2 && file_exists(public_path($aboutUs->about_image_2))) {
            unlink(public_path($aboutUs->about_image_2));
        }

        $aboutUs->delete();

        return response()->json([
            'status' => true,
            'message' => 'About us information deleted successfully',
            'data' => null
        ]);
    }
}
