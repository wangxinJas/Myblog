<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Model\Category;
use Illuminate\Support\Facades\Input;
use App\Http\Model\Article;
use Illuminate\Support\Facades\Validator;

class ArticleController extends CommonController
{
    //get.admin/article  全部文章列表
    public function index(){
        $data = Article::orderBy('art_id','desc')->paginate(5);
        
        return view('admin.article.index',compact('data'));
        
    }
    
    //get.admin/article/create  添加文章
    public function create(){
        
        $data = (new Category)->tree(); 
        return view('admin.article.add',compact('data'));
        
    }
    //post.admin/article 添加文章提交 
    public function store() {
        $input = Input::except('_token');
        $input['art_time'] = time();
        $rules = ['art_title' => 'required','art_content' => 'required'];
        
        $message = [
            'art_title.required'=>'文章标题不能为空!',
            'art_content.required'=>'文章内容不能为空!'
        ];
        
        $validator = Validator::make($input,$rules,$message);
        if ($validator->passes()){
        $re = Article::create($input);
            if($re){
                return redirect('admin/article');
            }else{
                return back()->with('errors','提交失败，请稍后再试！');
            }
        }else{
            return back()->withErrors($validator);
        }
        
    }
    //get.admin/article/{article}/edit  编辑文章
    public function edit($art_id) {
        
        $data = (new Category)->tree();
        $field = Article::find($art_id);
        return view('admin.article.edit',compact('data','field'));
    }
    
    //put.admin/article/{article}  更新文章
    public function update($art_id) {
        $input = Input::except('_token','_method');
        $re = Article::where('art_id',art_id)->updated($input);
        if($re){
            return redirect('admin/article');
        }else{
            return back()->with('errors','更新失败，请稍后再试！');
        }
    }
    
    //delete.admin/article/{article}  删除文章
    public function destroy() {
        $re = Article::where('art_id',art_id)->delete($art_id);
        if($re){
            $data = [
                'status' => 0,
                'msg' => '文章删除成功！',
            ];
        }else{
            $data = [
                'status' => 0,
                'msg' => '删除失败，请稍后再试！',
            ];
        }
        return $data;
    }
    
}
