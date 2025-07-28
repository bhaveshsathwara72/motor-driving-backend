<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestImageController extends Controller
{
    /**
     * Get all test images.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestImages()
    {
        $images = [];
        $testImagesPath = public_path('test_images');
        
        if (File::exists($testImagesPath)) {
            $files = File::files($testImagesPath);
            
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $images[] = [
                    'name' => $fileName,
                    'url' => asset('test_images/' . $fileName),
                    'type' => File::extension($file),
                    'size' => File::size($file)
                ];
            }
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Test images retrieved successfully',
            'data' => $images
        ]);
    }
    
    /**
     * Get a specific test image.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTestImage($filename)
    {
        $imagePath = public_path('test_images/' . $filename);
        
        if (!File::exists($imagePath)) {
            return response()->json([
                'status' => false,
                'message' => 'Image not found',
                'data' => null
            ], 404);
        }
        
        return response()->json([
            'status' => true,
            'message' => 'Test image retrieved successfully',
            'data' => [
                'name' => $filename,
                'url' => asset('test_images/' . $filename),
                'type' => File::extension($imagePath),
                'size' => File::size($imagePath),
                'content' => base64_encode(File::get($imagePath))
            ]
        ]);
    }
}