<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\Admin\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group([
    'middleware' => 'api',
    // 'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [UserController::class, 'me']);
    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'roles'
], function ($router) {
    Route::get('/', [RolController::class, 'index']);
    Route::get('create', [RolController::class, 'create']);
    Route::post('store', [RolController::class, 'store']);
    Route::get('edit/{rol_id}', [RolController::class, 'edit']);
    Route::put('update/{rol_id}', [RolController::class, 'update']);
});


Route::post('register', [UserController::class, 'register']);