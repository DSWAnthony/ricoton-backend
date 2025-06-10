<?php

namespace App\Http\Utils;

use Storage;
use Str;


trait DeleteImage
{
    /**
     * Delete an image from the storage.
     *
     * @param string $imagePath
     * @return void
     */
    public function deleteImageForUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }
    
        $pathWithStorage = parse_url($url, PHP_URL_PATH);

        // Quita el prefijo 
        $relativePath = Str::replaceFirst('/storage/', '', $pathWithStorage);

        // Log::info('Intentando borrar fichero en disco:', [
        //     'relativePath' => $relativePath,
        //     'exists'       => Storage::disk('public')->exists($relativePath),
        // ]);

        // borramos
        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->delete($relativePath);
        }

        return false;
    }
}