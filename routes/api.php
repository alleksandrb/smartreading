<?php

use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('tasks', [TaskController::class, 'index']);
Route::post('tasks', [TaskController::class, 'store']);
Route::get('tasks/{taskId}', [TaskController::class, 'show']);
Route::put('tasks/{taskId}', [TaskController::class, 'update']);
Route::delete('tasks/{taskId}', [TaskController::class, 'destroy']);
