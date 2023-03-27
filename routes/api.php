<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MouController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\LabRoomController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PortfolioController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:api'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});


Route::group(['prefix' => 'user'], function () {
    Route::get('/{id}', [UserController::class, 'get']);
    Route::get('/', [UserController::class, 'getAll']);
    Route::post('/', [UserController::class, 'add']);
    Route::put('/{id}', [UserController::class, 'edit']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});

Route::group(['prefix' => 'slide'], function () {
    Route::get('/{id}', [SlideController::class, 'get']);
    Route::get('/', [SlideController::class, 'getAll']);
    Route::post('/', [SlideController::class, 'add']);
    Route::put('/{id}', [SlideController::class, 'edit']);
    Route::delete('/{id}', [SlideController::class, 'delete']);
});

Route::group(['prefix' => 'lab-room'], function () {
    Route::put('/level/{id}', [LabRoomController::class, 'editLevel']);
    Route::get('/{id}', [LabRoomController::class, 'get']);
    Route::get('/',     [LabRoomController::class, 'getAll']);
    Route::post('/', [LabRoomController::class, 'add']);
    Route::put('/{id}', [LabRoomController::class, 'edit']);
    Route::delete('/{id}', [LabRoomController::class, 'delete']);
});

Route::group(['prefix' => 'equipment'], function () {
    Route::put('/level/{id}', [EquipmentController::class, 'editLevel']);
    Route::get('/{id}', [EquipmentController::class, 'get']);
    Route::get('/',     [EquipmentController::class, 'getAll']);
    Route::post('/', [EquipmentController::class, 'add']);
    Route::put('/{id}', [EquipmentController::class, 'edit']);
    Route::delete('/{id}', [EquipmentController::class, 'delete']);
});

Route::group(['prefix' => 'organization'], function () {
    Route::get('/{id}', [OrganizationController::class, 'get']);
    Route::get('/',     [OrganizationController::class, 'getAll']);
    Route::post('/', [OrganizationController::class, 'add']);
    Route::put('/{id}', [OrganizationController::class, 'edit']);
    Route::delete('/{id}', [OrganizationController::class, 'delete']);
});

Route::group(['prefix' => 'member'], function () {
    Route::put('/level/{id}', [MemberController::class, 'editLevel']);
    Route::get('/{id}', [MemberController::class, 'get']);
    Route::get('/',     [MemberController::class, 'getAll']);
    Route::post('/', [MemberController::class, 'add']);
    Route::put('/{id}', [MemberController::class, 'edit']);
    Route::delete('/{id}', [MemberController::class, 'delete']);
});

Route::group(['prefix' => 'portfolio'], function () {
    Route::put('/level/{id}', [PortfolioController::class, 'editLevel']);
    Route::get('/{id}', [PortfolioController::class, 'get']);
    Route::get('/',     [PortfolioController::class, 'getAll']);
    Route::post('/', [PortfolioController::class, 'add']);
    Route::put('/{id}', [PortfolioController::class, 'edit']);
    Route::delete('/{id}', [PortfolioController::class, 'delete']);
});


// Route::group(['prefix' => 'mou'], function () {
//     Route::get('/{id}', [MouController::class, 'get']);
//     Route::get('/', [MouController::class, 'getAll']);
//     Route::post('/', [MouController::class, 'add']);
//     Route::put('/{id}', [MouController::class, 'edit']);
//     Route::delete('/{id}', [MouController::class, 'delete']);
// });

// Route::group(['prefix' => 'country'], function () {
//     Route::get('/{id}', [CountryController::class, 'get']);
//     Route::get('/', [CountryController::class, 'getAll']);
//     Route::post('/', [CountryController::class, 'add']);
//     Route::put('/{id}', [CountryController::class, 'edit']);
//     Route::delete('/{id}', [CountryController::class, 'delete']);
// });

// Route::group(['prefix' => 'host'], function () {
//     Route::get('/{id}', [HostController::class, 'get']);
//     Route::get('/', [HostController::class, 'getAll']);
//     Route::post('/', [HostController::class, 'add']);
//     Route::put('/{id}', [HostController::class, 'edit']);
//     Route::delete('/{id}', [HostController::class, 'delete']);
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });