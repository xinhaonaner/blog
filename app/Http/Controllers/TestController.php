<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(Request $request) {
        $data = $request->input();
        info('请求方式：' . $request->method());
        info('请求参数:' . json_encode($data));
        return 'success';
    }
}
