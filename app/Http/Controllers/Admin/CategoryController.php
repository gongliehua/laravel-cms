<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    // 栏目列表
    public function categoryList(Request $request)
    {
        if ($request->isMethod('post')) {
            $sort = $request->input('sort');
            foreach ($sort as $key=>$value) {
                Category::where(['id'=>intval($key)])->update(['sort'=>intval($value)]);
            }
            return back()->withErrors(['success'=>'排序成功！'])->withInput();
        }

        $page = $request->input('page',1);
        $pageSize = $request->input('pageSize',15);
        $offset = ($page-1)*$pageSize;

        $data = $this->sorting(Category::orderBy('sort','asc')->get());
        $categorys = new LengthAwarePaginator(array_slice($data,$offset,$pageSize),count($data),$pageSize,$page,['path'=>$request->url(),'query'=>$request->query()]);

        return view('admin.category.index',['categorys'=>$categorys]);
    }

    // 栏目添加
    public function categoryAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'type'=>'required|integer',
                'parent_id'=>'required|integer',
                'sort'=>'required|integer',
            ], [
                'name.required'=>'名称不能为空',
                'type.required'=>'请选择类型',
                'type.integer'=>'类型错误',
                'parent_id.required'=>'上级栏目不能为空',
                'parent_id.integer'=>'上级栏目为整数',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $categorys = new Category();
            $categorys->name = $request->input('name');
            $categorys->type = $request->input('type');
            $categorys->keywords = $request->input('keywords');
            $categorys->description = $request->input('description');
            $categorys->content = $request->input('content');
            $categorys->parent_id = $request->input('parent_id');
            $categorys->sort = $request->input('sort');
            if ($categorys->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }

        $categorys = $this->sorting(Category::orderBy('sort','asc')->get());
        return view('admin.category.add',['categorys'=>$categorys]);
    }

    // 栏目信息
    public function categoryInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:categorys,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该栏目不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $category = Category::find($request->input('id'));
        $categorys = $this->sorting(Category::orderBy('sort','asc')->get());
        return view('admin.category.info',['category'=>$category,'categorys'=>$categorys]);
    }

    // 栏目编辑
    public function categoryEdit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:categorys,id',
                'name'=>'required',
                'type'=>'required|integer',
                'parent_id'=>'required|integer|not_in:'.$request->input('id'),
                'sort'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'name.required'=>'名称不能为空',
                'type.required'=>'请选择类型',
                'type.integer'=>'类型错误',
                'parent_id.required'=>'上级栏目不能为空',
                'parent_id.integer'=>'上级栏目为整数',
                'parent_id.not_in'=>'上级栏目不能是自己',
                'sort.required'=>'排序不能为空',
                'sort.integer'=>'排序为整数',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $categorys = Category::find($request->input('id'));
            $categorys->name = $request->input('name');
            $categorys->type = $request->input('type');
            $categorys->keywords = $request->input('keywords');
            $categorys->description = $request->input('description');
            $categorys->content = $request->input('content');
            $categorys->parent_id = $request->input('parent_id');
            $categorys->sort = $request->input('sort');
            if ($categorys->save()) {
                return back()->withErrors(['success'=>'修改成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'修改失败！'])->withInput();
            }
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:categorys,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $category = Category::find($request->input('id'));
        $categorys = $this->sorting(Category::orderBy('sort','asc')->get());
        return view('admin.category.edit',['category'=>$category,'categorys'=>$categorys]);
    }

    // 栏目删除
    public function categoryDel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:categorys,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该栏目不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $category = Category::where('parent_id',$request->input('id'))->first();
        if ($category) return back()->withErrors(['error'=>'有其它栏目在使用该栏目作为上级栏目,不能删除'])->withInput();

        $article = Article::where('category_id',$request->input('id'))->first();
        if ($article) return back()->withErrors(['error'=>'有文章使用该栏目,不能删除'])->withInput();

        $delCategory = Category::destroy($request->input('id'));
        if ($delCategory) {
            return back()->withErrors(['success'=>'删除成功！'])->withInput();
        } else {
            return back()->withErrors(['error'=>'删除失败！'])->withInput();
        }
    }
}
