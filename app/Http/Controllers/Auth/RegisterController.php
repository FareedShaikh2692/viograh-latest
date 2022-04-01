<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Helpers\Common_function;
use Carbon\Carbon;
use Lang;
use Session;
use App\User;
use App\UserFamilyTree;
use App\UserVerification;
use App\MailSetting;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        //
    }

    public function registers(){
        if (Auth::check()) {
            return redirect('/home');
        } 
        $data = array('title'=>"Sign up",'action'=>url('register/insert'),'frn_id'=>'frm_user_register');
        return view('signup',$data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'first_name' => 'required|max:32|min:2',
            'last_name' => 'required|max:32|min:2',
            'email'=>'required|email|max:100',
            'phone_number' => 'required|numeric|min:6',
            'birth_date' => 'required',
            'gender' => 'required',
            'password'=>'required|min:6|max:255',
            //'confirm_password'=>'same:password',
        );
        $rules = [
            'first_name.required' => 'The first name is required',
            'first_name.max' => 'Maximum 32 characters allowed for first name',
            'first_name.min' => 'Minimum 2 characters required for first name',
            'last_name.required' => 'The last name is required',
            'last_name.max' => 'Maximum 32 characters allowed for last name',
            'last_name.min' => 'Minimum 2 characters required for last name',
            'email.required' => 'The email is required',
            'email.email' => 'Enter valid email address',
            'email.max' => 'Maximum 100 characters allowed for email',
            'phone_number.required' => 'The mobile number is required',
            'phone_number.min' => 'Minimum 06 digits allowed for mobile number',
            'phone_number.numeric' => 'Only numeric allowed',
            'birth_date.required' => 'Birth date is required',
            'gender.required' => 'Gender is required',
            'password.required' => 'The password is required',
            'password.min' => 'Minimum 6 characters required for password',
            'password.max' => 'Maximum 255 characters allowed for password',
            //'confirm_password.same' => 'Confirm password must be same as Password',
        ];
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        $check_email_exists = User::where([['email','=',$request->email],['status','!=','delete']])->first();

        if(empty($check_email_exists)){
            try{   
                //GENERATE PASSWORD TOKEN
                $token = Common_function::generateToken(20).time().Common_function::generateToken(20); 
                // get site email
                $mail_temp = MailSetting::where('status', '!=', 'delete')->where('slug', '=', 'user-email-verification')->first();
                $msg = $mail_temp['content'];

                $msg = str_replace('[SITE_URL]', url(''), $msg);
                $msg = str_replace('[LOGO_LINK]', url(asset('images/logo.png')), $msg);
                $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
                $msg = str_replace('[YEAR]', date('Y'), $msg);
                $msg = str_replace('[USER_EMAIL]', $request->email, $msg);
                $msg = str_replace('[USER_NAME]', $request->first_name.' '.$request->last_name, $msg);
                $msg = str_replace('[VERIFICATION_LINK]', url('/verify/'.$token), $msg);
                $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
                $msg = str_replace('[CONTACT_US]', config('custom_config.settings.site_email'), $msg);


                $email_data['from_email'] = $mail_temp['from_email'];
                $email_data['to_email'] = $request->email;
                $email_data['subject'] = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $mail_temp['subject']);
                $email_data['message'] = $msg;


                Mail::send([], [], function ($message) use ($email_data) {
                $message->to($email_data['to_email'])
                    ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                    ->subject( $email_data['subject'])
                    ->setBody($email_data['message'], 'text/html');
                });
            }catch(\Exception $e){
            
            }
                if (Mail::failures()) {
                    return redirect('/signup')->withInput()->withError(trans('adminlte::custom.mail_send_fail'));
                    exit;
                }else{
                    // INSERT ARRAY
                    $user = User::create(array(
                        'first_name' => ucwords($request->first_name),
                        'last_name' => ucwords($request->last_name),
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'login_platform' => 'native',
                        'gender' => $request->gender,
                        'phone_number' => $request->phone_number,
                        'birth_date' => date_format( date_create($request->birth_date), 'Y-m-d' ),
                        'status' => 'pending',
                        'created_ip' => ip2long(\Request::ip()),
                        'updated_ip' => ip2long(\Request::ip()),
                        'created_at'=>Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ));
                  

                    $customer_verification = UserVerification::create(array(
                        'user_id' => $user->id,
                        'token' => $token, 
                    ));
                    $familyTreeArray = array(
                        'user_id' => $user->id,
                        'family_tree_id' => 0,
                        'name' => ucwords($request->first_name),
                        'email' => $request->email,
                        'age' => ($request->birth_date != '') ?Common_function::age($user->birth_date):'',
                        'gender' => $request->gender,
                        'phone_number' => $request->phone_number,
                        'relationship' => 1,
                        'status' => 'enable',
                        'created_ip' => ip2long(\Request::ip()),
                        'updated_ip' => ip2long(\Request::ip()),
                        'created_at'=>Carbon::now(),
                        'updated_at' => Carbon::now(),
                    );
                    UserFamilyTree::insert($familyTreeArray);
                }
            
            $request->session()->put('tmp_user_id', $user->id);
            $request->session()->put('tmp_email_resend', $request->email);
            $request->session()->put('tmp_password', $request->password);

            return redirect('/signup')->withSuccess(trans('custom.user_acc_nt_verified'));
        }else{
            return redirect('/signup')->withInput()->withError(trans('adminlte::custom.email_exists'));
        }
    }

    public function verify($token, Request $request){
        $verifyUser = UserVerification::where('token', $token)->first();
        
        // CHECK EXPIRY WITH IN 2 DAYS
        if(isset($verifyUser) && $verifyUser->userDetail->status=='pending'){
            $difference = date('Y-m-d', strtotime($verifyUser->created_at.' +2 day'));
            if($difference <= Carbon::now()) {

                UserVerification::where('user_id', $verifyUser->user_id)->delete();
    
                return redirect('/signup')->withError(trans('custom.token_expires'));
            }
            else
            {
                User::where('id','=',$verifyUser->user_id)
                    ->update(['status' => 'enable','updated_at' => Carbon::now(),'updated_ip' => ip2long(\Request::ip())]);
                
                UserVerification::where('user_id', $verifyUser->user_id)->delete();
    
                return redirect('/')->withSuccess(trans('custom.user_acc_verified'));
            }
        }else{
            return redirect('/signup')->withInput()->withError(trans('custom.token_expires'));
        }
    }

    // RESEND VERIFICATION MAIL
    public function resend(Request $request){
        $email = $request->session()->get('tmp_email_resend');
        $res = array('code'=>0);
        if($email!=''){
            $result = User::where('email','=',$email)
                    ->where([['status','=','pending']])
                    ->orderBy('id','DESC')
                    ->first();
                   
            if(isset($result)){
                //SEND MAIL

                //GENERATE PASSWORD TOKEN
                $token = Common_function::generateToken(20).time().Common_function::generateToken(20); 

                // get site email
                $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'user-email-verification')->first();

                // parameter for mail template and function to send
                $msg = $mail_temp['content'];

                $msg = str_replace('[SITE_URL]', url(''), $msg);
                $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
                $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
                $msg = str_replace('[YEAR]', date('Y'), $msg);
                $msg = str_replace('[USER_EMAIL]', $request->email, $msg);
                $msg = str_replace('[USER_NAME]', $result->first_name.' '.$result->last_name, $msg);
                $msg = str_replace('[VERIFICATION_LINK]', url('/verify/'.$token), $msg);
                $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
                $msg = str_replace('[CONTACT_US]', config('custom_config.settings.contact_email'), $msg);

                $email_data['from_email'] = $mail_temp['from_email'];
                $email_data['to_email'] = $result->email;
                $email_data['subject'] = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $mail_temp['subject']);
                $email_data['message'] = $msg;
               
                Mail::send([], [], function ($message) use ($email_data) {
                  $message->to($email_data['to_email'])
                      ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                      ->subject( $email_data['subject'])
                      ->setBody($email_data['message'], 'text/html');
                });

                $user_verification = array(
                    'user_id' => $result->id,
                    'token' => $token, 
                );
                UserVerification::updateOrCreate(array('user_id' => $result->id),$user_verification);

                $data['code'] = 1;
                return response()->json($data);exit;
            }
            else{
                $data['msg'] = Lang::get('custom.succ_email_verification');
                $data['code'] = 0;
                return response()->json($data);exit;
            }
        }
        // AJAX RESPONSE
        return response()->json($res);
    }
}
