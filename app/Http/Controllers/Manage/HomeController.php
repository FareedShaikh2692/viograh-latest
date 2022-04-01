<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Manage;
use Illuminate\Support\Facades\Redirect;
use App\MailSetting;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Admin';
    }

    public function index(Request $request){
        $data = array('title'=>"Mail Templates",'main_module'=>$this->main_module);
        $data['results'] = MailSetting::where('status','!=','delete');
        $data['tbl'] = Common_function::encrypt('mail_settings');

        //Search data
        $data['module_name'] = $request->input('module');
        if($data['module_name']!=''){

            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->orWhere('module', '=', $data['module_name']);  
            });
        }

        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'))->onEachSide(2);

        $data['modules'] = MailSetting::distinct()->get()->where('status','=','enable')->toArray();

        $data['results']->appends(['module'=>$data['module_name']]);

        return view('manage.mail_settings',$data);
    }
}
