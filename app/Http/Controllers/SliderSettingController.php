<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SliderSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SliderSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sliderSetting = SliderSetting::first();
        if (!$sliderSetting) {
            return view('slider-setting.create');
        }
        return view('slider-setting.index', compact('sliderSetting'));
    }

    public function create()
    {
        return view('slider-setting.create');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'status' => 'required|boolean',
        //     'autoplay' => 'required|boolean',
        //     'duration' => 'required|integer|min:1',
        // ]);

        $SliderSetting = SliderSetting::create([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN),
            'autoplay' => filter_var($request->input('autoplay'), FILTER_VALIDATE_BOOLEAN),
            'duration' => $request->input('duration'),
            'click_url' => $request->click_url,
        ]);
        // Prepare an array to store image data
        $imageDataArray = [];

        // Get all inputs including files
        $imgUrls = $request->input('img_url', []);
        $files = $request->file('img_url', []);

        // Process image URLs
        foreach ($imgUrls as $index => $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $imageDataArray[] = [
                    'img_url' => $url,
                    'file_path' => null,
                ];
            }
        }

        // Process file uploads
        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('images', 'public');
                $imageDataArray[] = [
                    'img_url' => url(Storage::url($path)),
                    'file_path' => null,
                ];
            }
        }
        // Store the image data in the database
        foreach ($imageDataArray as $imageData) {
            $SliderSetting->imageUrls()->create($imageData);
        }

        return redirect()->back()->with('success', 'Data stored successfully!');
    }

    public function edit(SliderSetting $sliderSetting)
    {
        return view('slider-settings.edit', compact('sliderSetting'));
    }

    public function update(Request $request, SliderSetting $sliderSetting)
    {
        $request->validate([
            'duration' => 'required|integer|min:1',
            'click_url' => 'required|url',
        ]);

        $sliderSetting->update([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN),
            'autoplay' => filter_var($request->input('autoplay'), FILTER_VALIDATE_BOOLEAN),
            'duration' => $request->input('duration'),
            'click_url' => $request->click_url,
        ]);

        // Prepare an array to store image data
        $imageDataArray = [];

        // Get all inputs including files
        $imgUrls = $request->input('img_url', []);
        $files = $request->file('img_url', []);

        // Process image URLs
        foreach ($imgUrls as $index => $url) {
            $imageDataArray[] = [
                'img_url' => $url,
            ];
        }

        // Process file uploads
        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('images', 'public');
                $imageDataArray[] = [
                    'img_url' => url(Storage::url($path)),
                ];
            }
        }
        // Remove old image URLs
        $sliderSetting->imageUrls()->delete();

        // Store the new image data in the database
        foreach ($imageDataArray as $imageData) {
            $sliderSetting->imageUrls()->create($imageData);
        }

        return redirect()->back()->with('success', 'Data updated successfully!');
    }
}
