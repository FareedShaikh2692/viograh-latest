<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use App\UserForgotPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Lang;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Common_function;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\UserVerification;
use App\MailSetting;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        //$this->middleware('guest');
    }

    public function showResetForm($token)
    {
        if($token!='') {
            $forgot_res = UserForgotPassword::where('token','=',$token)->first();
     
            if(isset($forgot_res) && $forgot_res->user->status=='enable') {
                
                $data = array('action' => url('reset-password/' . $token),'title'=> 'Reset Password');
                $data['token'] = $token;
                //$data['userEmail'] = $forgot_res->user->email;
                return view('reset_pass', $data);
            }
            if(isset($forgot_res) && $forgot_res->user->status=='disable') {
                
                return redirect('/')->with('error',__('adminlte::custom.error_account_disable'));
            }
            return redirect('/')->with('error',__('adminlte::custom.token_miss_match'));
        }
    }

    public function reset(Request $request,$token){
        if($token!='') {
            // VALIDATION RULE
            $validation_array = array(
                'password'=>'required|min:6|max:255',
                'confirm_password'=>'same:password',
            );
            $rules = [
                'password.required' => 'The password is required',
                'password.min' => 'Minimum 6 characters required for password',
                'password.max' => 'Maximum 255 characters allowed for password',
                'confirm_password.same' => 'Confirm password must be same as Password',
            ];

            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);

            $forgot_res = UserForgotPassword::where('token','=',$token)->first();

            if(isset($forgot_res) && $forgot_res->user->status=='enable') {
                User::where('id','=',$forgot_res->user_id)
                    ->update(['password'=>bcrypt($request->password)]);
                
                UserForgotPassword::where('token','=',$token)->delete();

                return redirect('/')->with('success', __('adminlte::custom.succ_reset_password'));
            }
            else {
                //TOKEN MISMATCH
                return redirect('/')->with('error', __('adminlte::custom.token_miss_match'));
            }
        }
        else{
            return redirect('/');
        }
    }

    public function broker()
    {
        return Password::broker('users');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }
}
