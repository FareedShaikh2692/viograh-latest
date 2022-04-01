<?php

namespace App\Http\Controllers\ManageAuth;

use App\Http\Controllers\Controller;
use App\Manage;
use App\ManagePasswordResets;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use DB;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/manage/home';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('manage.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token)
    {    
        if($token!='') {

            $forgot_res = ManagePasswordResets::where('token','=',$token)->first();
            if(isset($forgot_res) && $forgot_res->user->status=='enable') {
                
                $data = array('action' => url('manage/password/reset/' . $token));
                $data['token'] = $token;
                return view('adminlte::auth.passwords.reset', $data);
            }
            if(isset($forgot_res) && $forgot_res->user->status=='disable') {
                
                return redirect('/manage')->with('error',__('adminlte::custom.error_account_disable'));
            }
            return redirect('/manage')->with('error',__('adminlte::custom.token_miss_match'));
        }
    }

    public function reset(Request $request,$token){
        if($token!='') {
            $validator_rule = [
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ];
            $this->validate($request, $validator_rule);

            $forgot_res = ManagePasswordResets::where('token','=',$token)->first();
            if(isset($forgot_res) && $forgot_res->user->status=='enable') {
                if($forgot_res->user->email == $request->email) {

                    Manage::where('id','=',$forgot_res->user_id)
                        ->update(['password'=>bcrypt($request->password)]);
                    
                    DB::table('manage_password_resets')->where('token','=',$token)->delete();

                    return redirect('/manage')->with('success', __('adminlte::custom.succ_reset_password'));
                }
                else{
                    return back()->withInput()->with('error',__('adminlte::custom.js_email'));
                }
            }
            else {
                return redirect('/manage')->with('error', __('adminlte::custom.token_miss_match'));
            }
        }
        else{
            return redirect('/manage');
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('manages');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('manage');
    }
}
