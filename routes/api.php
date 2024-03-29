<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('test', 'TestController@index');
Route::any('test/receive', 'TestController@recevie');
Route::any('test/headers', 'TestController@headers');
Route::post('test/upload', 'TestController@upload');
Route::post('upload/uploadFile', 'UploadController@uploadFile');
Route::post('test/mysql', 'TestController@mysql');