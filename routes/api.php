<?php

use App\Http\Response\ApiResponse;
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

    // 框架auth验证未通过默认跳到 name为login的路由
    $router->get('auth', function (Request $request) {
        return ApiResponse::withError('Unauthenticated!', 401);
    })
        ->name('login')
        ->withoutMiddleware(['auth:sanctum']);

    // login
    $router->post('login', 'LoginController@login')
        ->withoutMiddleware(['auth:sanctum']);

    // user
    $router->apiResource('users', 'UserController')->except('store');
    $router->post('users', 'UserController@store')->withoutMiddleware(['auth:sanctum']);

    // article
    $router->apiResource('articles', 'ArticleController');

    // Article Comment
    /**
     * 浅层嵌套 
     * index=>/photos/{photo}/comments, store=>/photos/{photo}/comments
     * show,update,destroy=>/comments/{comment}
     */
    $router->apiResource('article.comments', 'ArticleCommentController')->shallow();

});