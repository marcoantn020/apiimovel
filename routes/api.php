<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\RealStateController;
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

    // users
    Route::name("user_profile.")
        ->group(function () {
            Route::apiResource("user-profile", UserProfileController::class);
        });

});
