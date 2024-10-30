## Sanctum API 身份认证

文档地址：http://laravel.p2hp.com/cndocs/8.x/sanctum

### 启用 Sanctum 认证

0. 安装（如有配置文件 config/sanctum.php，说明已成功安装并发布）

```sh
# 通过 Composer 安装 Laravel Sanctum
composer require laravel/sanctum
# 发布 Sanctum 的 配置(config文件夹) 和迁移文件
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
# 运行数据库迁移，Sanctum 将创建一个数据库表来存储 API 令牌
php artisan migrate
```

1. 添加 Sanctum 中间件到 `app/Http/Kernel.php` 文件中的 api 中间件组中:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ...
],
```

2. User 模型继承 `Illuminate\Foundation\Auth\User` 并使用 `Laravel\Sanctum\HasApiTokens`特性：

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    ...
}
```

### 身份认证默认配置（一般无需修改）

```php
// config/auth.php

'defaults' => [
    'guard' => 'web', // 默认看守器
    'passwords' => 'users',
],
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class, // 默认用户模型
    ],
],
```

### 验证路由示例

```php
// routes/api.php

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 路由组
Route::group([
    'namespace' => 'App\\Http\\Controllers\\Api',
    'middleware' => ['auth:sanctum'],
], function (Router $router) {
    // login
    $router->post('login', 'LoginController@login')->withoutMiddleware(['auth:sanctum']);
    // users
    $router->apiResource('users', 'UserController');
});
```

### 常用令牌方法

```php
use Illuminate\Support\Facades\Auth;

/**
 * 通过 Auth facade 访问 Laravel 的身份验证服务
 * Auth::user() 和 $request->user() 均可从token中获取当前认证用户$user
 * 实例$user可使用 Auth facade 的方法
 */

// 生成令牌 （token_name可以是用户id/账号/email等唯一标识符）
Auth::user()->createToken($request->token_name);

// 撤销所有令牌...
$user->tokens()->delete();

// 撤销用于验证当前请求的令牌...
$request->user()->currentAccessToken()->delete();

// 撤销指定令牌...
$user->tokens()->where('id', $tokenId)->delete();
```

### 常用 Auth 认证方法

```php
use Illuminate\Support\Facades\Auth;

/**
 * 通过 Auth facade 访问 Laravel 的身份验证服务
 */

// 获取 Auth 对象，等同于 Auth Facade
auth();
// 判断当前用户是否已认证（是否已登录）
Auth::check();
// 获取当前已经认证的用户实例。如果用户未登录，此方法将返回null
Auth::user();
// 自定义看守器 默认为 `web` (config/auth.php)
Auth::guard();
// 获取当前的认证用户的 ID（未登录情况下会报错，先check）
Auth::id();
// 验证用户凭证
Auth::validate($credentials);
// 验证用户凭证，成功后启用会话即自动登录 (检索db用户并比较哈希密码）
Auth::attempt(['email' => $email, 'password' => $password], $remember = false);
// 登录一个指定用户到应用上
Auth::login(User::find(1), $remember = false);
// 登录指定用户 ID 的用户到应用上
Auth::loginUsingId(1, $remember = false);
// 检测是否记住了登录
Auth::viaRemember();
// 使用户退出登录（清除会话）
Auth::logout();

```
