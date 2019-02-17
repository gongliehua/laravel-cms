<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class RuleController extends BackendController
{
    //权限列表
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $sort = $request->input('sort');
            foreach ($sort as $key=>$value) {
                Rule::where(['id'=>intval($key)])->update(['sort'=>intval($value)]);
            }
            return back()->withErrors(['success'=>'排序成功！'])->withInput();
        }

        $page = $request->input('page',1);
        $pageSize = $request->input('pageSize',15);
        $offset = ($page-1)*$pageSize;

        $rule = $this->_sort(Rule::orderBy('sort','asc')->get());
        $data = new LengthAwarePaginator(array_slice($rule,$offset,$pageSize),count($rule),$pageSize,$page,['path'=>$request->url(),'query'=>$request->query()]);

        return view('admin.rule.index',['rule'=>$data]);
    }

    //添加权限
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'title'=>'required',
                'icon'=>'required',
                'status'=>'required|integer',
                'ismenu'=>'required|integer',
                'sort'=>'required|integer',
                'parent_id'=>'required|integer',
            ], [
                'name.required'=>'名称不能为空',
                'title.required'=>'标题不能为空',
                'icon.required'=>'图标不能为空',
                'status.required'=>'请选择状态',
                'status.integer'=>'状态为整数',
                'ismenu.required'=>'请选择菜单栏',
                'ismenu.integer'=>'菜单栏为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $rule = new Rule();
            $rule->name = $request->input('name');
            $rule->title = $request->input('title');
            $rule->icon = $request->input('icon');
            $rule->status = $request->input('status');
            $rule->ismenu = $request->input('ismenu');
            $rule->sort = $request->input('sort');
            $rule->parent_id = $request->input('parent_id');
            if ($rule->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());
        return view('admin.rule.add',['rules'=>$rules]);
    }

    //查看权限
    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:rules,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $rule = Rule::find($request->input('id'));

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());

        return view('admin.rule.info',['rule'=>$rule,'rules'=>$rules]);
    }

    //编辑权限
    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:rules,id',
                'name'=>'required',
                'title'=>'required',
                'icon'=>'required',
                'status'=>'required|integer',
                'ismenu'=>'required|integer',
                'sort'=>'required|integer',
                'parent_id'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'name.required'=>'名称不能为空',
                'title.required'=>'标题不能为空',
                'icon.required'=>'图标不能为空',
                'status.required'=>'请选择状态',
                'status.integer'=>'状态为整数',
                'ismenu.required'=>'请选择菜单栏',
                'ismenu.integer'=>'菜单栏为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $data = [
                'name'=>$request->input('name'),
                'title'=>$request->input('title'),
                'icon'=>$request->input('icon'),
                'status'=>$request->input('status'),
                'ismenu'=>$request->input('ismenu'),
                'sort'=>$request->input('sort'),
                'parent_id'=>$request->input('parent_id'),
            ];

            $rule = Rule::where(['id'=>$request->input('id')])->update($data);
            if ($rule) {
                return back()->withErrors(['success'=>'修改成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'修改失败！'])->withInput();
            }
        }
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:rules,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $rule = Rule::find($request->input('id'));

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get());

        return view('admin.rule.edit',['rule'=>$rule,'rules'=>$rules]);
    }

    //删除权限
    public function del(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:rules,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $rules = $this->_sort(Rule::orderBy('sort','asc')->get(),$request->input('id'));
        $rule = [];
        foreach ($rules as $key=>$value) {
            $rule[] = $value['id'];
        }
        array_push($rule,$request->input('id'));

        $rule = Rule::destroy($rule);
        if ($rule) {
            return back()->withErrors(['success'=>'删除成功！'])->withInput();
        } else {
            return back()->withErrors(['error'=>'删除失败！'])->withInput();
        }
    }
}
