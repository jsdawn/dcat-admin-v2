<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $articleId)
    {
        $page = $request->get('page', 1);
        $size = $request->get('size', 10);

        $query = ArticleComment::with(['user', 'toUser'])
            ->where('article_id', $articleId);

        $comments = $query->paginate($size, ['*'], 'page', $page);

        return ApiResponse::withList($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $articleId)
    {

        $article = Article::find($articleId);

        if (empty($article)) {
            return ApiResponse::withError("未查询到该记录");
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'to_user_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $validated = $validator->validated();
        $validated['article_id'] = $articleId;
        $validated['user_id'] = Auth::id();

        if (!empty($validated['to_user_id'])) {
            $toUser = User::find($validated['to_user_id']);
            if (empty($toUser)) {
                return ApiResponse::withError("未查询到该评论对象");
            }
        }

        $comment = ArticleComment::create($validated);
        return ApiResponse::withJson($comment->fresh());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = ArticleComment::with(['user', 'toUser'])
            ->find($id);

        if (empty($comment)) {
            return ApiResponse::withError("未查询到该记录");
        }

        return ApiResponse::withJson($comment);
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
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }


        $comment = ArticleComment::find($id);
        if (empty($comment)) {
            return ApiResponse::withError("未查询到该记录");
        }

        if ($comment["user_id"] != Auth::id()) {
            return ApiResponse::withError("无权修改该记录");
        }

        $validated = $validator->validated();

        $comment->update($validated);
        return ApiResponse::withJson($comment->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = ArticleComment::find($id);
        if (empty($comment)) {
            return ApiResponse::withError("未查询到该记录");
        }

        if ($comment["user_id"] != Auth::id()) {
            return ApiResponse::withError("无权删除该记录");
        }

        $comment->delete();
        return ApiResponse::withJson(["id" => $id]);
    }
}
