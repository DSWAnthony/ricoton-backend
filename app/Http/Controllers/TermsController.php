<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terms\StoreTermsRequest;
use App\Http\Requests\Terms\UpdateTermsRequest;
use App\Services\TermService;

class TermsController extends Controller
{
    public function __construct( private TermService $termService ) {}


    public function showTerm()
    {
        $term = $this->termService->getTerm();

        if ($term) {
            return response()->json($term);
        }
        return response()->json(['message' => 'Term not found'], 404);
    }

    public function storeTerm(StoreTermsRequest $request)
    {

        $term = $this->termService->createTerms($request->validated());
        if ($term) {
            return response()->json($term, 201);
        }
        return response()->json(['message' => 'Failed to create term'], 500);
    }

    public function updateTerm(UpdateTermsRequest $request)
    {

        $term = $this->termService->updateTerms($request->validated());

        if ($term) {
            return response()->json($term);
        }

        return response()->json(['message' => 'Failed to update term'], 500);
    }

}
