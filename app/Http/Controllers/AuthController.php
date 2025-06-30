<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="autenticación de usuarios"
 * )
 */
class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService
    ) {}
    
    /**
     * Register
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Autenticación"},
     *     summary="Registro de usuario",   
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario registrado correctamente"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al crear Usuario")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {

        $this->authService->register($request->validated());
        
        return response()->json([
            'message' => 'User registered successfully'
        ], 201);
        
    }

    /**
     * Login
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticación"},
     *     summary="Login de usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Credenciales correctas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario Autenticado correctamente"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al autenticar usuario")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $request->validated();

        if (!$token = JWTAuth::attempt($request->validated())) {
            return response()->json(['error' => 'Credenciales Invalidas'], 401);
        }
        
        return response()->json([
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

}
