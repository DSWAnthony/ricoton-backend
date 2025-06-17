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

}