<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Format\UserFormat;
use App\Http\Response\ApiResponse;
use App\Models\Article;
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

        $query = Article::query();

        if ($keyword) {
            $query->where("id", $keyword)
                ->where('name', 'like', '%' . $keyword . '%')
                ->where('email', 'like', '%' . $keyword . '%');
        }

        $users = $query->paginate($size, ['*'], 'page', $page);

        return ApiResponse::withList($users, UserFormat::class, 'toList');
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
            'image' => 'nullable',
            'title' => 'required|max:50',
            'type' => 'required',
            'brief' => 'nullable|max:150',
            'content' => 'nullable|max:1000',
            'status' => 'required',
            'users_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::withError($validator);
        }

        $validated = $validator->validated();

        $article = Article::create($validated);
        return ApiResponse::withJson($article);
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
