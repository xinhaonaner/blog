<?php
// +----------------------------------------------------------------------
// | UploadController.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2019/5/23 17:07
// +----------------------------------------------------------------------
// | Author: Felix <Fzhengpei@gmail.com>
// +----------------------------------------------------------------------

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use zgldh\QiniuStorage\QiniuStorage;

class UploadController extends Controller
{
    public function uploadFile(Request $request)

    {

        // 判断是否有文件上传

        if ($request->hasFile('file')) {

            // 获取文件,file对应的是前端表单上传input的name

            $file = $request->file('file');

            // Laravel5.3中多了一个写法

            // $file = $request->file;

            // 初始化

            $disk = QiniuStorage::disk('qiniu');

            // 重命名文件

            $fileName = md5($file->getClientOriginalName() . time() . rand()) . '.' . $file->getClientOriginalExtension();

            // 上传到七牛

            $bool = $disk->put('xinhaonaner_cn/image_' . $fileName, file_get_contents($file->getRealPath()));

            // 判断是否上传成功

            if ($bool) {

                $path = $disk->downloadUrl('xinhaonaner_cn/image_' . $fileName);

                dump($path);
                dd('上传成功');

            }

            return '上传失败';

        }

        return '没有文件';

    }
}