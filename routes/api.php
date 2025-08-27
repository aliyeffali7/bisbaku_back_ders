<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'me']);
});

Route::get('/courses', [CourseController::class,'index']);
Route::get('/courses/{id}', [CourseController::class,'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/courses', function (Request $request) {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin role required.'], 403);
            return app(CourseController::class)->store($request);
        }
    });

    Route::put('/courses/{id}', function (Request $request, $id) {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin role required.'], 403);
        }
        return app(CourseController::class)->update($request, $id);
    });

    Route::delete('/courses/{id}', function (Request $request, $id) {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin role required.'], 403);
        }
        return app(CourseController::class)->destroy($id);
    });
});