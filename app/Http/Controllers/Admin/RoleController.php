<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminRole;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // 角色列表
    public function roleList(Request $request)
    {
        $roles = Role::paginate();
        return view('admin.role.index',['roles'=>$roles]);
    }

    // 角色添加
    public function roleAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'status'=>'required|integer',
            ], [
                'name.required'=>'名称不能为空',
                'status.required'=>'请选择状态',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            try {
                DB::beginTransaction();

                $role = new Role();
                $role->name = $request->input('name');
                $role->status = $request->input('status');
                if (!$role->save()) throw new \Exception('添加失败！');

                if ($request->input('permission_id')) {
                    foreach ($request->input('permission_id') as $key=>$value) {
                        $rolePermission = new RolePermission();
                        $rolePermission->role_id = $role->id;
                        $rolePermission->permission_id = $value;
                        if (!$rolePermission->save()) throw new \Exception('添加失败！');
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
            return back()->withErrors(['success'=>'添加成功！'])->withInput();
        }
        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());
        return view('admin.role.add',['permissions'=>$permissions]);
    }

    // 角色信息
    public function roleInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:roles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $role = Role::with(['permission'])->find($request->input('id'));
        $permission_id = [];
        foreach ($role->permission as $key=>$value) {
            $permission_id[] = $value['id'];
        }

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());

        return view('admin.role.info',['role'=>$role,'permission_id'=>$permission_id,'permissions'=>$permissions]);
    }

    // 角色编辑
    public function roleEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:roles,id',
                'name'=>'required',
                'status'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该角色不存在',
                'name.required'=>'名称不能为空',
                'status.required'=>'请选择状态',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            try {
                DB::beginTransaction();
                $role = Role::find($request->input('id'));
                $role->name = $request->input('name');
                $role->status = $request->input('status');
                if (!$role->save()) throw new \Exception('修改失败！');

                $rolePermission = RolePermission::where(['role_id'=>$request->input('id')])->delete();

                if ($request->input('permission_id')) {
                    foreach ($request->input('permission_id') as $key=>$value) {
                        $rolePermission = new RolePermission();
                        $rolePermission->role_id = $request->input('id');
                        $rolePermission->permission_id = $value;
                        if (!$rolePermission->save()) throw new \Exception('修改失败！');
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
            return back()->withErrors(['success'=>'修改成功！'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:roles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该角色不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $role = Role::with(['permission'])->find($request->input('id'));
        $permission_id = [];
        foreach ($role->permission as $key=>$value) {
            $permission_id[] = $value['id'];
        }

        $permissions = $this->sorting(Permission::orderBy('sort','asc')->get());

        return view('admin.role.edit',['role'=>$role,'permission_id'=>$permission_id,'permissions'=>$permissions]);
    }

    // 角色删除
    public function roleDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:roles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该角色不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        try {
            DB::beginTransaction();

            $adminRole = AdminRole::where('role_id',$request->input('id'))->first();
            if ($adminRole) throw new \Exception('该角色有管理员使用,不能删除');

            $role = Role::destroy($request->input('id'));
            if (!$role) throw new \Exception('删除失败！');

            $rolePermission = RolePermission::where(['role_id'=>$request->input('id')])->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
        return back()->withErrors(['success'=>'删除成功！'])->withInput();
    }
}
