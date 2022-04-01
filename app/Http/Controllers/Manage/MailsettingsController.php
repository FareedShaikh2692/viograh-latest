<?php

namespace App\Http\Controllers\Manage;

use App\Common_model;
use App\Helpers\Common_function;
use App\Manage;
use App\Setting;
use App\MailSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Config;
use Carbon\Carbon;

class MailsettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Mail Template';
    }
    public function index(Request $request)
    {    
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

        $data['modules'] = MailSetting::distinct()->where('status','!=','delete')->get('module')->toArray();

        $data['results']->appends(['module'=>$data['module_name']]);

        return view('manage.mail_settings',$data);
    }

    public function add(){
        $data = array('title'=>"Add Mail Template",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('manage/mail-settings/insert'),'frm_id'=>'frm_mail_setting_add');
        return view('manage.mail_settings_edit', $data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required|max:100',
            'module'=>'required|max:100',
            'from_email'=>'required|max:100|email',
            'to_email'=>'max:100',
            'from_text'=>'required|max:100',
            'subject'=>'required|max:100',
            'comment'=>'max:500',
            'slug'=>'required|max:100|unique:mail_settings',
            'mail_content'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'module.required' => 'The module is required',
            'module.max' => 'Maximum 100 characters allowed for module',
            'from_email.required' => 'The from email is required',
            'from_email.email' => 'Enter valid email address',
            'from_email.max' => 'Maximum 100 characters allowed for from email',
            'to_email.max' => 'Maximum 100 characters allowed for to email',
            'from_text.required' => 'The from text is required',
            'from_text.max' => 'Maximum 100 characters allowed for from text',
            'subject.required' => 'The subject is required',
            'subject.max' => 'Maximum 100 characters allowed for subject',
            'comment.max' => 'Maximum 500 characters allowed for comment',
            'slug.required' => 'The slug is required',
            'slug.max' => 'Maximum 100 characters allowed for slug',
            'slug.unique' => 'Slug should be unique',
            'mail_content.required' => 'The Mail content is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);
        
        // INSERT ARRAY
        $insert_array = array(
            'module'=>$request->module,
            'title'=>$request->title,
            'subject'=>$request->subject,
            'from_email'=>$request->from_email,
            'sent_mail_to_admin'=>$request->to_admin,
            'to_email'=>($request->to_email!='')?$request->to_email:"This mail sent to user's mail",
            'slug'=>$request->slug,
            'from_text'=>$request->from_text,
            'comment'=>$request->comment,
            'content'=>$request->mail_content,
            'status'=>'enable',
            'created_by'=>Auth::guard('manage')->user()->id,
            'updated_by'=>'0',
            'created_ip'=>ip2long(\Request::ip()),
            'created_at'=>Carbon::now(),
            'updated_at' => Carbon::now(),
        );
        MailSetting::insert($insert_array);

        return redirect('/manage/mail-settings')->withSuccess(trans('adminlte::custom.succ_mail_setting_added'));
        
        
    }

    public function edit($id){
        $data = array('title'=>"Edit Mail Template",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('manage/mail-settings/update/'.$id),'frm_id'=>'frm_mail_setting_edit');

        $data['mail_setting'] = MailSetting::where('status','!=','delete')->where('id','=',$id)->limit(1)->first();

        if(!empty($data['mail_setting'])) {
            return view('manage.mail_settings_edit', $data);
        }
        else{
            return redirect('/manage/mail-settings');
        }
    }

    public function update(Request $request,$id){
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required|max:100',
            'module'=>'required|max:100',
            'from_email'=>'required|max:100|email',
            'to_email'=>'max:100',
            'from_text'=>'required|max:100',
            'subject'=>'required|max:100',
            'comment'=>'max:500',
            'mail_content'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'module.required' => 'The module is required',
            'module.max' => 'Maximum 100 characters allowed for module',
            'from_email.required' => 'The from email is required',
            'from_email.email' => 'Enter valid email address',
            'from_email.max' => 'Maximum 100 characters allowed for from email',
            'to_email.max' => 'Maximum 100 characters allowed for to email',
            'from_text.required' => 'The from text is required',
            'from_text.max' => 'Maximum 100 characters allowed for from text',
            'subject.required' => 'The subject is required',
            'subject.max' => 'Maximum 100 characters allowed for subject',
            'comment.max' => 'Maximum 500 characters allowed for comment',
            'mail_content.required' => 'The Mail content is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // UPDATE ARRAY
        $update_array  = array(
            'module'=>$request->module,
            'title'=>$request->title,
            'subject'=>$request->subject,
            'from_email'=>$request->from_email,
            'to_email'=>($request->to_email!='')?$request->to_email:"This mail sent to user's mail",
            'from_text'=>$request->from_text,
            'comment'=>$request->comment,
            'content'=>$request->mail_content,            
            'updated_at' => Carbon::now(),
            'updated_ip' => ip2long(\Request::ip()),
            'updated_by' => auth()->guard('manage')->id(),
        );

        MailSetting::where('id','=',$id)->update($update_array);
        return redirect('/manage/mail-settings')->withSuccess(trans('adminlte::custom.succ_mail_setting_updated'));
        
    }

    public function information($id){
        $data = array('title'=>"Mail Templates Information",'main_module'=>$this->main_module,'method'=>'Information','url'=>url(''));
        $data['mail_setting'] = MailSetting::where('status','!=','delete')->where('id','=',$id)->limit(1)->first();

        if(!empty($data['mail_setting'])) {
            return view('manage.mail_settingsinfo', $data);
        }
        else{
            return redirect('/manage/mail-settings');
        }
    }
}
