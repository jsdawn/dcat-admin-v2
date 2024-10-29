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

        //定义存储路径，文件夹切割能让查找效率更高
//        $folder_name = "uploads/images/" . $folder . date("Ym/d", time());
//        $upload_path = public_path() . '/' . $folder_name;
//
        //定义文件名
//        $file_name = $file_prefix . "_" . time() . "_" . "." . $extension;

        //将图片移动到目标储存位置
//        $file->move($upload_path, $file_name);

        $path = $file->store('images/' . $userId, 'public');
        $url  = Storage::url($path);
        if (strpos($url, 'http') !== 0) {
            $url = config('app.url') . $url;
        }

        //返回图片已经存储的路径
//        return ['path' => config('app.url') . "$folder_name/$file_name"];
        return ['path' => $url];
    }
}
