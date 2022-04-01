<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\User;

class PrivacySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'privacy';  
            
    }
    public function index(){
        $data = array('title'=>"Privacy Settings",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('privacy-settings/update/'),'frn_id'=>'frm_privacy_edit');

        $data['results'] = User::where('status','=','enable') 
            ->where('id','=',Auth::user()->id)       
            ->first();
        return view('privacy_setting', $data);
    }
  
    public function update(Request $request){
        $validation_array = array(
            'profile' => 'required',
            'notification' => 'required',
        );
        $rules = [
            'profile.required' => 'Please choose atleast one profile type.',
            'notification.required' => 'Please choose email notification.',
        ];
        
        $this->validate($request,$validation_array,$rules);
        $update_privacy_array = array(
            'id'=>Auth::user()->id,
            'profile_privacy' => $request->profile,
            'email_notification' =>  $request->notification,
            'created_ip' => ip2long(\Request::ip()),
            'updated_at' => Carbon::now(),
        );
     
        
        User::where('id','=',Auth::user()->id)
        ->update($update_privacy_array);
        return redirect('/privacy-settings')->withSuccess(trans('custom.succ_privacy_updated'));
        
    }
  
}
