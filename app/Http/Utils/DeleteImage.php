<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait DeleteImage
{

    public function deleteImageForUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        try {
            $pathWithStorage = parse_url($url, PHP_URL_PATH);
            $relativePath = Str::replaceFirst('/storage/', '', $pathWithStorage);

            if (Storage::disk('public')->exists($relativePath)) {
                return Storage::disk('public')->delete($relativePath);
            }

            // Intentar con diferentes patrones de URL
            $alternativePath = Str::after($url, Storage::disk('public')->url(''));
            if (Storage::disk('public')->exists($alternativePath)) {
                return Storage::disk('public')->delete($alternativePath);
            }

            Log::warning("Imagen no encontrada: $url");
            return false;
        } catch (\Exception $e) {
            Log::error("Error eliminando imagen: {$url} - " . $e->getMessage());
            return false;
        }
    }
}