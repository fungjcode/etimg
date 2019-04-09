<?php
use think\Route;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//前端
Route::rule('/', 'index/index');
Route::rule('i', 'index/index/i');
Route::rule('l', 'index/index/l');
Route::rule('about', 'index/index/about');
Route::rule(['u', 'u/:id'], 'index/index/u');

//后台
Route::rule('sysadminlogin', 'index/sysadmin/adminlogin');
Route::rule('sysadmin', 'index/sysadmin');