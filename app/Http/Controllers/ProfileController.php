<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\User;
use App\Countries;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'profile';  
            
    }

    public function index(Request $request){
        $data = array('title' => 'Profile', 'module'=>'profile');
        
        $data['results'] = User::where('status','=','enable') 
            ->where('id','=',Auth::user()->id)       
            ->first();         
        return view('profile',$data);
    }
    public function edit(){
        $id=Auth::user()->id;
        $data = array('title'=>" Edit Profile", 'method'=>'Edit','action'=>url('profile/update/'.$id),'frn_id'=>'frm_profile_edit' ,'module'=>$this->main_module);
        $data['result'] = User::where('id','=',Auth::user()->id)          
                        ->first();
        
        $data['currency_data'] = Countries::where('status','=','enable')->get();
        
        $data['date'] = Carbon::now();      
        return view('my_profile',$data);
    }
    public function update(Request $request,$id)
    {   
        $check_email_exists = User::where([['email','=',$request->email],['status','=','enable']])->first();

        // VALIDATION RULE
        $validation_array = array(
            'first_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|max:50|regex:/^[a-zA-Z\s]+$/',
            'phone_number_profile' => 'required|min:5|max:15|gt:0',
            'birth_date' => 'required',
            'gender' => 'required',
            'address' =>'max:400|nullable',
            'places_lived' =>'max:100|nullable',
            'profession' =>'min:2|max:200|nullable',
            'about_me' =>'max:400|nullable',
            'blood_group' => ['nullable'],
            'email'=>'required|email|max:100',
            'dial_code' => 'required|min:2|not_in:0|max:7|regex:/^[\d\(\)\-+]+$/',//(\d{1}\-)?(\d{1,6})
            'file' => 'mimes:jpeg,png,jpg|max:10240|nullable',
        );
        $rules=[
            'first_name.regex' => 'Only Characters are allowed in First Name Field', 
            'last_name.regex' => 'Only Characters are allowed in Last Name Field', 
            'email.required' => 'The email is required',
            'phone_number_profile.required' => 'The phone number field is required',
            'phone_number_profile.min' => 'Minimum 5 numerics allowed for phone number',
            'phone_number_profile.max' => 'Maximum 15 numierics allowed for phone number',
            'email.email' => 'Enter valid email address',
            'email.max' => 'Maximum 100 characters allowed for email',
            'dial_code' => 'Country code is not valid'
        ];
        $this->validate($request,$validation_array,$rules);

        $update_profile_array = array(
            'id'=>Auth::user()->id,
            'first_name' => $request->first_name,
            'last_name' =>  $request->last_name,
            'birth_date' => date_format(date_create($request->birth_date), 'Y/m/d ' ),
            'email' => $request->email,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number_profile,
            'places_lived' => $request->places_lived,
            'profession' => $request->profession,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'about_me' => $request->about_me,
            'dial_code' => $request->dial_code,
            'currency_id' => $request->currency_id,
            'created_ip' => ip2long(\Request::ip()),
            'updated_at' => Carbon::now(),
        );
       
        $file = $request->file('file');
        
        if(!empty($file)){
            $tmp_path = config('custom_config.s3_user_big');
            $file_name = Common_function::fileNameTrimmer($file->hashName());
            $path = $tmp_path.$file_name;

            $img = $request->oldPhoto;
          
            $img = str_replace('data:image/png;base64,', '', $img);
            $cropped_image_file = str_replace(' ', '+', $img);
           
            $image = Image::make($cropped_image_file)->orientate();
            $image = $image->stream()->__toString();

            Storage::disk('s3')->put($path, $image,'');

            $thumb_path = str_replace('big','thumb',$path);
            Common_function::crop_thumb_storage($path,$thumb_path,$cropped_image_file);

            if($request->input('oldimage') != ''){
                Storage::disk('s3')->delete(config('custom_config.s3_user_big').$request->input('oldimage'));
                Storage::disk('s3')->delete(config('custom_config.s3_user_thumb').$request->input('oldimage'));
            }

            $update_profile_array['profile_image'] = str_replace($tmp_path,'',$path);
        }
        /* if($request->image == 'default-image'){
            $update_profile_array['profile_image'] = NULL;
        } */
        
        if(!empty($check_email_exists)) {
            if($request->email ==  Auth::user()->email){
                User::where('id','=',$id)->update($update_profile_array);  
                return redirect('/profile')->withSuccess(trans('custom.succ_profile_updated'));  
            }else{
                return redirect()->route('profile.edit')->withInput()->withError(trans('custom.error_profile_email'));
            }
        } else{
            User::where('id','=',$id)->update($update_profile_array);
            return redirect('/profile')->withSuccess(trans('custom.succ_profile_updated'));
        }
    }

}
