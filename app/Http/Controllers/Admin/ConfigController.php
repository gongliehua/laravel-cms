<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    // 配置列表
    public function configList(Request $request)
    {
        if ($request->isMethod('post')) {
            $sort = $request->input('sort');
            foreach ($sort as $key=>$value) {
                Config::where(['id'=>intval($key)])->update(['sort'=>intval($value)]);
            }
            return back()->withErrors(['success'=>'排序成功！'])->withInput();
        }

        $configs = Config::orderBy('sort','asc')->paginate();
        return view('admin.config.index',['configs'=>$configs]);
    }

    // 配置添加
    public function configAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title'=>'required',
                'name'=>'required|alpha_dash|unique:configs',
                'type'=>'required|integer',
                'sort'=>'required|integer',
            ], [
                'title.required'=>'标题不能为空',
                'name.required'=>'名称不能为空',
                'name.alpha_dash'=>'名称仅包含字母数字破折号和下划线',
                'name.unique'=>'该名称已存在',
                'type.required'=>'请选择类型',
                'type.integer'=>'类型为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $config = new Config();
            $config->title = $request->input('title');
            $config->name = $request->input('name');
            $config->type = $request->input('type');
            $config->value = $request->input('value');
            $config->values = $request->input('values');
            $config->sort = $request->input('sort');
            if ($config->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }
        return view('admin.config.add');
    }

    // 配置查看
    public function configInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:configs,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该配置不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $config = Config::find($request->input('id'));
        return view('admin.config.info',['config'=>$config]);
    }

    // 配置编辑
    public function configEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:configs,id',
                'title'=>'required',
                'name'=>'required|alpha_dash|unique:configs,name,'.$request->input('id').',id',
                'type'=>'required|integer',
                'sort'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'title.required'=>'标题不能为空',
                'name.required'=>'名称不能为空',
                'name.alpha_dash'=>'名称仅包含字母数字破折号和下划线',
                'name.unique'=>'该名称已存在',
                'type.required'=>'请选择类型',
                'type.integer'=>'类型为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $config = Config::find($request->input('id'));
            $config->title = $request->input('title');
            $config->name = $request->input('name');
            $config->type = $request->input('type');
            $config->value = $request->input('value');
            $config->values = $request->input('values');
            $config->sort = $request->input('sort');
            if ($config->save()) {
                return back()->withErrors(['success'=>'修改成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'修改失败！'])->withInput();
            }
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:configs,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该配置不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $config = Config::find($request->input('id'));
        return view('admin.config.edit',['config'=>$config]);
    }

    // 配置删除
    public function configDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:configs,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $config = Config::destroy($request->input('id'));
        if ($config) {
            return back()->withErrors(['success'=>'删除成功！'])->withInput();
        } else {
            return back()->withErrors(['error'=>'删除失败！'])->withInput();
        }
    }

    // 配置管理
    public function configSave(Request $request)
    {
        if ($request->isMethod('post')) {
            $name = $request->input('name');
            foreach ($name as $key=>$value) {
                $value = is_array($value) ? implode(',',$value) : $value;
                Config::where(['name'=>$key])->update(['values'=>$value]);
            }
            return back()->withErrors(['success'=>'修改成功！'])->withInput();
        }
        $configs = Config::orderBy('sort','asc')->get();
        return view('admin.config.save',['configs'=>$configs]);
    }
}
