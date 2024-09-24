<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Format\UserFormat;
use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

  public function index(Request $request)
  {
    $page = $request->get('page', 1);
    $size = $request->get('size', 10);
    $keyword = $request->get('keyword');

    $query = User::query();

    if ($keyword) {
      $query->where("id", $keyword)
        ->where('name', 'like', '%' . $keyword . '%')
        ->where('email', 'like', '%' . $keyword . '%');
    }

    // $users = $query->paginate($size, ['*'], 'page', $page);
    $users = $query->get();

    return ApiResponse::withList($users, UserFormat::class, 'toList');
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|max:16',
      'email' => 'required|email',
      'password' => 'required|min:6|max:16|confirmed',
      'password_confirmation' => 'required',
    ]);

    if ($validator->fails()) {
      return ApiResponse::withError($validator);
    }

    $validated = $validator->safe()->only(['name', 'email', 'password']);
    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);
    return ApiResponse::withJson($user);
  }

  public function show($id)
  {
    $user = User::find($id);
    if ($user) {
      return ApiResponse::withJson($user);
    } else {
      return ApiResponse::withError("未查询到该用户");
    }
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'nullable|max:16',
      'email' => 'nullable|email',
      'password' => 'nullable|min:6|max:16|confirmed',
    ]);

    if ($validator->fails()) {
      return ApiResponse::withError($validator);
    }

    $user = User::find($id);
    if ($user) {
      $validated = $validator->safe()->only(['name', 'email', 'password']);

      if (Arr::exists($validated, 'password')) {
        $validated['password'] = Hash::make($validated['password']);
      }

      $user->update($validated);
      return ApiResponse::withJson($user);

    } else {
      return ApiResponse::withError("未查询到该用户");
    }
  }

  public function destroy($id)
  {
    $user = User::find($id);
    if ($user) {
      $user->delete();
      return ApiResponse::withJson(["id" => $id]);
    } else {
      return ApiResponse::withError("未查询到该用户");
    }
  }


}