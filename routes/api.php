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

Route::resource('items', App\Http\Controllers\API\itemAPIController::class)
    ->except(['create', 'edit']);

Route::resource('items', App\Http\Controllers\API\ItemAPIController::class)
    ->except(['create', 'edit']);

Route::resource('blogs', App\Http\Controllers\API\BlogAPIController::class)
    ->except(['create', 'edit']);

Route::post('files/link', [App\Http\Controllers\API\FileApiController::class, 'link'])->name('files.link');;

Route::post('files/delete', [App\Http\Controllers\API\FileApiController::class, 'destroy'])->name('files.delete');;

Route::resource('files', App\Http\Controllers\API\FileApiController::class)
    ->except(['show', 'create', 'edit', 'destroy']);
