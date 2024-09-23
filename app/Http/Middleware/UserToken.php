<?php

namespace App\Http\Middleware\Api;

use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Routing\Router;

class UserToken
{

    protected $router;

    /**
     * @var array
     */
    protected $authAction = [
        'api.v1.user.loginBindOpenId',
    ];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {


        $currentAction = $this->router->getCurrentRoute()->getAction();
        if (!in_array($currentAction['as'], $this->authAction)) {
            return $next($request);
        }

        $xUserToken = $request->header('X-User-Token');

        $user = User::query()->where('token', $xUserToken)->first();
        if (!$user) {
            return ApiResponse::withWarning('客户端与服务器端通信密钥校对失败', 401);
        }

        $request->auth_user_id = $user->id;
        $request->auth_user = $user;

        return $next($request);
    }

    public static function parseRequest($request)
    {
        $xUserToken = $request->header('X-User-Token');

        return User::query()->where('token', $xUserToken)->first();
    }

}
