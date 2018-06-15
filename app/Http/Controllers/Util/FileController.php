<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Models\Util\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;

class FileController extends Controller
{

    public function show($id)
    {
        if (Storage::disk('admin')->exists('files/'.$id)) {
            return response()->download(storage_path('data/files/'.$id));
        } else {
            return 'not exist';
        }
    }
}
