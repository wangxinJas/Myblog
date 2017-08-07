<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Model\Links;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LinksController extends CommonController
{
    //get.admin/links  全部友情链接列表
    public function index() {
        $data = Links::orderBy('link_order','asc')->get();
        return view('admin.links.index',compact('data'));
    }
    public function changeOrder() {
        $input = Input::all();
        $links = Links::find($input['link_id']);
        $links->link_order = $input['link_order'];
        $re = $links->update();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '更新成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '更新失败！请稍后重试',
            ];
        }
        return $data;
    }
    
    //get.admin/links/create  添加友情链接
    public function create() {
//         $data = Links::where('link_pid',0)->get();
        return view('admin/links/add');
    }
    //post.admin/clinks  友情链接提交
    public function store() {
        $input = Input::except('_token');
        $rules = ['clink_name' => 'required','clink_url' => 'required'];
        
        $message = [
            'link_name.required'=>'友情链接名称不能为空!',
            'link_url.required'=>'友情链接url不能为空!',
        ];
        
        $validator = Validator::make($input,$rules,$message);
        if ($validator->passes()){
            $re = Links::create($input);
            if($re){
                return redirect('admin/links');
            }else{
                return back()->with('errors','添加失败，请稍后再试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    //get.admin/links/{links}/edit  编辑友情链接
    public function edit($link_id) {
        $field = Links::find($link_id);
        return view('admin.links.edit',compact('field'));
    }
    //put.admin/links/{links}  更新友情链接
    public function update($link_id) {
        $input = Input::except('_token','_method');
        $re = Links::where('link_id',$link_id)->updated($input);
        if($re){
            return redirect('admin/links');
        }else{
            return back()->with('errors','更新失败，请稍后再试！');
        }
    }
    
    //get.admin/links/{link}  显示友情链接
    public function show() {
    
    }
    
    //delete.admin/links/{links}  删除友情链接
    public function destroy($link_id) {
        $re = Links::where('link_id',$link_id)->delete();
        if($re){
            $data = [
                'status' => 0,
                'msg' => '删除成功！',
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
