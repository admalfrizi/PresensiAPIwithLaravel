<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PresensiController;

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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::post('register', [AuthController::class, 'daftarkan']);
Route::post('login',[AuthController::class, 'masuk']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('profile', function(Request $request) {
        return auth()->user();
    });

    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/get-presensi', [PresensiController::class, 'getData']);
    Route::post('save-presensi', [PresensiController::class, 'saveData']);

});
