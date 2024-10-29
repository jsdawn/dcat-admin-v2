<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Utils\UploadHandles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function ImageUpload(Request $request, UploadHandles $handles)
    {
        $image = $handles->saveImage($request->file('file'), Auth::id());

        return ApiResponse::withJson($image);
    }

}
