<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Format\UserFormat;
use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    // 获取当前用户信息
    public function index(Request $request)
    {
        $users = Auth::user();

        return ApiResponse::withJson($users);
    }

    // 用户列表（分页）
    public function getList(Request $request)
    {
        $page    = $request->get('page', 1);
        $size    = $request->get('size', 10);
        $keyword = $request->get('keyword');

        $query = User::query();

        if ($keyword) {
            $query->where("id", $keyword)
                  ->where('name', 'like', '%' . $keyword . '%')
                  ->where('email', 'like', '%' . $keyword . '%');
        }

        $users = $query->paginate($size, ['*'], 'page', $page);

        return ApiResponse::withList($users, UserFormat::class, 'toList');
    }

    // 新增
    public function store(Request $request)
    {
        // 由注册接口实现
    }

    // 详情
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return ApiResponseApiResponse::withJson($user);
        } else {
            return ApiResponse::withError("未查询到该用户");
        }
    }

    // 修改
    public function update(Request $request, $id)
    {
        // 是否当前用户
        if (Auth::check() && Auth::id() != $id) {
            return ApiResponse::withError("无权修改他人信息");
        }

        $validator = Validator::make($request->all(), [
            'avatar'                => 'nullable|string',
            'name'                  => 'nullable|string|max:16',
            'email'                 => 'nullable|email',
            'password'              => 'nullable|string|min:6|max:16|confirmed',
            'password_confirmation' => 'nullable|string|min:6|max:16',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $user = User::find($id);
        if (empty($user)) {
            return ApiResponse::withError("未查询到该用户");
        }

        $validated = $validator->safe()->except(['password_confirmation']);


        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated = Arr::except($validated, ['password']);
        }

        $user->update($validated);
        return ApiResponse::withJson($user->fresh());
    }

    // 删除
    public function destroy($id)
    {
        // 是否当前用户
        if (Auth::user() && Auth::user()->id != $id) {
            return ApiResponse::withError("无权删除他人信息");
        }

        $user = User::find($id);
        if (empty($user)) {
            return ApiResponse::withError("未查询到该用户");
        }

        $user->delete();
        return ApiResponse::withJson(["id" => $id]);
    }

}
