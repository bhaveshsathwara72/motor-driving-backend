<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    /**
     * Display the About Us page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the latest About Us information
        $aboutUs = AboutUs::latest()->first();
        
        return view('about_us.index', compact('aboutUs'));
    }

    /**
     * Upload image for About Us.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_type' => 'required|in:about_image_1,about_image_2',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Image validation failed: ' . $validator->errors()->first());
        }

        $aboutUs = AboutUs::latest()->first();
        
        if (!$aboutUs) {
            return redirect()->back()->with('error', 'No About Us record found to update.');
        }

        $imageType = $request->image_type;
        $imagePath = null;
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($aboutUs->$imageType) {
                $oldImagePath = str_replace(url(''), '', $aboutUs->$imageType);
                if (file_exists(public_path($oldImagePath))) {
                    unlink(public_path($oldImagePath));
                }
            }
            
            $image = $request->file('image');
            $imageName = $imageType . '_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('about_us');
            
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            $image->move($directory, $imageName);
            $imagePath = 'about_us/' . $imageName;
            
            $aboutUs->$imageType = $imagePath;
            $aboutUs->save();
            
            return redirect()->back()->with('success', 'Image uploaded successfully.');
        }
        
        return redirect()->back()->with('error', 'No image was provided.');
    }
}