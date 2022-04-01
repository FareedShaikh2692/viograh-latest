<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Helpers\Common_function;
use Carbon\Carbon;
use Lang;
use Laravel\Socialite\Facades\Socialite;
use App\UserVerification;
use App\MailSetting;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        
    }

    public function showLoginForm(){
      
        if (Auth::check()) {
            return redirect('/home');
        } 
        return view('login');
    }

    public function login(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'email'=>'required|email|max:100',
            'password'=>'required|min:6|max:255',
        );
        $rules = [
            'email.required' => 'The email is required',
            'email.email' => 'Enter valid email address',
            'email.max' => 'Maximum 100 characters allowed for email',
            'password.required' => 'The password is required',
            'password.min' => 'Minimum 6 characters required for password',
            'password.max' => 'Maximum 255 characters allowed for password',
        ];

        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        // GET RECORDS
        $user = User::where('email','=',$request->email)
        ->where('status','!=','Delete')
        ->orderBy('id','DESC')
        ->first();
        // CHECK RECORD
        if(!empty($user)){
            $login_date = array(
                'email' => $request->email,
                'password' => $request->password,
            );
            $login_date['id'] = $user->id;

            $remember_me  = ( !empty( $request->remember_me ) )? true : false;

            if(Auth::attempt($login_date,$remember_me)){
                $this->clearLoginAttempts($request);
                if(Auth::user()->status==='disable'){
                    // DISABLE
                    Auth::logout();
                    return redirect('/')->withInput()->withError(trans('custom.error_account_disable'));
                }
                elseif(Auth::user()->status==='pending'){
                    // PENDING AND EMAIL NOT VERIFIED
                    Auth::logout();
                    $request->session()->put('tmp_email_resend', $request->email);
                    return redirect('/')->withInput()->withError(trans('custom.user_acc_nt_verified'));
                }
                else{
                    // //SESSION CREATED OF USER ID EXISTS
                    // if(Auth::user()->id != ''){
                    //     session_start();

                    //     $user = User::where('id','=',Auth::user()->id)
                    //         ->where('status','!=','Delete')
                    //         ->orderBy('id','DESC')
                    //         ->get();
                    //     $namee = $user[0]->first_name;
                    //     $request->session()->put('userid', Auth::user()->id);
                    //     $request->session()->put('name', $namee);
                    //     $request->session()->put('email', $user[0]->email);
                    // }
                    // SUCCESS LOGIN
                    return redirect('/home');
                }
            }else{
                //INVALID PASSWORD
                $this->incrementLoginAttempts($request);
                return redirect('/')->withInput()->withError(trans('custom.invalid_password'));
            }
        }else{
            //ACCOUNT NOT EXIST
            return redirect('/')->withInput()->withError(trans('custom.acc_not_exists'));
        }
    }

    protected function guard(){
        return Auth::guard('web');
    }

    public function logout(Request $request){
        
        @Auth::logout();
        //$this->auth->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        
        // if ($response = $this->loggedOut($request)) {            
        //     return $response;
        // }
        // session_start();
        // session()->flush();
        // session_destroy();
        Session::forget('verifyNetworthLogin');
        return redirect('/');
    }

    //GOOGLE LOGIN
    public function redirectToGoogle()
    {    
      return Socialite::driver('google')->redirect();
    }
    //CALLBACK
    public function handleGoogleCallback(Request $request)
    {
        try {   
            //GET USER            
            $user = Socialite::driver('google')->user();              
            @$result = User::where('email',$user->email)->where('status','!=','delete')->first();            
            
            if(!empty(@$result))  {   
                             
                if(@$result->status == 'disable') {                      
                    return redirect('/')->withError(trans('custom.error_account_disable'));
                }
                else {                    
                    if(@$result->status == 'pending') {                        
                        $result->status = "enable";
                    }

                    if(@$result->google_id == '') {                        
                        $result->google_id = $user->id;
                    }
                    @$result->save();
                    //SESSION CREATED IF USER EXIST
                    session_start();
                    $namee = explode(" ", $user->name);
                    $request->session()->put('id',$result->id);
                    $request->session()->put('name', $namee[0]);
                    $request->session()->put('email', $user->email);                 
                    
                    Auth::login(@$result);                    
                    return redirect()->route('index');
                }
            }
            else {   
                $explode_name = explode(" ",$user->name);               
                 
                //CREATED NEW USER          
                $newUser = User::create(['first_name' => $explode_name[0],
                    'last_name' => $explode_name[1], 
                    'email' => $user->email, 
                    'google_id' => $user->id, 
                    'login_platform' => 'google', 
                    'status' => 'enable',
                    'created_at'=>Carbon::now(),
                    'updated_at' => Carbon::now()]);                            
               
                //SESSION CRETAED FOR NEW USER                
                session_start();
                $namee = explode(" ", $user->name);
                $request->session()->put('id',$newUser->id);
                $request->session()->put('name', $namee[0]);
                $request->session()->put('email', $user->email); 
                
                Auth::login($newUser);
                return redirect()->route('index');
            }
        }
        catch(Exception $e){
            return redirect('/')->withError($e->getMessage());
        }
    }
}  
             

    
   /*  public function handleGoogleCallback123(Request $request){

       try {
            $user = Socialite::driver('google')->user();
            echo"<pre>";
            print_r($user);exit;
            $existing_user = User::where('email',$user->email)->where('status','!=','delete')->first();
            //print_r($existing_user);exit;
            //$input_arr = $request->input();            
            //$existing_user = User::where([['email','=',$input_arr['email']],['status','!=','Delete']])->first();
            
            //NO ACCOUNT FOUND
            if(empty($existing_user)){
                $newUser = User::create(['name' => $user->name, 'email' => $user->email, 'google_id' => $user->id, 'login_platform' => 'google', 'status' => 'enable']);
                Auth::guard('users')->login($new_user);
                return response()->json(['code' => 1,'url' => '/home']);exit;
            }
            else
            {
                //USER IS ENABLED AND VERIFIED
                if($existing_user->status == 'enable'){
                    Auth::guard('users')->login($existing_user); 
                    if(@$request->redirect_url != '' && @$request->redirect_url != 'undefined' ){
                        $url = $request->redirect_url;
                    }
                    else{
                        $url = url('/');
                    }
                
                    return response()->json(['code' => 1,'url' => $url]);exit;
                }
                //USER IS DISABLED
                else if($existing_user->status == 'disable'){
                    Auth::logout();
                    return response()->json(['code' => 0, 'msg' => __('custom.error_account_disable')]);exit;
                }
                //USER IS NOT VERIFIED
                else if($existing_user->status == 'pending' || $existing_user->google_id == ''){
                
                    $existing_user->status = 'enable';
                    $existing_user->google_id = $input_arr['id'];
                    
                    $existing_user->save();
                    
                    Auth::guard('users')->login($existing_user);
                    return response()->json(['code' => 1,'url' => '/']);exit;
                }
            }
        }
        catch(Exception $e) 
        {
            return response()->json(['code' => 1,'url' => url('/')]);exit;
        }
    } */

    

