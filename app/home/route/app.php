<?php
namespace app\home\Route;
use think\facade\Route;

Route::post('login', 'Sign/login')->allowCrossDomain();
Route::any('register', 'Sign/register')->allowCrossDomain();
Route::group(function(){
    Route::any('test', 'Sign/test');
})->middleware(\app\home\middleware\CheckToken::class)->allowCrossDomain();
Route::any('logout', 'Sign/logout')->allowCrossDomain();