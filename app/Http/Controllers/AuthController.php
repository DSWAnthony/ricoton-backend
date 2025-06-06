<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function register(RegisterRequest $request)
    {

        $this->authService->register($request->validated());
        
        return response()->json([
            'message' => 'User registered successfully'
        ], 201);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(LoginRequest $request)
    {
        $request->validated();

        if (!$token = JWTAuth::attempt($request->validated())) {
            return response()->json(['error' => 'Credenciales Invalidas'], 401);
        }
        
        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
