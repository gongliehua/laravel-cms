<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Article;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // 管用户列表
    public function adminList(Request $request)
    {
        $admins = Admin::with(['adminRole'=>function ($query) {
            $query->with(['role']);
        }])->paginate();
        // 由于对象方式获取不到链表的数据, 所以转数组
        $data = json_decode(json_encode($admins),true);
        return view('admin.admin.index',['admins'=>$admins, 'data'=>$data['data']]);
    }

    // 添加管理员
    public function adminAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username'=>'required|unique:admins',
                'name'=>'required',
                'password'=>'required|min:6',
                'role_id'=>'required|exists:roles,id',
            ], [
                'username.required'=>'用户名不能为空',
                'username.unique'=>'该用户名已存在',
                'name.required'=>'昵称不能为空',
                'password.required'=>'密码不能为空',
                'password.min'=>'密码长度至少6位',
                'group_id.required'=>'角色不能为空',
                'group_id.exists'=>'该角色不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            try {
                DB::beginTransaction();

                if ($file = $request->file('avatar')) {
                    $result = $this->fileUpload($file,'uploads/avatar',['php']);
                    if ($result['code'] !== 200) throw new \Exception($result['msg']);
                }
                $admin = new Admin();
                $admin->username = $request->input('username');
                $admin->name = $request->input('name');
                $admin->password = sha1($request->input('password'));
                $admin->avatar = empty($result['data']) ? '' : $result['data'];
                if (!$admin->save()) {
                    if (!empty($result['data'])) @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                    throw new \Exception('添加失败！');
                }
                $adminRole = new AdminRole();
                $adminRole->admin_id = $admin->id;
                $adminRole->role_id = $request->input('role_id');
                if (!$adminRole->save()) throw new \Exception('添加失败！');

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
            return back()->withErrors(['success'=>'添加成功！'])->withInput();
        }
        $roles = Role::all();
        return view('admin.admin.add',['roles'=>$roles]);
    }

    // 查看管理员
    public function adminInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $admin = Admin::with(['adminRole'=>function($query) {
            $query->with(['role']);
        }])->find($request->input('id'));
        $roles = Role::all();
        return view('admin.admin.info',['admin'=>$admin,'roles'=>$roles]);
    }

    // 管理员编辑
    public function adminEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:admins,id',
                'name'=>'required',
                'role_id'=>'required|exists:roles,id',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该用户不存在',
                'nickname.required'=>'昵称不能为空',
                'role_id.required'=>'角色不能为空',
                'role_id.exists'=>'该角色不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            try {
                DB::beginTransaction();

                $data = array_only($request->all(),['name']);
                if ($request->input('password') != '') {
                    if (strlen($request->input('password')) <6) throw new \Exception('密码长度至少6位');
                    $data['password'] = sha1($request->input('password'));
                }
                if ($file = $request->file('avatar')) {
                    $result = $this->fileUpload($file,'uploads/avatar',['php']);
                    if ($result['code'] != 200) throw new \Exception($result['msg']);
                    $data['avatar'] = $result['data'];

                    $origin = Admin::find($request->input('id'));
                    $admin = Admin::where(['id'=>$request->input('id')])->update($data);
                    if ($admin) {
                        if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
                    } else {
                        @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                        throw new \Exception('修改失败！');
                    }
                } else {
                    $admin = Admin::where(['id'=>$request->input('id')])->update($data);
                    if (!$admin) throw new \Exception('修改失败！');
                }
                $role = AdminRole::where('admin_id',$request->input('id'))->first();
                if ($role->role_id != $request->input('role_id')) {
                    $role->role_id = $request->input('role_id');
                    if (!$role->save()) throw new \Exception('修改失败！');
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
            return back()->withErrors(['success'=>'修改成功！'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该管理员不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $admin = Admin::with(['adminRole'=>function($query){
            $query->with(['role']);
        }])->find($request->input('id'));
        $roles = Role::all();
        return view('admin.admin.edit',['admin'=>$admin,'roles'=>$roles]);
    }

    // 管理员删除
    public function adminDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|not_in:1|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.not_in'=>'默认管理员不能删除',
            'id.exists'=>'该管理员不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        try {
            DB::beginTransaction();

            $article = Article::where('admin_id',$request->input('id'))->first();
            if ($article) throw new \Exception('有文章和该管理员关联,请先删除文章');

            $origin = Admin::find($request->input('id'));
            $admin = Admin::destroy($request->input('id'));
            if ($admin) {
                if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
                $adminRole = AdminRole::where('admin_id',$request->input('id'))->delete();
                if (!$adminRole) throw new \Exception('删除失败！');
            } else {
                throw new \Exception('删除失败！');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
        return back()->withErrors(['success'=>'删除成功！'])->withInput();
    }
}
