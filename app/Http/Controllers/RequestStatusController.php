<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ContactUs;
use App\MailSetting;
use Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\UserFamilyTree;
use App\User;
use Auth;

class RequestStatusController extends Controller
{
    public function index(Request $request){
      
        $status_id = $request->input('id');
        $token = $request->input('token');
        $action = $request->input('action');
        
        $query = UserFamilyTree::where('id','=',$status_id)
                ->where('token','=',$token)
                ->first();
       
        if(!empty($query)){
            if($query->request_status == 'pending'){             
                if(Auth::check()){
                    if($query->email != Auth::user()->email){
                        return redirect()->route('index')->withError(trans('custom.error_user_req'));
                    }
                    else{
                        $update_array = array(
                            'token' => NULL,
                            'updated_ip' => ip2long(\Request::ip()),
                            'updated_at' => Carbon::now(),
                        );
                        ($action == 'accept') ? $update_array['request_status'] = 'accept' : $update_array['request_status'] = 'reject'; 
                        UserFamilyTree::where('id','=',$status_id)->update($update_array);
                        if($action == 'accept'){
                            return redirect()->route('index')->withSuccess(trans('custom.status_accept'));
                        } else {
                            return redirect()->route('index')->withError(trans('custom.status_reject'));
                        }
                    }                    
                }
                else{
                    return redirect('/')->withError(trans('custom.err_without_login'));
                }
            }else{
                if(Auth::check()){
                    if($query->email != Auth::user()->email){
                        return redirect()->route('index')->withError(trans('custom.status_wrong'));
                    }
                    else{
                        if($query->request_status == 'accept'){
                            return redirect()->route('index')->withError(trans('custom.err_already_accept'));
                        }else{
                            return redirect()->route('index')->withError(trans('custom.err_already_reject'));
                        }
                    }
                }
                else{
                    return redirect('/')->withError(trans('custom.err_without_login'));
                }    
            }     
        }
        else{  
            if(Auth::check()){
                return redirect()->route('index')->withError(trans('custom.status_wrong'));
            }else{
                return redirect('/')->withError(trans('custom.status_wrong'));
            } 
        }
    }
}
