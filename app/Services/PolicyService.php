<?php

namespace App\Services;

use App\Models\Policy;


class PolicyService
{

    public function createPolicy(array $data): Policy
    {
        return Policy::create($data);
    }

    public function updatePolicy(array $data)
    {
        $policy = Policy::first();
        if ($policy) {
            $policy->update($data);
            return $policy;
        }
        
    }

    /**
     * Get the terms and conditions.
     *
     * @return string
     */
    public function getTerms(): Policy|null
    {
        $policy = Policy::first();
        if ($policy) {
            return $policy;
        }
        return null;
    }
}