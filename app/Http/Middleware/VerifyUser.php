<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

use Illuminate\Support\Facades\Session;
use Config;
use Illuminate\Http\Request;
use Lang;
use Redirect;
use Carbon\Carbon;

class VerifyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */ protected $session;
     
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if($user->status=='disable'){
                Auth::logout();
                return redirect('/')->with('error', __('custom.error_account_disable'));
            }
            else if($user->status=='delete'){
                Auth::logout();
                return redirect('/')->with('error', __('custom.error_account_delete'));
            }
           
            return $next($request);
        }
        else{
        
            return redirect('/');
           
        }
    }
}