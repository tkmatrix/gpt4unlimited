<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication Routes
Route::prefix('auth')->group(function() {
    // Un-protected Routes
    Route::post('/check-email/{type}', [AuthController::class, 'check_email']);
    Route::post('/check/{type}', [AuthController::class, 'check']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function() {
        // Validate Token
        Route::get('/validate-token', function(Request $request){return response()->json(AuthController::return_session_data($request), 200);});
        Route::post('/validate-token', function(Request $request){return response()->json(AuthController::return_session_data($request), 200);});

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});