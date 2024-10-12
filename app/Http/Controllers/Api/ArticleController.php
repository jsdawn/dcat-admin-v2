<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Format\UserFormat;
use App\Http\Response\ApiResponse;
use App\Models\Article;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);
        $keyword = $request->get('keyword');

        $query = Article::query()->with(['author', 'comments.user', 'comments.toUser']);

        if ($keyword) {
            $query->where("id", $keyword)
                ->where('name', 'like', '%' . $keyword . '%')
                ->where('email', 'like', '%' . $keyword . '%');
        }

        $users = $query->paginate($size, ['*'], 'page', $page);

        return ApiResponse::withList($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string',
            'title' => 'required|string|max:50',
            'type' => 'integer', // 如果有该字段，必须是整数
            'brief' => 'nullable|string|max:150', // 可为空/null，有值必须为字符串
            'content' => 'nullable|string|max:1000',
            'status' => 'integer',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $validated = $validator->validated();
        $validated['user_id'] = Auth::id();

        $article = Article::create($validated);
        return ApiResponse::withJson($article->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::with(['author', 'comments'])
            ->find($id);

        if (empty($article)) {
            return ApiResponse::withError("未查询到该记录");
        }

        return ApiResponse::withJson($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 传了字段key，则改，不传key则不修改
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|string',
            'title' => 'string|max:50',
            'type' => 'integer', // 如果有该字段，必须整数
            'brief' => 'nullable|string|max:150', // 可为空/null，有值必须为字符串
            'content' => 'nullable|string|max:1000',
            'status' => 'integer',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }


        $article = Article::find($id);
        if (empty($article)) {
            return ApiResponse::withError("未查询到该记录");
        }

        if ($article["user_id"] != Auth::id()) {
            return ApiResponse::withError("无权修改该记录");
        }

        $validated = $validator->validated();

        $article->update($validated);
        return ApiResponse::withJson($article->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        if (empty($article)) {
            return ApiResponse::withError("未查询到该记录");
        }

        if ($article["user_id"] != Auth::id()) {
            return ApiResponse::withError("无权删除该记录");
        }

        $article->delete();
        return ApiResponse::withJson(["id" => $id]);
    }
}
