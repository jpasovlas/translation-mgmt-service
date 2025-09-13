<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TranslationController;
use App\Http\Controllers\Api\V1\ExportController;
use App\Http\Controllers\Api\V1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {
        // Export endpoint
        Route::get('translations/export', [ExportController::class, 'export']);

        // Translation endpoints
        Route::get('translations', [TranslationController::class, 'index']);
        Route::post('translations', [TranslationController::class, 'store']);
        Route::get('translations/{id}', [TranslationController::class, 'show']);
        Route::put('translations/{id}', [TranslationController::class, 'update']);
        Route::delete('translations/{id}', [TranslationController::class, 'destroy']);

        // Logout endpoint
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

require __DIR__.'/auth.php';