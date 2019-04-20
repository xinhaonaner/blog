<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestUploadRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(Request $request) {
        $data  = file_get_contents("php://input");
        info('请求参数:' . $data);
//        abort(503, 'Unauthorized action.');
        return 'success';
    }

    public function upload(TestUploadRequest $request) {
        header("Access-Control-Allow-Origin: *");
        $dir = 'public/'.date('Y-m-d');
        $path = $request->file('file')->store($dir);

        return json_encode(['url'=>url($path),'code'=>200]);
    }
}
