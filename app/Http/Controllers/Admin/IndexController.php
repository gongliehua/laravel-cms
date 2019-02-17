<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Group;
use App\Models\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Validator;

class IndexController extends BackendController
{
    //后台首页
    public function index(Request $request)
    {
        //默认用户不限制
        if ($request->session()->get('admin.id') === 1) {
            $admin = Admin::find($request->session()->get('admin.id'));
            $menu = $this->_menu($this->_sorting(Rule::where(['ismenu'=>1])->orderBy('sort','asc')->get()));
        } else {
            $admin = Admin::with(['group'=>function ($query) {
                $query->with(['rule'=>function ($query) {
                    $query->where(['ismenu'=>1])->orderBy('sort','asc');
                }]);
            }])->where(['id'=>$request->session()->get('admin.id')])->first();
            $menu = $this->_menu($this->_sorting($admin->group->rule));
        }

        return view('admin.index', ['admin'=>$admin,'menu'=>$menu]);
    }

    //用户信息
    public function profile(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username'=>'required|unique:admins,username,'.$request->session()->get('admin.id').',id',
                'nickname'=>'required',
                'group_id'=>'required|exists:groups,id',
            ], [
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

                $origin = Admin::find($request->session()->get('admin.id'));
                $admin = Admin::where(['id'=>$request->session()->get('admin.id')])->update($data);
                if ($admin) {
                    if (!empty($origin->avatar)) @unlink($_SERVER['DOCUMENT_ROOT'] . '/' .$origin->avatar);
                    return back()->withErrors(['success'=>'修改成功！'])->withInput();
                } else {
                    @unlink($_SERVER['DOCUMENT_ROOT']. '/'. $result['data']);
                    return back()->withErrors(['error'=>'修改失败！'])->withInput();
                }
            } else {
                $admin = Admin::where(['id'=>$request->session()->get('admin.id')])->update($data);
                if ($admin) {
                    return back()->withErrors(['success'=>'修改成功！'])->withInput();
                } else {
                    return back()->withErrors(['error'=>'修改失败！'])->withInput();
                }
            }
        }
        $admin = Admin::find($request->session()->get('admin.id'));
        if ($admin) {
            $group = Group::all();
            return view('admin.profile', ['admin'=>$admin,'group'=>$group]);
        } else {
            return $this->error('抱歉！你要的数据未找到！');
        }
    }

    //后台登录
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
                $request->session()->put(['admin.id'=>$admin->id,'admin.time'=>time()]);
                return redirect('admin/index');
            } else {
                return back()->withErrors(['用户名或密码错误'])->withInput();
            }
        }
        return view('admin.login');
    }

    //后台退出
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('admin/login');
    }

    //错误页面
    public function error($msg = '拒绝访问！权限不足！', $time = 3000, $url = '')
    {
        return view('admin.error', ['msg'=>$msg,'time'=>$time,'url'=>$url]);
    }
}
