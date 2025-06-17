<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use App\Models\Category;
use Storage;

/**
 * @OA\Tag(
 *     name="Categorías",
 *     description="Gestión de categorías de productos"
 * )
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Electrónicos"),
 *     @OA\Property(property="description", type="string", example="Productos electrónicos", nullable=true),
 *     @OA\Property(property="image_url", type="string", format="url", example="http://ejemplo.com/storage/categories/tech.jpg", nullable=true),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $service
    ) {}

    /**
     * Listar categorías activas
     *
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categorías"},
     *     summary="Obtener categorías activas",
     *     description="Endpoint público para listar categorías activas",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = $this->service->getActiveCategories();

        return response()->json($categories, 200);
    }

     /**
     * Crear nueva categoría
     *
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categorías"},
     *     summary="Crear nueva categoría",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/StoreCategoryRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría creada"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al crear la categoría")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
             $data = array_merge($request->all(), [
                'image_url' => Storage::disk('public')->url($imagePath)
            ]);
        }

        $category = $this->service->createCategory($data);
        
        if (!$category)
            return response()->json(['message' => 'Error al crear la categoría'], 500);
        
        return response()->json(['message' => 'Categoría creada', 'data' => $category], 201);
    }

     /**
     * Obtener categoría específica
     *
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Categorías"},
     *     summary="Obtener categoría por ID",
     *     description="Endpoint público para obtener detalles de una categoría",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $category = $this->service->findCategoryById($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        return response()->json($category, 200);
    }

    /**
     * Actualizar categoría
     *
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categorías"},
     *     summary="Actualizar categoría existente",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría actualizada"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error al actualizar la categoría")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = $this->service->updateCategory($id, $request->validated());

        if (!$category)
            return response()->json(['message' => 'Error al actualizar la categoría'], 500);

        return response()->json(['message' => 'Categoría actualizada', 'data' => $category], 200);
    }

    /**
     * Eliminar categoría
     *
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categorías"},
     *     summary="Eliminar categoría",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría eliminada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Categoría no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category)
            return response()->json(['message' => 'Categoría no encontrada'], 404);

        $category->delete();
        return response()->json(['message' => 'Categoría eliminada'], 200);
    }
}
