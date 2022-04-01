<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Session;
use Config;
use Request;
use Lang;
use Redirect;
use Carbon\Carbon;

class VerifyNetworthNomineePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */ protected $session;
     
    public function handle($request, Closure $next)
    {
        if(session()->has('verifyNetworthLogin')==true && ((int)(time() - $request->session()->get('verifyNetworthLogin')) / 60) <=  config('custom_config.networth__nominee_login_allowed_time')){
                
            $request->session()->put('verifyNetworthLogin',time());
        }
        else{
            Session::forget('verifyNetworthLogin');

        } 
        return $next($request);
    }
}
