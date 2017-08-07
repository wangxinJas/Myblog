<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Model\config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class configController extends CommonController
{   
    //get.admin/config  全部配置项列表
    public function index() {
        $data = Config::orderBy('conf_order','asc')->get();
        foreach ($data as $k=>$v){
            switch ($v->fleid_type){
                case'input': 
                    $data[$k]->_html='<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'"/>';
                    break;
                case'radio': 
                    $arr=explode(',', $v->field_value);
                    $str='';
                    foreach ($arr as $m=>$n){
                        $r=explode('|', $n);
                        $c=$v->conf_content==$r[0]?' checked ':'';
                        $str.='<input type="radio" name="conf_content[]" value="'.$r[0].'"'.$c.' />'.$r[1]&nbsp;;
                    }
                    $data[$k]->_html=$str;
                    break;
                case'textarea': 
                    $data[$k]->_html='<textarea type="text" class="lg" name="conf_content[]">'. $v->conf_content.'</textarea>';
                    break;
            }
            
            
        }
        return view('admin.config.index',compact('data'));
    }
    
    public function putFile(){
        $config =Config::pluck('conf_content','conf_name')->all();
        //var_export($config,true);
        $path=base_path().'\config\web.php';
        $str='<?php return '.var_export($config,true).';';
        file_put_contents($path, $str);
         
    }
    
    public function changeContent(){
        $input = Input::all();
        foreach ($input['conf_id'] as $k=>$v){
            Config::where('conf_id',$v)->update(['conf_content'=>$input['conf_content'][$k]]);
        }
        $this->putFile();
        return back()->with('errors','配置项更新成功！');
        
    }
    
 
    
    public function changeOrder() {
        $input = Input::all();
        $config = Config::find($input['conf_id']);
        $config->conf_order = $input['conf_order'];
        $re = $config->update();
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
    
    //get.admin/config/create  添加全部配置项
    public function create() {
//         $data = config::where('conf_pid',0)->get();
        return view('admin/config/add');
    }
    
    //post.admin/config  配置项提交
    public function store() {
        $input = Input::except('_token');
        $rules = ['conf_name' => 'required','conf_title' => 'required'];
        
        $message = [
            'conf_name.required'=>'配置项名称不能为空!',
            'conf_title.required'=>'配置项标题不能为空!',
        ];
        
        $validator = Validator::make($input,$rules,$message);
        if ($validator->passes()){
            $re = Config::create($input);
            if($re){
                return redirect('admin/config');
            }else{
                return back()->with('errors','添加失败，请稍后再试！');
            }
        }else{
            return back()->withErrors($validator);
        }
    }
    //get.admin/config/{config}/edit  编辑全部配置项
    public function edit($conf_id) {
        $field = Config::find($conf_id);
        return view('admin.config.edit',compact('field'));
    }
    //put.admin/config/{config}  更新全部配置项
    public function update($conf_id) {
        $input = Input::except('_token','_method');
        $re = Config::where('conf_id',$conf_id)->update($input);
        if($re){
            $this->putFile();
            return redirect('admin/config');
        }else{
            return back()->with('errors','更新失败，请稍后再试！');
        }
    }
    
    //get.admin/config/{conf}  显示全部配置项
    public function show() {
    
    }
    
    //delete.admin/config/{config}  删除全部配置项
    public function destroy($conf_id) {
        $re = Config::where('conf_id',$conf_id)->delete();
        if($re){
            $this->putFile();
            $data = [
                'status' => 0,
                'msg' => '删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '删除失败，请稍后再试！',
            ];
        }
        return $data;
    }
}
