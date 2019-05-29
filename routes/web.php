<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['web']], function () {
    Route::any('login', 'IndexController@login');
    Route::any('logout', 'IndexController@logout');
});
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['web','webAdmin']], function () {
    Route::any('index', 'IndexController@index');
    Route::any('profile', 'IndexController@profile');

    Route::any('adminList', 'AdminController@adminList');
    Route::any('adminAdd', 'AdminController@adminAdd');
    Route::any('adminInfo', 'AdminController@adminInfo');
    Route::any('adminEdit', 'AdminController@adminEdit');
    Route::any('adminDel', 'AdminController@adminDel');

    Route::any('roleList', 'RoleController@roleList');
    Route::any('roleAdd', 'RoleController@roleAdd');
    Route::any('roleInfo', 'RoleController@roleInfo');
    Route::any('roleEdit', 'RoleController@roleEdit');
    Route::any('roleDel', 'RoleController@roleDel');

    Route::any('permissionList', 'PermissionController@permissionList');
    Route::any('permissionAdd', 'PermissionController@permissionAdd');
    Route::any('permissionInfo', 'PermissionController@permissionInfo');
    Route::any('permissionEdit', 'PermissionController@permissionEdit');
    Route::any('permissionDel', 'PermissionController@permissionDel');

    Route::any('configList', 'ConfigController@configList');
    Route::any('configAdd', 'ConfigController@configAdd');
    Route::any('configInfo', 'ConfigController@configInfo');
    Route::any('configEdit', 'ConfigController@configEdit');
    Route::any('configDel', 'ConfigController@configDel');
    Route::any('configSave', 'ConfigController@configSave');

    Route::any('categoryList', 'CategoryController@categoryList');
    Route::any('categoryAdd', 'CategoryController@categoryAdd');
    Route::any('categoryInfo', 'CategoryController@categoryInfo');
    Route::any('categoryEdit', 'CategoryController@categoryEdit');
    Route::any('categoryDel', 'CategoryController@categoryDel');

    Route::any('articleList', 'ArticleController@articleList');
    Route::any('articleAdd', 'ArticleController@articleAdd');
    Route::any('articleInfo', 'ArticleController@articleInfo');
    Route::any('articleEdit', 'ArticleController@articleEdit');
    Route::any('articleDel', 'ArticleController@articleDel');
});
