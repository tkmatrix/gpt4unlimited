<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Chat\ChatsController;
use App\Http\Controllers\Messages\MessagesController;
use App\Http\Controllers\User\UserController;
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
Route::prefix('/auth')->group(function() {
    // Un-protected Routes
    Route::post('check-email/{type}', [AuthController::class, 'check_email']);
    Route::post('check/{type}', [AuthController::class, 'check']);
    // Google Auth Login
    Route::get('google', [AuthController::class, 'google']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function() {
        // Validate Token
        Route::get('validate', function(Request $request){return response()->json(AuthController::return_session_data($request), 200);});
        Route::post('validate', function(Request $request){return response()->json(AuthController::return_session_data($request), 200);});

        Route::get('logout/{all?}', [AuthController::class, 'logout']);
    });
});

// Protected Account Related Routes
Route::middleware('auth:sanctum')->prefix('/account')->group(function() {
    Route::post('setup', [UserController::class, 'account_setup']);
    Route::post('picture', [UserController::class, 'upload_pfp']);
    Route::post('update-email', [UserController::class, 'update_email']);
    Route::post('update-password', [UserController::class, 'update_password']);
});

// Protected Chat Routes
Route::middleware('auth:sanctum')->prefix('/chat')->group(function() {
    Route::get('get/{id}', [ChatsController::class, 'get']);
    Route::post('save-session', [ChatsController::class, 'save_session_chat']);

    Route::post('new-prompt/{chat_id}', [MessagesController::class, 'new_prompt']);
});