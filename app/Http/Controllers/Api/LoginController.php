<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * laravel手动验证用户：http://laravel.p2hp.com/cndocs/8.x/authentication#scroll-nav__3
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $credentials = $validator->validated();

        /**
         * attempt 登录用户 (检索db用户并比较哈希密码）
         * config/auth.php [providers] 默认使用User模型
         * return bool
         */
        if (Auth::attempt($credentials)) {

            // 撤销所有令牌（单点登录）
            Auth::user()->tokens()->delete();
            // Sanctum 发布令牌
            $token = Auth::user()->createToken($request->get('email'));

            return ApiResponse::withJson(['token' => $token->plainTextToken, 'user' => Auth::user()]);
        }

        return ApiResponse::withError('用户名/密码错误');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avatar'                => 'nullable|string',
            'name'                  => 'required|max:16',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|max:16|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $validated = $validator->safe()->only(['name', 'email', 'password']);

        $user = User::where('email', $validated['email'])->first();
        if (! empty($user)) {
            return ApiResponse::withError('该邮件已被注册');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return ApiResponse::withJson($user->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
