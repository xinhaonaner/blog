<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $path = Storage::url(trim($path,'public/'));

        return json_encode(['url'=>url($path),'code'=>200]);
    }

    // 测试接受
    public function recevie(Request $request) {
        $data = $request->input();
        info('请求参数:' . json_encode($data));
        return 'success';

    }
}
