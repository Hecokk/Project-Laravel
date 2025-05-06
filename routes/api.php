<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

// Health check endpoint - NO AUTH required
Route::get('/health', function () {
    Log::info('Health check endpoint accessed');
    try {
        DB::connection()->getPdo();
        return response()->json([
            'status' => 'ok',
            'message' => 'Service is running',
            'database' => 'connected'
        ]);
    } catch (\Exception $e) {
        Log::error('Database connection failed: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Service is running but database connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
