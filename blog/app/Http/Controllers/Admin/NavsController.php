<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Model\Navs;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class NavsController extends CommonController
{
    //get.admin/navs  全部自定义导航列表
    public function index() {       
        $data = Navs::orderBy('nav_order','asc')->get();
        return view('admin.navs.index',compact('data'));
    }
    public function changeOrder() {
        $input = Input::all();
        $navs = Navs::find($input['nav_id']);
        $navs->nav_order = $input['nav_order'];
        $re = $navs->update();
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
    
    //get.admin/navs/create  添加自定义导航
    public function create() {
//         $data = Navs::where('nav_pid',0)->get();
        return view('admin/navs/add');
    }
    //post.admin/navs  添加自定义导航提交
    public function store() {
        $input = Input::except('_token');
        $rules = ['nav_name' => 'required','nav_url' => 'required'];
        
        $message = [
            'nav_name.required'=>'自定义导航名称不能为空!',
            'nav_url.required'=>'自定义导航url不能为空!',
        ];
        
        $validator = Validator::make($input,$rules,$message);
        if ($validator->passes()){
            $re = Navs::create($input);
            if($re){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','添加失败，请稍后再试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    //get.admin/navs/{navs}/edit  编辑自定义导航
    public function edit($nav_id) {
        $field = Navs::find($nav_id);
        return view('admin.navs.edit',compact('field'));
    }
    //put.admin/navs/{navs}  更新自定义导航
    public function update($nav_id) {
        $input = Input::except('_token','_method');
        $re = Navs::where('nav_id',$nav_id)->update($input);
        if($re){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','更新失败，请稍后再试！');
        }
    }
    
    //get.admin/navs/{navs}  显示自定义导航
    public function show() {
    
    }
    
    //delete.admin/navs/{navs}  删除自定义导航
    public function destroy($nav_id) {
        $re = Navs::where('nav_id',$nav_id)->delete();
      
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
