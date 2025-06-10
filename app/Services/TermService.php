<?php

namespace App\Services;

use App\Models\Terms;

class TermService
{
    /**
     * Get the settings.
     *
     * @return array
     */
    public function createTerms(array $data): Terms
    {
        
        return Terms::create($data);
    }

    public function updateTerms(array $data): Terms
    {
        $term = Terms::first();

        if ($term) {
            $term->update($data);
        } else {
            throw new \Exception('Term not found');
        }

        return $term;
    }

    public function getTerm(): ?Terms
    {
        return Terms::first();
    }
}