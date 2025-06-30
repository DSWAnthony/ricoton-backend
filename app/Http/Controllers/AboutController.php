<?php

namespace App\Http\Controllers;

use App\Http\Requests\About\CreateAboutRequest;
use App\Http\Requests\About\UpdateAboutRequest;
use App\Services\AboutService;

class AboutController extends Controller
{
    public function __construct(
        private AboutService $termService
    ) {}

    public function showTerm()
    {
        $term = $this->termService->getAbout();

        if ($term) {
            return response()->json($term);
        }
        return response()->json(['message' => 'Term not found'], 404);
    }

    public function storeTerm(CreateAboutRequest $request)
    {

        $term = $this->termService->createAbout($request->validated());
        if ($term) {
            return response()->json($term, 201);
        }
        return response()->json(['message' => 'Failed to create term'], 500);
    }
    
    public function updateTerm(UpdateAboutRequest $request)
    {

        $term = $this->termService->updateAbout($request->validated());

        if ($term) {
            return response()->json($term);
        }

        return response()->json(['message' => 'Failed to update term'], 500);
    }
}
