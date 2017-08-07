<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\Http\Model\User;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
        if($input = Input::all()){
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                return back()->with('msg','验证码错误！');     
            }           
            
            $user = User::first();
            if($user->user_name != $input['user_name'] || Crypt::decrypt($user->user_pass) != $input['user_pass'])
            {
                return back()->with('msg','用户名或密码错误！');               
            }
            session(['user'=>$user]);
            return redirect('admin/index');
        }else {
//             $user = User::first(); 
//             dd($user);
            return view('admin.login');
        }
    }
    public function code()
    {
        $code = new \Code;
        $code->make();
    }
    public function quit()
    {
        session(['user'=>NULL]);
        return redirect('admin/login');
    }
//     public function getcode()
//     {
//         $code = new \Code;
//         echo $code->get();
//     }

}