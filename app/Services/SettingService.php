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
        $setting = Setting::first();
        
        if ($setting) {
            $setting->update($data);
        } else {
            // Handle case where setting does not exist
            throw new \Exception('Setting not found');
        }
    }

    public function getSetting()
    {
        return Setting::first();
    }

     public function deleteImageForUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }
    
        $pathWithStorage = parse_url($url, PHP_URL_PATH);

        // Quita el prefijo 
        $relativePath = str::replaceFirst('/storage/', '', $pathWithStorage);

        log::info('Intentando borrar fichero en disco:', [
            'relativePath' => $relativePath,
            'exists'       => Storage::disk('public')->exists($relativePath),
        ]);

        // borramos
        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }
}