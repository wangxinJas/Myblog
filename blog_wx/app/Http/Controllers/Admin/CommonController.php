<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    //图片上传
    public function upload(){
        $file = Input::file('Filedata');
        //检验上传文件是否有效
        if($file->isValid()){
            $realPath = $file->getRealPath();  //临时文件的绝对路径
            $extension = $file->getClintOriginalExtention();  //获取上传文件的后缀名
            
            $newName = date('YmdHis').mt_rand(100, 999).'.'.$extension;
            $path = $file->move(base_path().'/uploads',$newName);
            $filepath = 'uploads/'.$newName;
            return $filepath;
        }
    }
}
