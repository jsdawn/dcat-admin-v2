<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

class UploadHandles
{
    //定义一个允许的后缀名属性
    protected $allow_ext = ['jpg', 'jpeg', 'png', 'gif'];

    public function saveImage($file, $userId)
    {
        //进行后缀名的验证,如果没有那么就默认为png
        $extension = $file->getClientOriginalExtension() ? strtolower($file->getClientOriginalExtension()) : 'png';
        if (!in_array($extension, $this->allow_ext)) {
            return false;
        }

        $filename = rawurlencode(date("Ymd", time()) . '_' . $file->getClientOriginalName());
        $path     = $file->storeAs('images/' . $userId, $filename, 'public');

        $url = rawurldecode(Storage::disk('public')->url($path));
        if (strpos($url, 'http') !== 0) {
            $url = config('app.url') . $url;
        }

        //返回图片已经存储的路径
        return ['path' => $url];
    }
}
