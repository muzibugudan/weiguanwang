<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

return [

];

// 登录页面
Route::rule('/admin/login', 'admin/Login/login');
// 验证码
Route::rule('/admin/Login/code', 'admin/Login/code');

Route::rule('/admin/Login/dologin', 'admin/Login/dologin');

// 主页
Route::rule('/admin/index', 'admin/Index/index');
// 欢迎页
Route::rule('/admin/welcome', 'admin/Index/welcome');
// 微官网类表页
Route::rule('/admin/Guanwang/index', 'admin/Guanwang/index');
// 选中删除
Route::rule('/admin/Article/delAll','admin/Article/delAll');
