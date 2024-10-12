<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articleId = $request->get('article_id');

        $query = ArticleLike::query()->with(['user']);
        if ($articleId) {
            $query->where("id", $articleId);
        }

        return ApiResponse::withList($query->get());
    }

    /**
     * 点赞/取消点赞
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required|integer',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }
        $validated = $validator->safe()->only(['article_id']);
        $validated['user_id'] = Auth::id();

        // 是否有该文章
        $article = Article::find($validated['article_id']);
        if (empty($article)) {
            return ApiResponse::withError("未查询到该记录");
        }

        $like = ArticleLike::query()
            ->with(['user'])
            ->where('article_id', $validated['article_id'])
            ->where("user_id", $validated['user_id'])
            ->first();

        if ($request->get('status') == 1) {
            // 点赞
            if (empty($like)) {
                $like = ArticleLike::create($validated);
                $like = $like->with(['user'])->find($like['id']);
            }
            return ApiResponse::withJson($like);
        } else {
            // 取消点赞
            $delId = 0;
            if (!empty($like)) {
                $like->delete();
                $delId = $like["id"];
            }
            return ApiResponse::withJson(["id" => $delId]);
        }
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
    public function update(Request $request, $id)
    {
        //
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
