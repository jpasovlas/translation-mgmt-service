<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(
        protected ExportService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/translations/export",
     *     summary="Export translations",
     *     tags={"Export"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\Parameter(
     *         name="locale",
     *         in="query",
     *         required=true,
     *         description="Locale code",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         required=false,
     *         description="Comma-separated list of tags to export",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="flat",
     *         in="query",
     *         required=false,
     *         description="Whether to export translations as a flat array or as a nested array by group",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Translation export",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     )
     * )
     */
    public function export(Request $request): \Illuminate\Http\JsonResponse
    {
        $locale = $request->get('locale');
        $tags = $request->filled('tags') ? explode(',', $request->get('tags')) : null;
        $flat = $request->boolean('flat', false);

        $payload = $this->service->export($locale, $tags, $flat);

        return response()->json($payload)
            ->header('Cache-Control','no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma','no-cache');
    }
}
