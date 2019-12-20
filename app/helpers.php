<?php
/**
 * Created by PhpStorm.
 * User: Felix
 * Date: 2018/11/21 9:27 AM
 */

if ( !function_exists('getIcpNum')) {
    /**
     * @function 获取icp备案号
     * @return string
     */
    function getIcpNum()
    {
        $url = request()->url();
        return str_contains($url, 'xinhaonaner') ? '浙ICP备17026242号-2' : ' 浙ICP备17026242号-3';
    }
}