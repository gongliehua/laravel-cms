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
Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['web','admin.login']], function () {
    Route::any('index', 'IndexController@index');
    Route::any('profile', 'IndexController@profile');
    Route::any('error', 'IndexController@error');

    Route::any('article', 'ArticleController@index');
    Route::any('infoArticle', 'ArticleController@info');
    Route::any('addArticle', 'ArticleController@add');
    Route::any('editArticle', 'ArticleController@edit');
    Route::any('delArticle', 'ArticleController@del');

    Route::any('category', 'CategoryController@index');
    Route::any('infoCategory', 'CategoryController@info');
    Route::any('addCategory', 'CategoryController@add');
    Route::any('editCategory', 'CategoryController@edit');
    Route::any('delCategory', 'CategoryController@del');

    Route::any('link', 'LinkController@index');
    Route::any('infoLink', 'LinkController@info');
    Route::any('addLink', 'LinkController@add');
    Route::any('editLink', 'LinkController@edit');
    Route::any('delLink', 'LinkController@del');

    Route::any('email', 'EmailController@index');
    Route::any('infoEmail', 'EmailController@info');
    Route::any('addEmail', 'EmailController@add');
    Route::any('editEmail', 'EmailController@edit');
    Route::any('delEmail', 'EmailController@del');

    Route::any('rule', 'RuleController@index');
    Route::any('infoRule', 'RuleController@info');
    Route::any('addRule', 'RuleController@add');
    Route::any('editRule', 'RuleController@edit');
    Route::any('delRule', 'RuleController@del');

    Route::any('group', 'GroupController@index');
    Route::any('infoGroup', 'GroupController@info');
    Route::any('addGroup', 'GroupController@add');
    Route::any('editGroup', 'GroupController@edit');
    Route::any('delGroup', 'GroupController@del');

    Route::any('admin', 'AdminController@index');
    Route::any('infoAdmin', 'AdminController@info');
    Route::any('addAdmin', 'AdminController@add');
    Route::any('editAdmin', 'AdminController@edit');
    Route::any('delAdmin', 'AdminController@del');

    Route::any('config', 'ConfigController@index');
    Route::any('infoConfig', 'ConfigController@info');
    Route::any('addConfig', 'ConfigController@add');
    Route::any('editConfig', 'ConfigController@edit');
    Route::any('delConfig', 'ConfigController@del');

    Route::any('setting', 'ConfigController@setting');
});
