<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('storage/images/{user_id}/{file_name}', function ($userId, $filename) {
    $filename = rawurlencode($filename);
    return response()->file(public_path("storage/images/{$userId}/{$filename}"));
})->where('path', '.*');
