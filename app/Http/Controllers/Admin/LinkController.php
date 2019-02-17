<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\DB;

class LinkController extends BackendController
{
    //链接列表
    public function index(Request $request)
    {
        return 1;
    }

    //添加链接
    public function add(Request $request)
    {
        return 1;
    }

    //查看链接
    public function info(Request $request)
    {
        return 1;
    }

    //编辑链接
    public function edit(Request $request)
    {
        return 1;
    }

    //删除链接
    public function del(Request $request)
    {
        $rules = [
            ['name'=>'#','title'=>'文章管理','icon'=>'fa-file-text','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/article','title'=>'文章列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>1],
            ['name'=>'admin/infoArticle','title'=>'查看文章','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>1],
            ['name'=>'admin/addArticle','title'=>'添加文章','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>1],
            ['name'=>'admin/editArticle','title'=>'编辑文章','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>1],
            ['name'=>'admin/delArticle','title'=>'删除文章','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>1],

            ['name'=>'#','title'=>'栏目管理','icon'=>'fa-align-justify','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/category','title'=>'栏目列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>7],
            ['name'=>'admin/infoCategory','title'=>'查看栏目','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>7],
            ['name'=>'admin/addCategory','title'=>'添加栏目','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>7],
            ['name'=>'admin/editCategory','title'=>'编辑栏目','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>7],
            ['name'=>'admin/delCategory','title'=>'删除栏目','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>7],

            ['name'=>'#','title'=>'链接管理','icon'=>'fa-link','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/link','title'=>'链接列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>13],
            ['name'=>'admin/infoLink','title'=>'查看链接','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>13],
            ['name'=>'admin/addLink','title'=>'添加链接','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>13],
            ['name'=>'admin/editLink','title'=>'编辑链接','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>13],
            ['name'=>'admin/delLink','title'=>'删除链接','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>13],

            ['name'=>'#','title'=>'邮件管理','icon'=>'fa-envelope','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/email','title'=>'邮件列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>19],
            ['name'=>'admin/infoEmail','title'=>'查看邮件','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>19],
            ['name'=>'admin/addEmail','title'=>'添加邮件','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>19],
            ['name'=>'admin/editEmail','title'=>'编辑邮件','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>19],
            ['name'=>'admin/delEmail','title'=>'删除邮件','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>19],

            ['name'=>'#','title'=>'用户管理','icon'=>'fa-users','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/rule','title'=>'权限列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>25],
            ['name'=>'admin/infoRule','title'=>'查看权限','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/addRule','title'=>'添加权限','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/editRule','title'=>'编辑权限','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/delRule','title'=>'删除权限','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/group','title'=>'角色列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>25],
            ['name'=>'admin/infoGroup','title'=>'查看角色','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/addGroup','title'=>'添加角色','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/editGroup','title'=>'编辑角色','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/delGroup','title'=>'删除角色','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/admin','title'=>'用户列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>25],
            ['name'=>'admin/infoAdmin','title'=>'查看用户','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/addAdmin','title'=>'添加用户','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/editAdmin','title'=>'编辑用户','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],
            ['name'=>'admin/delAdmin','title'=>'删除用户','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>25],

            ['name'=>'#','title'=>'系统管理','icon'=>'fa-gears','ismenu'=>1,'parent_id'=>0],
            ['name'=>'admin/config','title'=>'配置列表','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>41],
            ['name'=>'admin/infoConfig','title'=>'查看配置','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>41],
            ['name'=>'admin/addConfig','title'=>'添加配置','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>41],
            ['name'=>'admin/editConfig','title'=>'编辑配置','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>41],
            ['name'=>'admin/delConfig','title'=>'删除配置','icon'=>'fa-circle-o','ismenu'=>0,'parent_id'=>41],
            ['name'=>'admin/setting','title'=>'配置管理','icon'=>'fa-circle-o','ismenu'=>1,'parent_id'=>41],
        ];
        DB::table('rules')->insert($rules);
    }
}
