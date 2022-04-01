<?php

namespace App\Http\Controllers\Manage;

use App\Common_model;
use App\Helpers\Common_function;
use App\Manage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Admins';
    }
    public function index()
    {
        $data = array('title'=>"Profile",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('manage/profile/update/'),'frn_id'=>'frm_admin_edit','profile_view'=>1);

        $data['admin'] = Manage::findOrfail(Auth::guard('manage')->user()->id);
        return view('manage.admins_edit', $data);
    }

    public function update(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'fname' => 'required',
            'lname' => 'required',
            'confirm_password'=>'same:password',
            'file' => 'mimes:jpeg,png,gif,jpg|max:5120'
        );

        $update_array = array(
            'first_name'=>$request->fname,
            'last_name'=>$request->lname,
            'updated_at' => date('Y-m-d h:m:s'),
        );

        if($request->contact!=''){
            $validation_array['contact'] = 'numeric';
            $update_array['contact_number'] = $request->contact;
        }
        if($request->password!=''){
            $validation_array['password'] = 'min:6';
            $update_array['password'] = bcrypt($request->password);
        }

        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array);
		
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
        
        Manage::where('id','=',Auth::guard('manage')->user()->id)
                ->update($update_array);

        return redirect('/manage/admins')->with('success',trans('adminlte::adminlte.succ_profile_update'));
    }
}
