<?php

namespace App\Http\Controllers;

use App\Http\Requests\Terms\StoreTermsRequest;
use App\Http\Requests\Terms\UpdateTermsRequest;
use App\Services\TermService;

/**
 * @OA\Tag(
 *     name="Términos y Condiciones",
 *     description="Gestión de los términos y condiciones del servicio"
 * )
 * @OA\Schema(
 *     schema="Term",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Términos de Servicio"),
 *     @OA\Property(property="description", type="string", example="Contenido detallado..."),
 *     @OA\Property(property="other_description", type="string", nullable=true, example="Sección adicional"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class TermsController extends Controller
{
    public function __construct( private TermService $termService ) {}

     /**
     * Obtener términos actuales
     *
     * @OA\Get(
     *     path="/api/terms",
     *     tags={"Términos y Condiciones"},
     *     summary="Obtener los términos y condiciones actuales",
     *     description="Endpoint público para obtener los términos y condiciones",
     *     @OA\Response(
     *         response=200,
     *         description="Términos obtenidos exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Term")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Términos no encontrados",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Term not found")
     *         )
     *     )
     * )
     */
    public function showTerm()
    {
        $term = $this->termService->getTerm();

        if ($term) {
            return response()->json($term);
        }
        return response()->json(['message' => 'Term not found'], 404);
    }

    /**
     * Crear nuevos términos
     *
     * @OA\Post(
     *     path="/api/terms",
     *     tags={"Términos y Condiciones"},
     *     summary="Crear nuevos términos y condiciones",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreTermsRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Términos creados exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Term")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al crear términos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to create term")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function storeTerm(StoreTermsRequest $request)
    {

        $term = $this->termService->createTerms($request->validated());
        if ($term) {
            return response()->json($term, 201);
        }
        return response()->json(['message' => 'Failed to create term'], 500);
    }

    /**
     * Actualizar términos existentes
     *
     * @OA\Put(
     *     path="/api/terms",
     *     tags={"Términos y Condiciones"},
     *     summary="Actualizar términos y condiciones existentes",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTermsRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Términos actualizados exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Term")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar términos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to update term")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function updateTerm(UpdateTermsRequest $request)
    {

        $term = $this->termService->updateTerms($request->validated());

        if ($term) {
            return response()->json($term);
        }

        return response()->json(['message' => 'Failed to update term'], 500);
    }

}
