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




Route::get('index', 'index/index/index');
Route::get('info', 'index/info/info');
Route::get('xlist', 'index/index/xlist');
Route::get('about', 'index/other/about');
Route::get('tag', 'index/index/tag'); //根据标签获取文章
Route::post('search', 'index/index/search'); //搜索文章
Route::get('swoole', 'index/swoole/index'); //swoole测试