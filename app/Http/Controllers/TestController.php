<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // $data  = file_get_contents("php://input");
        // info('请求参数1:' . $data);

        $data = $request->input();
        info('请求参数2:' . json_encode($data));
       // abort(503, 'Unauthorized action.');
        return 'success';

    }

    // 测试mysql数据写入
    public function mysql()
    {
        set_time_limit(0);
        $t1 = microtime(true);

        $number = 5000;
        $len = 6;
        $cdkey_id = 1;
        $item = 'item';
        $equip = 'equip';
        $sub = 'sub';
        $close_time = time();
        $type = 1;
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $table = DB::table('cdkey_list');

        for ($i = 1; $i < $number; $i++) {
            $code = str_shuffle($str);
            $code = substr($code, 0, $len);
            $num = strval($i);
            $num_len = strlen($num);
            $p = -1;
            for ($j = 0; $j < $num_len; $j++) {
                $p = rand($p + 1, ($len - 1) - ($num_len - $j - 1));
                $code[$p] = $num[$j];
            }
            // $keyCode[] = array('CDKEY'=>$code);
            $data = [
                'CDKEY' => $code,
                'cdkey_id' => $cdkey_id,
                'equip' => $equip,
                'item' => $item,
                'sub' => $sub,
                'type' => $type,
                'close_time' => $close_time,
            ];
            if (!($i % 10)) {
                usleep(500);
            }
            $table->insert($data);
        }

        $t2 = microtime(true);
        info('执行成功，总耗时' . round($t2 - $t1, 3) . '秒');
        echo '执行成功，总耗时' . round($t2 - $t1, 3) . '秒';
        exit;
    }
}
