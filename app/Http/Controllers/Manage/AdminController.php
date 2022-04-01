<?php

namespace App\Http\Controllers\Manage;

use App\Helpers\Common_function;
use App\Mail\Admin_welcome;
use App\Manage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Admin';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Admins",'main_module'=>$this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = Manage::where('status','!=','delete')
            ->where('id','!=',Auth::guard('manage')->user()->id);

        if($data['search']!=''){

            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->orWhere('first_name','LIKE',$data['search'].'%')
                ->orWhere('last_name','LIKE',$data['search'].'%')
                ->orWhere('email', 'LIKE', $data['search'].'%');});  
        }
        
        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'));
        $data['tbl'] = Common_function::encrypt('manages');

        $data['results']->appends(['search'=>$data['search']]);
        return view('manage.admins',$data);
    }

    public function add(){
        $data = array('title'=>" Add Admin",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('manage/admins/insert'),'frn_id'=>'frm_admin_add');
        return view('manage.admins_edit', $data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'contact' =>'nullable|numeric',
            'email'=>'required|email|max:100',
            'password'=>'required|min:6|max:255',
            'confirm_password'=>'same:password',
            'file' => 'mimes:jpeg,png,gif,jpg|max:5120'
        );
        $rules = [
            'fname.required' => 'The first name is required',
            'fname.max' => 'Maximum 50 characters allowed for first name',
            'lname.required' => 'The last name is required',
            'lname.max' => 'Maximum 50 characters allowed for last name',
            'email.required' => 'The email is required',
            'email.email' => 'Enter valid email address',
            'email.max' => 'Maximum 100 characters allowed for email',
            'password.required' => 'The password is required',
            'password.min' => 'Minimum 6 characters required for password',
            'password.max' => 'Maximum 255 characters allowed for password',
            'confirm_password.same' => 'Confirm password must be same as Password',
            'contact.numeric' => 'The contact number allows only number',
            'file.max' => 'Display photo must be less than or equals to 5MB.'
        ];

        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        $check_email_exists = Manage::where([['email','=',$request->email],['status','!=','Delete']])->first();
        if(empty($check_email_exists)){
            // INSERT ARRAY
            $insert_array = array(
                'first_name' => ucwords($request->fname),
                'last_name' => ucwords($request->lname),
                'email' => $request->email,
                'contact_number' => $request->contact,
                'admin_type' => $request->admin_type,
                'password' => bcrypt($request->password),
                'status' => 'enable',
                'created_by' => auth()->guard('manage')->id(),
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => auth()->guard('manage')->id(),
            );

            $file = $request->file('file');
            
            if(!empty($file)){
                $tmp_path = config('custom_config.s3_admin_big');
                $file_name = Common_function::fileNameTrimmer($file->hashName());
                $path = $tmp_path.$file_name;
                $image = Image::make($file)->orientate();
                $image = $image->stream()->__toString();
    
                Storage::disk('s3')->put($path, $image,'');
    
                $thumb_path = str_replace('big','thumb',$path);
                Common_function::thumb_storage($path,$thumb_path);
    
                
                $insert_array['profile_photo'] = str_replace($tmp_path,'',$path);
            }

            Manage::insert($insert_array);
        }else{
            return redirect('/manage/admins/add')->withInput()->withError(trans('adminlte::custom.email_exists'));
        }

        return redirect('/manage/admins')->withInput()->withSuccess(trans('adminlte::adminlte.succ_admin_added'));
    }

    public function edit($id){
        $data = array('title'=>" Edit Admin", 'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('manage/admins/update/'.$id),'frn_id'=>'frm_admin_edit');

        $data['admin'] = Manage::where('status','!=','delete')
            ->where('id','!=',Auth::guard('manage')->user()->id)
            ->where('id','=',$id)
            ->limit(1)->first();

        if(!empty($data['admin'])) {
            return view('manage.admins_edit', $data);
        }
        else{
            return redirect('/manage/admins');
        }
    }

    public function update(Request $request,$id){
        // VALIDATION RULE
        $validation_array = array(
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'confirm_password'=>'same:password',
            'file' => 'mimes:jpeg,png,gif,jpg|max:5120'
        );

        // UPDATE ARRAY
        $update_array = array(
            'first_name'=>ucwords($request->fname),
            'last_name'=>ucwords($request->lname),
            'updated_by' => auth()->guard('manage')->id(),
            'updated_ip' => ip2long(\Request::ip()),
            'updated_at' => Carbon::now(),            
        );
        if(Auth::guard('manage')->user()->admin_type=='super_admin'){
            $update_array['admin_type'] = $request->admin_type;
        }

        if($request->contact!=''){
            $validation_array['contact'] = 'numeric';
            $update_array['contact_number'] = $request->contact;
        }
        if($request->password!=''){
            $validation_array['password'] = 'min:6|max:255';
            $update_array['password'] = bcrypt($request->password);
        }

        $rules = [
            'fname.required' => 'The first name is required',
            'fname.max' => 'Maximum 50 characters allowed for first name',
            'lname.required' => 'The last name is required',
            'lname.max' => 'Maximum 50 characters allowed for last name',
            'password.required' => 'The password is required',
            'password.min' => 'Minimum 6 characters required for password',
            'password.max' => 'Maximum 255 characters allowed for password',
            'confirm_password.same' => 'Confirm password must be same as Password',
            'contact.numeric' => 'The contact number allows only number',
            'file.max' => 'Display photo must be less than or equals to 5MB.'
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);
            
        $file = $request->file('file');
            
        // CHECK FOR EMPTY
        if(!empty($file)){
            $tmp_path = config('custom_config.s3_admin_big');
            $file_name = Common_function::fileNameTrimmer($file->hashName());
            $path = $tmp_path.$file_name;

            $image = Image::make($file)->orientate();
            $image = $image->stream()->__toString();

            Storage::disk('s3')->put($path, $image,'public');

            $thumb_path = str_replace('big','thumb',$path);
            Common_function::thumb_storage($path,$thumb_path);

            if($request->input('oldPhoto') != ''){
                Storage::disk('s3')->delete(config('custom_config.s3_admin_big').$request->input('oldPhoto'));
                Storage::disk('s3')->delete(config('custom_config.s3_admin_thumb').$request->input('oldPhoto'));
            }
            $update_array['profile_photo'] = str_replace($tmp_path,'',$path);
        }

        Manage::where('id','=',$id)
                ->update($update_array);

        return redirect('/manage/admins')->with('success',trans('adminlte::adminlte.succ_admin_update'));
    }

    public function information($id){
        $data = array('title'=>"Admin Information", 'main_module'=>$this->main_module,'method'=>'Information');

        $data['admin'] = Manage::where('status','!=','delete')
            ->where('id','!=',Auth::guard('manage')->user()->id)
            ->where('id','=',$id)
            ->limit(1)->first();

        if(!empty($data['admin'])) {
            return view('manage.admininfo', $data);
        }
        else{
            return redirect('/manage/admins');
        }
    }
}
