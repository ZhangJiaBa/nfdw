<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Models\Util\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;

class ImagesController extends Controller
{
    public function store(Request $request)
    {
        if(!$request->hasFile('images')){
            return '上传文件为空！';
        }

        $file = new File();
        $filePath = storage_path('data/images');
        if(!$res = $file->save($request->file('images'), $filePath)){
            return response(['error' => 1], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $res;
    }

    public function show($id)
    {
        if (Storage::disk('admin')->exists('images/'.$id)) {
            return response()->download(storage_path('data/images/'.$id));
        } else {
            return 'not exist';
        }
    }

    public function qrcod($id)
    {
        return response()->download(storage_path('data/qrcodes/'.$id));
    }
}
