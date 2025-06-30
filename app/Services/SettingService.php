<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingService
{

    public function createSetting(array $data)
    {
       return Setting::create($data);
    }

    public function updateSetting(array $data)
    {
        $setting = Setting::firstOrFail();
        $setting->update($data);
        return $setting;
    }

    public function getSetting()
    {
        return Setting::first();
    }

    public function deleteLogo()
    {
        $setting = Setting::firstOrFail();
        if ($setting->logo_url) {
            // Delete the logo file from storage
            // Storage::disk('public')->delete($setting->logo_url);
            // Update the logo_url field to null
            $setting->logo_url = null;
            $setting->save();
        }
        return response()->json(['message' => 'Logo deleted successfully']);
    }

}