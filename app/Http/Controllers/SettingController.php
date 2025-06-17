<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Utils\DeleteImage;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Configuración",
 *     description="Gestión de la configuración de la empresa"
 * )
 * @OA\Schema(
 *     schema="Setting",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="company_name", type="string", example="Mi Empresa"),
 *     @OA\Property(property="company_description", type="string", example="Descripción de ejemplo"),
 *     @OA\Property(property="logo_url", type="string", format="url", example="http://ejemplo.com/storage/settings/logo.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SettingController extends Controller
{
    use DeleteImage;

    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * Obtener configuración actual
     *
     * @OA\Get(
     *     path="/api/setting",
     *     tags={"Configuración"},
     *     summary="Obtener configuración actual",
     *     description="Endpoint público para obtener la configuración de la empresa",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/Setting")
     *     )
     * )
     */
    public function showSetting()
    {
        $setting = $this->settingService->getSetting();
        // Logic to return settings
        return response()->json($setting);
    }

    /**
     * Crear nueva configuración
     *
     * @OA\Post(
     *     path="/api/setting",
     *     tags={"Configuración"},
     *     summary="Crear nueva configuración",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/StoreSettingRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Configuración creada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Settings created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Setting")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function store(StoreSettingRequest $request)
    {
        // Logic to create settings
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('settings', 'public');
            $data['logo_url'] = Storage::disk('public')->url($filePath); 
        }

        $setting = $this->settingService->createSetting($data);
        
        return response()->json(['message' => 'Settings created successfully', 'data' => $setting], 201);
    }

    /**
     * Actualizar configuración existente
     *
     * @OA\Put(
     *     path="/api/setting",
     *     tags={"Configuración"},
     *     summary="Actualizar configuración existente",
     *     description="Endpoint protegido que requiere token de autenticación",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/UpdateSettingRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Configuración actualizada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Settings updated successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Configuración no encontrada"
     *     )
     * )
     */
    public function update(UpdateSettingRequest $request)
    {

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $setting = $this->settingService->getSetting();

            // Delete the old image if it exists
            if ($setting && isset($setting->logo_url)) {
                $this->deleteImageForUrl($setting->logo_url);
            }

            $filePath = $request->file('image')->store('settings', 'public');
            $data['logo_url'] = Storage::disk('public')->url($filePath);
            
            $this->settingService->updateSetting($data);
        } else {
            $this->settingService->updateSetting($data);
        }

        return response()->json(['message' => 'Settings updated successfully'], 200);
    }
    
}
