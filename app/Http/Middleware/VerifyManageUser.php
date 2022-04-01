<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use app\User;
use App\Manage;
use Session;
use Config;
use Request;

class VerifyManageUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {	
        
        if (Auth::guard('manage')->check()) {
			$user = Auth::guard('manage')->user();
            
            $permission  = config('custom_config.admin_module_permission');
            $curClass = Request::segment(2);
            $curMethod = Request::segment(3);
            $curUserType = $user->admin_type;

            if($curClass == 'home'){$curClass = 'dashboard';}
            if($curMethod == ''){$curMethod = 'index';}

            //echo $curClass.' + '.$curMethod.' + '.$curUserType; die;
            
            if(!in_array($curUserType,$permission[$curClass][$curMethod]) AND !key_exists($curUserType,$permission[$curClass][$curMethod])){
                return redirect('/manage/')->with('error', __('adminlte::custom.access_denied'));
            }else{
                if($user->status=='Disable'){
                    Auth::guard('manage')->logout();
                    return redirect('/manage/login')->with('error', __('adminlte::custom.error_account_disable'));
                }
                else if($user->status=='Delete'){
                    Auth::guard('manage')->logout();
                    return redirect('/manage/login')->with('error', __('adminlte::custom.error_account_delete'));
                }
            }
            return $next($request);
        }
        else{
			return $next($request);
        }
    }
}