<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('api.items', App\Http\Controllers\API\itemAPIController::class)
    ->except(['create', 'edit']);

Route::resource('api.items', App\Http\Controllers\API\ItemAPIController::class)
    ->except(['create', 'edit']);
