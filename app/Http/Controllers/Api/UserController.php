<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $users = User::get();
    return response()->json($users, 201);
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

    $validated = $validator->safe()->except(['password_confirmation']);
    $user = User::create($validated);
    return ApiResponse::withJson($user);
  }

  public function show($id)
  {
    $user = User::find($id);
    if ($user) {
      return response()->json($user, 200);
    } else {
      return response()->json(null, 404);
    }
  }

  public function update(Request $request, $id)
  {
    $user = User::find($id);
    if ($user) {
      $user->update($request->all());
      return response()->json($user, 200);
    } else {
      return response()->json(null, 404);
    }
  }

  public function destroy($id)
  {
    $user = User::find($id);
    if ($user) {
      $user->delete();
      return response()->json(null, 204);
    } else {
      return response()->json(null, 404);
    }
  }


}