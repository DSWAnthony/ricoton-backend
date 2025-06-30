<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Ricoton API",
 *     version="1.0.0",
 *     description="Documentación de la API de autenticación con Swagger en Laravel 12",
 *     @OA\Contact(
 *         email="soporte@tuempresa.com"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8000/",
 *     description="API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
