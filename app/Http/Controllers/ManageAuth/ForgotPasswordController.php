<?php

namespace App\Http\Controllers\ManageAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Manage;
use App\ManagePasswordResets;
use App\MailSetting;
use DB;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {        
        return view('adminlte::auth.passwords.email');
    }
    public function sendResetLinkEmail(Request $request)
    {
        // VALIDATION RULE
        $validation_array = array(
            'email' => 'required|email',
        );
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);
        
        // GET RECORDS RECORDS
        $user = Manage::where('email','=',$request->email)
            ->where('status','!=','Delete')
            ->orderBy('id','DESC')
            ->get();
        
        
        // CHECK RECORD
        if($user->count()>0){
            $user = $user[0];
           
            if($user->status=='Disable'){
                // DISABLE
                auth()->guard('manage')->logout();
                return redirect()->route('password.email')
                    ->withErrors( __('adminlte::custom.error_account_disable'))
                    ->withInput();
            }
            else{
                $user->token = str_random(10).  time().uniqid().str_random(10);
                DB::table('manage_password_resets')
                ->updateOrInsert(
                    ['user_id' => $user->id],
                    ['user_id' => $user->id, 'token' => $user->token ]
                );
                
                $user->link = '/manage/password/reset/'.$user->token;

                $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'admin-forgot-password')->first();

                // parameter for mail template and function to send
                $msg = $mail_temp['content'];

                $msg = str_replace('[USER_NAME]', $user->first_name.($user->last_name != '' ? (' '.$user->last_name) : ''), $msg);
                $msg = str_replace('[SITE_URL]', url(''), $msg);
                $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
                $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
                $msg = str_replace('[YEAR]', date('Y'), $msg);
                $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
                $msg = str_replace('[RESET_LINK]', url('manage/password/reset/'.$user->token), $msg);

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
                return redirect('manage/password/reset')
                ->withSuccess( __('adminlte::custom.succ_forgot_mail_send'));
            }
        }
        else{
            // NO ACCOUNT EXISTS
            return redirect('manage/password/reset')
                ->withErrors( __('adminlte::custom.error_email_account_not_exists'))
                ->withInput();
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
}
