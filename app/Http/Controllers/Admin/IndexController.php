<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{
    // 后台首页
    public function index(Request $request)
    {
        // 默认管理员不限制
        $id = Auth::guard('webAdmin')->id();
        if ($id == 1) {
            $admin = Admin::find($id);
            $menu = $this->sortString($this->sortArray(Permission::where(['is_menu'=>1])->orderBy('sort','asc')->get()));
        } else {
            $admin = Admin::with(['adminRole'=>function ($query) {
                $query->with(['role'=>function ($query) {
                    $query->with(['permission'=>function ($query) {
                        $query->where('is_menu',1)->orderBy('sort','asc');
                    }]);
                }]);
            }])->where(['id'=>$id])->first();
            $menu = json_decode(json_encode($admin), true); // 转换数组
            $menu = $this->sortString($this->sortArray($menu['admin_role']['role']['permission']));
        }

        return view('admin.index', ['admin'=>$admin,'menu'=>$menu]);
    }

    // 用户信息
    public function profile(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'role_id'=>'required|exists:roles,id',
            ], [
                'name.required'=>'昵称不能为空',
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

                    $origin = Admin::find(Auth::guard('webAdmin')->id());
                    $admin = Admin::where(['id'=>Auth::guard('webAdmin')->id()])->update($data);
                    if ($admin) {
                        if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
                    } else {
                        @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                        throw new \Exception('修改失败！');
                    }
                } else {
                    $admin = Admin::where(['id'=>Auth::guard('webAdmin')->id()])->update($data);
                    if (!$admin) throw new \Exception('修改失败！');
                }
                $role = AdminRole::where('admin_id',Auth::guard('webAdmin')->id())->first();
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
        $admin = Admin::with(['adminRole'=>function ($query) {
            $query->with(['role']);
        }])->find(Auth::guard('webAdmin')->id());
        $roles = Role::all();
        return view('admin.profile', ['admin'=>$admin,'roles'=>$roles]);
    }

    // 后台登录
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username'=>'required',
                'password'=>'required',
            ], [
                'username.required'=>'用户名不能为空',
                'password.required'=>'密码不能为空',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $admin = Admin::where(['username'=>$request->input('username'),'password'=>sha1($request->input('password'))])->first();
            if ($admin) {
                Auth::guard('webAdmin')->login($admin, $request->has('remember'));
                return redirect('admin/index');
            } else {
                return back()->withErrors(['username'=>'用户名或密码错误'])->withInput();
            }
        }
        return view('admin.login');
    }

    // 后台退出
    public function logout(Request $request)
    {
        Auth::guard('webAdmin')->logout();
        return redirect('admin/login');
    }
}
