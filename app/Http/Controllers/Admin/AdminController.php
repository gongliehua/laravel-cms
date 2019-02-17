<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Validator;

class AdminController extends BackendController
{
    //用户列表
    public function index(Request $request)
    {
        $admins = Admin::with(['group'])->paginate();
        return view('admin.admin.index',['admins'=>$admins]);
    }

    //添加用户
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username'=>'required|unique:admins',
                'nickname'=>'required',
                'password'=>'required',
                'group_id'=>'required|exists:groups,id',
            ], [
                'username.required'=>'用户名不能为空',
                'username.unique'=>'该用户名已存在',
                'nickname.required'=>'昵称不能为空',
                'password.required'=>'密码不能为空',
                'group_id.required'=>'用户组不能为空',
                'group_id.exists'=>'用户组不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            if ($file = $request->file('avatar')) {
                $result = $this->fileUpload($file,'uploads/avatar',['php']);
                if ($result['code'] !== 200) return back()->withErrors(['avatar'=>$result['msg']])->withInput();
            }
            $admin = new Admin();
            $admin->username = $request->input('username');
            $admin->nickname = $request->input('nickname');
            $admin->password = sha1($request->input('password'));
            $admin->group_id = $request->input('group_id');
            $admin->avatar = empty($result['data']) ? '' : $result['data'];
            if ($admin->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                if (!empty($result['data'])) @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }
        $groups = Group::all();
        return view('admin.admin.add',['groups'=>$groups]);
    }

    //查看用户
    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $admin = Admin::with(['group'])->find($request->input('id'));
        $groups = Group::all();
        return view('admin.admin.info',['admin'=>$admin,'groups'=>$groups]);
    }

    //编辑用户
    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:admins,id',
                'username'=>'required|unique:admins,username,'.$request->input('id').',id',
                'nickname'=>'required',
                'group_id'=>'required|exists:groups,id',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该用户不存在',
                'username.required'=>'用户名不能为空',
                'username.unique'=>'该用户名已存在',
                'nickname.required'=>'昵称不能为空',
                'group_id.required'=>'用户组不能为空',
                'group_id.exists'=>'用户组不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $data = array_only($request->all(),['username','nickname','group_id']);
            if ($request->input('password')) {
                $data['password'] = sha1($request->input('password'));
            }
            if ($file = $request->file('avatar')) {
                $result = $this->fileUpload($file,'uploads/avatar',['php']);
                if ($result['code'] !== 200) return back()->withErrors(['avatar'=>$result['msg']])->withInput();
                $data['avatar'] = $result['data'];

                $origin = Admin::find($request->input('id'));
                $admin = Admin::where(['id'=>$request->input('id')])->update($data);
                if ($admin) {
                    if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
                    return back()->withErrors(['success'=>'修改成功！'])->withInput();
                } else {
                    @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                    return back()->withErrors(['error'=>'修改失败！'])->withInput();
                }
            } else {
                $admin = Admin::where(['id'=>$request->input('id')])->update($data);
                if ($admin) {
                    return back()->withErrors(['success'=>'修改成功！'])->withInput();
                } else {
                    return back()->withErrors(['error'=>'修改失败！'])->withInput();
                }
            }
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $admin = Admin::with(['group'])->find($request->input('id'));
        $groups = Group::all();
        return view('admin.admin.edit',['admin'=>$admin,'groups'=>$groups]);
    }

    //删除用户
    public function del(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:admins,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $origin = Admin::find($request->input('id'));

        $admin = Admin::destroy($request->input('id'));
        if ($admin) {
            if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
            return back()->withErrors(['success'=>'删除成功！'])->withInput();
        } else {
            return back()->withErrors(['error'=>'删除失败！'])->withInput();
        }
    }
}
