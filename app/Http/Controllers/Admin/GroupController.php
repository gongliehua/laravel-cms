<?php

namespace App\Http\Controllers\Admin;

use App\Models\GroupRule;
use App\Models\Rule;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GroupController extends BackendController
{
    //角色列表
    public function index(Request $request)
    {
        $groups = Group::paginate();
        return view('admin.group.index',['groups'=>$groups]);
    }

    //添加角色
    public function add(Request $request)
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

            DB::beginTransaction();
            try {
                $group = new Group();
                $group->name = $request->input('name');
                $group->status = $request->input('status');
                if (!$group->save()) throw new \Exception('添加失败！');

                if ($request->input('rules')) {
                    foreach ($request->input('rules') as $key=>$value) {
                        $group_rules = new GroupRule();
                        $group_rules->group_id = $group->id;
                        $group_rules->rule_id = $value;
                        if (!$group_rules->save()) throw new \Exception('添加失败！');
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error'=>$e->getMessage()])->withInput();
            }
            return back()->withErrors(['success'=>'添加成功！'])->withInput();
        }
        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());
        return view('admin.group.add',['rules'=>$rules]);
    }

    //查看角色
    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:groups,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $group = Group::with(['rule'])->find($request->input('id'));
        $rule = [];
        foreach ($group->rule as $key=>$value) {
            $rule[] = $value['id'];
        }

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());

        return view('admin.group.info',['group'=>$group,'rule'=>$rule,'rules'=>$rules]);
    }

    //编辑角色
    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:groups,id',
                'name'=>'required',
                'status'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'name.required'=>'名称不能为空',
                'status.required'=>'请选择状态',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            DB::beginTransaction();
            try {
                $group = Group::find($request->input('id'));
                $group->name = $request->input('name');
                $group->status = $request->input('status');
                if (!$group->save()) throw new \Exception('修改失败！');

                //没数据会false
                $group_rules = GroupRule::where(['group_id'=>$request->input('id')])->delete();
                //if (!$group_rules) throw new \Exception('修改失败！');

                if ($request->input('rules')) {
                    foreach ($request->input('rules') as $key=>$value) {
                        $group_rules = new GroupRule();
                        $group_rules->group_id = $request->input('id');
                        $group_rules->rule_id = $value;
                        if (!$group_rules->save()) throw new \Exception('修改失败！');
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
            'id'=>'required|exists:groups,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $group = Group::with(['rule'])->find($request->input('id'));
        $rule = [];
        foreach ($group->rule as $key=>$value) {
            $rule[] = $value['id'];
        }

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());

        return view('admin.group.edit',['group'=>$group,'rule'=>$rule,'rules'=>$rules]);
    }

    //删除角色
    public function del(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|groups:rules,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        return 1;
        DB::beginTransaction();
        try {
            $group = Group::destroy($request->input('id'));
            if (!$group) throw new \Exception('删除失败！');

            //没数据会false
            $group_rules = GroupRule::where(['group_id'=>$request->input('id')])->delete();
            //if (!$group_rules) throw new \Exception('删除败！');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
        return back()->withErrors(['success'=>'删除成功！'])->withInput();
    }
}
