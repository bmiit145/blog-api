<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/home', function (Request $request) {
    return response()->json(['error' => "Unauthorised Access"]);
})->name('home');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'create']);
    Route::post('/posts/{postId}', [PostController::class, 'update']);
    Route::delete('/posts/{postId}', [PostController::class, 'delete']);
    Route::get('/posts/{postId}', [PostController::class, 'show']);
    Route::put('/posts/{postId}/active', [PostController::class, 'toggleActive']);
});