<?php

namespace App\Http\Controllers\ManageAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Manage;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/manage/admins';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $maxAttempts;

    protected $decayMinutes;


    public function __construct()
    {        
        $this->middleware('manage.guest', ['except' => 'logout']);     
        $this->maxAttempts  = config('custom_config.maxAttempts');
        $this->decayMinutes = config('custom_config.decayMinutes');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    { 
        $data = array('title'=>"Admin Login");
        return view('adminlte::auth.login',$data);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('manage');
    }

    public function login(Request $request){

        if (Auth::guard('manage')->check()) {
            return redirect('/manage/admins');
        }    
               
        // VALIDATION RULE
        $validation_array = array(
            $this->username() => 'required|string',
            'password' => 'required|string',
        );
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // GET RECORDS RECORDS
        $user = Manage::where('email','=',$request->email)
            ->orderBy('id','DESC')
            ->get();

        $login_date = array(
            'email' => $request->email,
            'password' => $request->password,
        );
        // CHECK RECORD
        if($user->count()>0){
            $login_date['id'] = $user[0]->id;

            if(Auth::guard('manage')->attempt($login_date)){
                $this->clearLoginAttempts($request);
                if(Auth::guard('manage')->user()->status == 'disable') {
                    // DISABLE
                    auth()->guard('manage')->logout();
                    return redirect('/manage')
                        ->withErrors( __('adminlte::custom.error_account_disable'))
                        ->withInput();
                }
                else{
                    // SUCCESS LOGIN
                    return redirect('/manage/admins');
                }
            }
            else{
                // INVALID PASSWORD
                $this->incrementLoginAttempts($request);
                return redirect('/manage')
                    ->withErrors( __('adminlte::custom.error_password_invalid'))->withInput();
            }
        }
        else{
            // NO ACCOUNT EXISTS
            return redirect('/manage')
                ->withErrors( __('adminlte::custom.error_account_not_exists'))->withInput();
        }
    }

    public function logoutToPath() {
        return '/manage';
    }

    public function logout(Request $request) {        
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($response = $this->loggedOut($request)) {            
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/manage');
    }
}
