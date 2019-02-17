<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Validator;

class ArticleController extends BackendController
{
    //文章列表
    public function index(Request $request)
    {
        $articles = Article::with(['category','admin'])->paginate();
        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.article.index',['articles'=>$articles,'categorys'=>$categorys]);
    }

    //添加文章
    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'title'=>'required',
                'content'=>'required',
                'category_id'=>'required|exists:categorys,id',
            ], [
                'title.required'=>'标题不能为空',
                'content.required'=>'内容不能为空',
                'category_id.required'=>'请选择栏目',
                'category_id.exists'=>'该栏目不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $article = new Article();
            $article->title = $request->input('title');
            $article->keywords = $request->input('keywords');
            $article->description = $request->input('description');
            $article->content = $request->input('content');
            $article->category_id = $request->input('category_id');
            $article->admin_id = $request->session()->get('admin.id');
            if ($article->save()) {
                return back()->withErrors(['success'=>'添加成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'添加失败！'])->withInput();
            }
        }

        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.article.add',['categorys'=>$categorys]);
    }

    //查看文章
    public function info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:articles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $article = Article::find($request->input('id'));
        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.article.info',['article'=>$article,'categorys'=>$categorys]);
    }

    //编辑文章
    public function edit(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'id'=>'required|exists:articles,id',
                'title'=>'required',
                'content'=>'required',
                'category_id'=>'required|exists:categorys,id',
            ], [
                'id.required'=>'ID不能为空',
                'id.exists'=>'该数据不存在',
                'title.required'=>'标题不能为空',
                'content.required'=>'内容不能为空',
                'category_id.required'=>'请选择栏目',
                'category_id.exists'=>'该栏目不存在',
            ]);
            if ($validator->fails()) return back()->withErrors($validator)->withInput();

            $article = Article::find($request->input('id'));
            $article->title = $request->input('title');
            $article->keywords = $request->input('keywords');
            $article->description = $request->input('description');
            $article->content = $request->input('content');
            $article->category_id = $request->input('category_id');
            $article->admin_id = $request->session()->get('admin.id');
            if ($article->save()) {
                return back()->withErrors(['success'=>'修改成功！'])->withInput();
            } else {
                return back()->withErrors(['error'=>'修改失败！'])->withInput();
            }
        }

        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:articles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $article = Article::find($request->input('id'));
        $categorys = $this->_sort(Category::orderBy('sort','asc')->get());
        return view('admin.article.edit',['article'=>$article,'categorys'=>$categorys]);
    }

    //删除文章
    public function del(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:articles,id'
        ], [
            'id.required'=>'ID不能为空',
            'id.exists'=>'该数据不存在',
        ]);
        if ($validator->fails()) return back()->withErrors(['error'=>$validator->errors()->first()])->withInput();

        $article = Article::destroy($request->input('id'));
        if ($article) {
            return back()->withErrors(['success'=>'删除成功！'])->withInput();
        } else {
            return back()->withErrors(['error'=>'删除失败！'])->withInput();
        }
    }
}
