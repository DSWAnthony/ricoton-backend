<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Utils\DeleteImage;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use DeleteImage;

    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function showSetting()
    {
        $setting = $this->settingService->getSetting();
        // Logic to return settings
        return response()->json($setting);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreSettingRequest $request)
    {
        // Logic to create settings
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('settings', 'public');
            $data['logo_url'] = Storage::disk('public')->url($filePath); 
        }

        $setting = $this->settingService->createSetting($data);
        
        return response()->json(['message' => 'Settings created successfully', 'data' => $setting], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $setting = $this->settingService->getSetting();

            // Delete the old image if it exists
            if ($setting && isset($setting->logo_url)) {
                $this->deleteImageForUrl($setting->logo_url);
            }

            $filePath = $request->file('image')->store('settings', 'public');
            $data['logo_url'] = Storage::disk('public')->url($filePath);
            
            $this->settingService->updateSetting($data);
        } else {
            $this->settingService->updateSetting($data);
        }

        return response()->json(['message' => 'Settings updated successfully'], 200);
    }
}
