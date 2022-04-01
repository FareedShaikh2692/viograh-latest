<?php

namespace App\Http\Controllers\Manage;

use App\Common_model;
use App\Helpers\Common_function;
use App\Manage;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;


class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Setting';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Settings",'section'=>'general','main_module'=>$this->main_module);
        $section = $request->input('section');
        $data['results'] = Setting::where('status','!=','delete');
        $data['tbl'] = Common_function::encrypt('settings');

        if($section!=''){
            $data['section'] = $section;
        }
        $data['results'] =  $data['results']->where('setting_type','=',$data['section'])
            ->orderBy('id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'))->onEachSide(2);

        $data['results']->appends(['section'=>$data['section']]);
        return view('manage.settings',$data);
    }

    public function add(){
        $data = array('title'=>"Add Setting",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('manage/settings/insert'),'frm_id'=>'frm_setting_add');
        return view('manage.settings_edit', $data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required|max:100',
            'key'=>'required|max:100|unique:settings,setting_key',
            'value'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'key.required' => 'The key is required',
            'key.max' => 'Maximum 100 characters allowed for key',
            'key.unique'=>'The key already exist.',
            'value.required' => 'The value is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // INSERT ARRAY
        $insert_array = array(
            'name'=>$request->title,
            'setting_key'=>$request->key,
            'value'=>$request->value,
            'comment'=>$request->comment,
            'setting_type'=>'general',
            'status'=>'enable',
            'created_ip'=>ip2long(\Request::ip()),
            'created_by'=>Auth::guard('manage')->user()->id,
            'created_at'=>Carbon::now(),
            'updated_at' => Carbon::now(),
            'updated_by'=>Auth::guard('manage')->user()->id,
        );
        Setting::insert($insert_array);

        return redirect('/manage/settings')->withSuccess(trans('adminlte::custom.succ_setting_added'));
    }

    public function edit($id){
        $data = array('title'=>"Edit Setting",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('manage/settings/update/'.$id),'frm_id'=>'frm_setting_add');

        $data['setting'] = Setting::where('status','!=','delete')->where('id','=',$id)->limit(1)->first();

        if(!empty($data['setting'])) {
            return view('manage.settings_edit', $data);
        }
        else{
            return redirect('/manage/settings');
        }
    }

    public function update(Request $request,$id){
        // VALIDATION RULE
        $validation_array = array(
            'title'=>'required|max:100',
            'value'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'value.required' => 'The value is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // UPDATE ARRAY
        $update_array = array(
            'name'=>$request->title,
            'value'=>$request->value,
            'comment'=>$request->comment,
            'setting_type'=>'general',
            'status'=>'enable',
            'updated_at' => Carbon::now(),
            'updated_ip' => ip2long(\Request::ip()),
            'updated_by' => auth()->guard('manage')->id(),
        );
        Setting::where('id','=',$id)->update($update_array);

        return redirect('/manage/settings')->withSuccess(trans('adminlte::custom.succ_setting_updated'));
    }
}