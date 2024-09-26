<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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


Route::group([
    'namespace' => 'App\\Http\\Controllers\\Api',
    'middleware' => ['auth:sanctum'],
], function (Router $router) {

    // login
    $router->post('login', 'LoginController@login')
        ->name('login')
        ->withoutMiddleware(['auth:sanctum']);

    // user
    $router->apiResource('users', 'UserController');

    // article
    $router->apiResource('articles', 'UserController');


});