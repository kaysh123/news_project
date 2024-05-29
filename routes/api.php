<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Version\AppVersionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned to the "api"
| middleware group. Enjoy building your API!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/news-api', [NewsApiController::class, 'fetch_news']);
Route::post('/fetch-cat', [NewsApiController::class, 'fetch_cat']);
Route::post('/search', [NewsApiController::class, 'search']);
Route::post('/country-search', [NewsApiController::class, 'country']);
Route::post('/notification', [NewsApiController::class, 'latest']);
//check app update
Route::post('/check-update', [AppVersionController::class, 'checkUpdate']);
//add new version
Route::post('/app-versions', [AppVersionController::class, 'addVersion']);

// Routes that require authentication    
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Task routes
});
