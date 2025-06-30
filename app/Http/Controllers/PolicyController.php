<?php

namespace App\Http\Controllers;

use App\Http\Requests\Policy\StorePolicyRequest;
use App\Http\Requests\Policy\UpdatePolicyRequest;
use App\Services\PolicyService;

/**
 * @OA\Tag(
 *     name="Políticas",
 *     description="Gestión de políticas de privacidad y uso"
 * )
 * @OA\Schema(
 *     schema="Policy",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Política de Privacidad"),
 *     @OA\Property(property="description", type="string", example="Contenido detallado de las políticas..."),
 *     @OA\Property(property="other_description", type="string", nullable=true, example="Sección adicional sobre políticas"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class PolicyController extends Controller
{
    
    public function __construct(private PolicyService $policyService) {}

     /**
     * Obtener política actual
     *
     * @OA\Get(
     *     path="/api/policy",
     *     tags={"Políticas"},
     *     summary="Obtener la política actual",
     *     description="Endpoint público para obtener las políticas de privacidad y uso",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Policy")
     *     )
     * )
     */ 
    public function showPolicy()
    {
        $policy = $this->policyService->getTerms();

        return response()->json($policy);
    }

    /**
     * Crear nueva política
     *
     * @OA\Post(
     *     path="/api/policy",
     *     tags={"Políticas"},
     *     summary="Crear nueva política",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePolicyRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Política creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Policy created successfully"),
     *             @OA\Property(property="policy", ref="#/components/schemas/Policy")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function createPolicy(StorePolicyRequest $request)
    {

        $policy = $this->policyService->createPolicy($request->validated());

        return response()->json([
            'message' => 'Policy created successfully',
            'policy' => $policy
        ], 201);
    }

    /**
     * Actualizar política existente
     *
     * @OA\Put(
     *     path="/api/policy",
     *     tags={"Políticas"},
     *     summary="Actualizar política existente",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePolicyRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Política actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Policy updated successfully"),
     *             @OA\Property(property="policy", ref="#/components/schemas/Policy")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Política no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Policy not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
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
