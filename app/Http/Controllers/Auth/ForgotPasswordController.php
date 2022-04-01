<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Common_function;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Lang;
use Session;
use App\User;
use App\UserVerification;
use App\MailSetting;
use App\UserForgotPassword;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
    }

    public function showEmailForm(){
        $data = array('title' => 'Forgot password');
        if (Auth::check()) {
            return redirect('/home');
        } 
        return view('forgot_password', $data);
    }

    public function sendResetLinkEmail(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'forgot_email' => 'required|email',
        );
        $rules = [
            'forgot_email.required' => 'The email is required',
            'forgot_email.email' => 'Enter valid email address',
        ];
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // GET RECORDS RECORDS
        $user = User::where('status','!=','Delete')
        ->where('email','=',$request->forgot_email)
        ->orderBy('id','DESC')
        ->first();
        // CHECK RECORD
        if(!empty($user)){
            if($user->status=='disable'){
                // DISABLE
                Auth::logout();
                
                return redirect('/forgot-password')->withInput()->withError(trans('custom.error_account_disable'));
            }
            else if($user->status=='pending'){
                // PENDING
                Auth::logout();
                $request->session()->put('tmp_email_resend', $request->forgot_email);
                return redirect('/forgot-password')->withInput()->withError(trans('custom.user_acc_nt_verified'));

            }
            else{
                $user->token = str_random(10).time().uniqid().str_random(10);

                // DB::table('user_forgot_passwords')
                // ->updateOrInsert(
                //     ['user_id' => $user->id],
                //     ['user_id' => $user->id, 'token' => $user->token ]
                // );

                UserForgotPassword::updateOrInsert(['user_id' => $user->id],
                        ['user_id' => $user->id, 'token' => $user->token]);
                try{
                    // get site email
                    $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'user-forgot-password')->first();
                    // parameter for mail template and function to send

                    $msg = $mail_temp['content'];
                    
                    $msg = str_replace('[USER_NAME]', ucfirst($user->first_name).' '.ucfirst($user->last_name), $msg);
                    $msg = str_replace('[SITE_URL]', url(''), $msg);
                    $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
                    $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
                    $msg = str_replace('[YEAR]', date('Y'), $msg);
                    $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
                    $msg = str_replace('[RESET_LINK]', url('/reset-password/'.$user->token), $msg);

                    $email_data['to_email'] = $user->email;
                    $email_data['subject'] = $mail_temp['subject'];
                    $email_data['from_email'] = $mail_temp['from_email'];
                    $email_data['message'] = $msg;  
                     
                    Mail::send([], [], function ($message) use ($email_data) {
                    $message->to($email_data['to_email'])
                        ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                        ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                        ->setBody($email_data['message'], 'text/html');
                    });
                }catch(\Exception $e){
           
                }
                if (Mail::failures()) {
                    //ERROR MAIL NOT SEND
                    return redirect('/forgot-password')->withInput()->withError(trans('custom.mail_send_error'));
                }else{
                    //SUCCESS MAIL SEND
                    return redirect('/forgot-password')->withSuccess( __('adminlte::custom.succ_forgot_mail_send'));
                }
            }
        }else{
            //EMAIL ID NOT EXIST
            return redirect('/forgot-password')->withInput()->withError(trans('custom.acc_not_exists'));
        }
    }
}
