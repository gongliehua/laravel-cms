<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    // 权限列表
    public function permissionList(Request $request)
    {
        if ($request->isMethod('post')) {
            $sort = $request->input('sort');
            foreach ($sort as $key=>$value) {
                Permission::where(['id'=>intval($key)])->update(['sort'=>intval($value)]);
            }
            return back()->withErrors(['success'=>'排序成功！'])->withInput();
        }

        $page = $request->input('page',1);
        $pageSize = $request->input('pageSize',15);
        $offset = ($page-1)*$pageSize;

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());
        $data = new LengthAwarePaginator(array_slice($permissions,$offset,$pageSize),count($permissions),$pageSize,$page,['path'=>$request->url(),'query'=>$request->query()]);

        return view('admin.permission.index',['permissions'=>$data]);
    }

    // 权限添加
    public function permissionAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title'=>'required',
                'is_menu'=>'required|integer',
                'sort'=>'required|integer',
                'parent_id'=>'required|integer',
            ], [
                'title.required'=>'标题不能为空',
                'is_menu.required'=>'请选择菜单栏',
                'is_menu.integer'=>'菜单栏为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $permission = new Permission();
            $permission->title = $request->input('title');
            $permission->uri = $request->input('uri');
            $permission->icon = $request->input('icon');
            $permission->is_menu = $request->input('is_menu');
            $permission->sort = $request->input('sort');
            $permission->parent_id = $request->input('parent_id');
            if ($permission->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());
        return view('admin.permission.add',['permissions'=>$permissions]);
    }

    // 权限查看
    public function permissionInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:permissions,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该权限不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $permission = Permission::find($request->input('id'));

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());

        return view('admin.permission.info',['permission'=>$permission,'permissions'=>$permissions]);
    }

    // 权限编辑
    public function permissionEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:permissions,id',
                'title'=>'required',
                'is_menu'=>'required|integer',
                'sort'=>'required|integer',
                'parent_id'=>'required|integer|not_in:'.$request->input('id'),
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该权限不存在',
                'title.required'=>'标题不能为空',
                'is_menu.required'=>'请选择菜单',
                'is_menu.integer'=>'菜单为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
                'parent_id.not_in'=>'上级权限不能是自己',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $data = [
                'title'=>$request->input('title'),
                'uri'=>$request->input('uri'),
                'icon'=>$request->input('icon'),
                'is_menu'=>$request->input('is_menu'),
                'sort'=>$request->input('sort'),
                'parent_id'=>$request->input('parent_id'),
            ];

            $permission = Permission::where(['id'=>$request->input('id')])->update($data);
            if ($permission) {
                return back()->withErrors(['success'=>'修改成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'修改失败！'])->withInput();
            }
        }
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:permissions,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该权限不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $permission = Permission::find($request->input('id'));

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());

        return view('admin.permission.edit',['permission'=>$permission,'permissions'=>$permissions]);
    }

    // 权限删除
    public function permissionDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:permissions,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该权限不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        try {
            DB::beginTransaction();

            $permission = Permission::where('parent_id',$request->input('id'))->first();
            if ($permission) throw new \Exception('有其它权限在使用该权限作为上级权限,不能删除');

            $rolePermission = RolePermission::where('permission_id',$request->input('id'))->first();
            if ($rolePermission) throw new \Exception('有角色在使用该权限,不能删除');

            $delPermission = Permission::destroy($request->input('id'));
            if (!$delPermission) throw new \Exception('删除失败！');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
        return back()->withErrors(['success'=>'删除成功！'])->withInput();
    }
}
