<?php

namespace App\Services;

use App\Http\Utils\DeleteImage;
use App\Models\About;
use App\Models\Terms;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AboutService
{
    use DeleteImage;

    /**
     * Get the settings.
     *
     * @return array
     */
    public function createAbout(array $data): About
    {
        
        return About::create($data);
    }

    public function updateAbout(array $data, ?UploadedFile $banner): About
    {
        $about = About::first();

        if ($banner) {
            if ($about && $about->banner_url) {
                $this->deleteImageForUrl($about->banner_url);
            }

            $bannerPath = $banner->store('banners', 'public');
            $data['banner_url'] = Storage::disk('public')->url($bannerPath);
        }

        if ($about) {
            $about->update($data);
        } else {
            throw new \Exception('Term not found');
        }

        return $about;
    }

    public function getAbout(): ?About
    {
        return About::first();
    }
}