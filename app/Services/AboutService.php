<?php

namespace App\Services;

use App\Models\About;
use App\Models\Terms;

class AboutService
{
    /**
     * Get the settings.
     *
     * @return array
     */
    public function createAbout(array $data): About
    {
        
        return About::create($data);
    }

    public function updateAbout(array $data): About
    {
        $term = About::first();

        if ($term) {
            $term->update($data);
        } else {
            throw new \Exception('Term not found');
        }

        return $term;
    }

    public function getAbout(): ?About
    {
        return About::first();
    }
}