<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends BackendController
{
    //栏目列表
    public function index(Request $request)
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

        $data = $this->_sort(Category::orderBy('sort','asc')->get());
        $categorys = new LengthAwarePaginator(array_slice($data,$offset,$pageSize),count($data),$pageSize,$page,['path'=>$request->url(),'query'=>$request->query()]);

        return view('admin.category.index',['categorys'=>$categorys]);
    }

    //添加栏目
    public function add(Request $request)
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
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
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

        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.category.add',['categorys'=>$categorys]);
    }

    //查看栏目
    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:categorys,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $category = Category::find($request->input('id'));
        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.category.info',['category'=>$category,'categorys'=>$categorys]);
    }

    //编辑栏目
    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:categorys,id',
                'name'=>'required',
                'type'=>'required|integer',
                'parent_id'=>'required|integer',
                'sort'=>'required|integer',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'name.required'=>'名称不能为空',
                'type.required'=>'请选择类型',
                'type.integer'=>'类型错误',
                'parent_id.required'=>'上级权限不能为空',
                'parent_id.integer'=>'上级权限为整数',
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
        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.category.edit',['category'=>$category,'categorys'=>$categorys]);
    }

    //删除栏目
    public function del(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:categorys,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        DB::beginTransaction();
        try {
            $categorys = $this->_sort(Category::orderBy('sort','asc')->get(),$request->input('id'));
            $category = [];
            foreach ($categorys as $key=>$value) {
                $category[] = $value['id'];
            }
            array_push($category,$request->input('id'));

            $categorys = Category::destroy($category);
            if (!$categorys) throw new \Exception('删除失败！');


            //由于可能栏目下无文章,这样删除会返回false,
            //如果需要返回正确结果,可以选查询出这些栏目下的所有文章,然后在删除,懒得去搞这些费时间的事,直接删除好了
            $article = Article::whereIn('category_id',$category)->delete();
            //if (!$article) throw new \Exception('删除失败！');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error'=>$e->getMessage()])->withInput();
        }
        return back()->withErrors(['success'=>'删除成功！'])->withInput();
    }
}
