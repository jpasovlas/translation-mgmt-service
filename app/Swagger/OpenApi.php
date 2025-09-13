<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Translation API",
 *     version="1.0.0",
 *     description="API documentation for the Translation Service built with Laravel"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api/v1",
 *     description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use Laravel Sanctum bearer token for authentication"
 * )
 */
class OpenApi {}
