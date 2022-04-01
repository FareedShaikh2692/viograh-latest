<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\User;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'change_password';  
            
    }
    public function index(){
        $data = array('title'=>" Edit Password",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('change-password/update/'),'frn_id'=>'frm_password_edit');

        $data['results'] = User::where('status','=','enable') 
            ->where('id','=',Auth::user()->id)       
            ->first();
        
        return view('change_password', $data);
    }
  
    public function update(Request $request){
            $data['results'] = User::where('status','=','enable') 
            ->where('id','=',Auth::user()->id)       
            ->first();

            $google_id=$data["results"]->google_id;
            $password=$data["results"]->password;
    

                $validation_array = array(
                    'current_password' => 'required|min:6|max:255', 
                    'new_password' => 'required|min:6|max:255|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                    'confirm_password' => 'required_with:new_password|same:new_password'
                );
                $rules=[
                    'new_password.regex' => 'Atleast one uppercase, lowercase, number and special character required for new password',
                    'confirm_password.same' => 'New Password and Confirm Password Must be same.'
                ];
                $this->validate($request,$validation_array,$rules);
                $data = $request->all();
                $user = auth()->user();
                
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect('change-password')->withInput()->withError(trans('custom.error_current_password'));
                }else{
                    $user->update([                   
                        'password' =>  Hash::make($request->new_password),
                    ]);
                    return redirect('change-password')->withSuccess(trans('custom.succ_password_updated'));
                }
           
    }
}
