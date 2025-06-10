<?php

namespace App\Http\Controllers;

use App\Http\Requests\Policy\StorePolicyRequest;
use App\Http\Requests\Policy\UpdatePolicyRequest;
use App\Services\PolicyService;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    
    public function __construct(private PolicyService $policyService) {}


    public function showPolicy()
    {
        $policy = $this->policyService->getTerms();

        return response()->json($policy);
    }

    public function createPolicy(StorePolicyRequest $request)
    {

        $policy = $this->policyService->createPolicy($request->validated());

        return response()->json([
            'message' => 'Policy created successfully',
            'policy' => $policy
        ], 201);
    }

    public function updatePolicy(UpdatePolicyRequest $request)
    {

        $policy = $this->policyService->updatePolicy($request->validated());
        
        if ($policy) {
            return response()->json([
                'message' => 'Policy updated successfully',
                'policy' => $policy
            ], 200);
        }

        return response()->json([
            'message' => 'Policy not found'
        ], 404);

    }
}
