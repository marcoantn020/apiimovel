<?php

use App\Http\Controllers\Api\Auth\LoginJwtController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStateController;
use App\Http\Controllers\Api\RealStatePhotoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserProfileController;
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



Route::prefix("v1")->group(function () {

    Route::post('/login', [LoginJwtController::class, 'login']);
    Route::get('/logout', [LoginJwtController::class, 'logout']);
    Route::get('/me', [LoginJwtController::class, 'me']);
    Route::get('/refresh', [LoginJwtController::class, 'refresh']);

    Route::group(['middleware' => ['jwt.auth']], function () {

        //real-states
        Route::name("real_states.")
            ->group(function () {
                Route::apiResource("/real-states", RealStateController::class);
            });

        // users
        Route::name("users.")
            ->group(function () {
                Route::apiResource("users", UserController::class);
            });

        // categories
        Route::name("categories.")
            ->group(function () {
                Route::get("categories/{id}/real-states", [CategoryController::class, 'realStates']);
                Route::apiResource("categories", CategoryController::class);
            });

        // users profile
        Route::name("user_profile.")
            ->group(function () {
                Route::apiResource("user-profile", UserProfileController::class);
            });

        // users profile
        Route::name("photos.")->prefix('photos')
            ->group(function () {
                Route::delete('/{id}', [RealStatePhotoController::class, 'remove']);
                Route::put('/set-thumb/{photoId}/{realStateId}', [RealStatePhotoController::class, 'setThumb']);
            });
    });

});
