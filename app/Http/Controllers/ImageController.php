<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    private $uploadFolder;

    public function __construct()
    {
        $this->uploadFolder = storage_path('app/public/uploads');
        if (!file_exists($this->uploadFolder)) {
            mkdir($this->uploadFolder, 0755, true);
        }
    }

    public function selectBaseImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $path = $file->storeAs('public/uploads', 'base_image.png');
        return response()->json(['message' => 'Base image uploaded successfully', 'path' => $path]);
    }

    public function selectSecondImage(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }

        $file = $request->file('file');
        $path = $file->storeAs('public/uploads', 'second_image.png');
        return response()->json(['message' => 'Second image uploaded successfully', 'path' => $path]);
    }



    public function mergeImages()
    {
        $baseImagePath = storage_path('app/public/uploads/base_image.png');
        $secondImagePath = storage_path('app/public/uploads/second_image.png');

        if (!file_exists($baseImagePath) || !file_exists($secondImagePath)) {
            return response()->json(['error' => 'Images not found'], 404);
        }

        // Open images
        $baseImage = Image::make($baseImagePath);
        $secondImage = Image::make($secondImagePath);

        // Resize the second image to 250x120 pixels
        $secondImage->resize(250, 120);

        // Insert the second image as a watermark at (20px, 100px)
        $baseImage->insert($secondImage, 'top-left', 20, 100);

        // Save the merged image
        $mergedImagePath = storage_path('app/public/uploads/merged_image.png');
        $baseImage->save($mergedImagePath);

        return response()->json([
            'message' => 'Images merged successfully with watermark',
            'path' => asset('storage/uploads/merged_image.png')
        ]);
    }
}
