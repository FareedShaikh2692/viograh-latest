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
use App\Rules\CommaSeprater;

class MyselfController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'myself';  
            
    }

    public function index(Request $request)
     {
        $data = array('title' => 'About', 'module'=>$this->main_module);       
        $data['results'] = User::where('status','=','enable')
                ->where('id','=',Auth::user()->id)          
                ->first();                  
        
        return view('myself', $data);       
    }
    // ABOUT PAGE OF MYSELF
    public function edit(Request $request){
        
        $data = array('title' => 'Edit Details', 'method'=>'Add','action'=>route('myself.insert'),'frn_id'=>'frm_myself_add' ,'module'=>$this->main_module);
        $data['result'] = User::where('id','=',Auth::user()->id)          
                         ->first(); 
        if($data['result'] != ''){
            return view('add_myself', $data);
        }else{
            return redirect()->route('myself.index');
        }       
    } 

    public function insert(Request $request){
      
        // VALIDATION RULE
        $validation_array = array(     
            'file' => 'max:10240|mimes:jpeg,png,jpg|nullable', 
            'essence_of_life' => 'min:2|max:200|nullable',
            'biography' => 'nullable|min:2|not_regex:/^[0-9]+$/',           
            'place_of_birth' => 'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'favourite_movie'=>'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'favourite_song' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'favourite_book' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'favourite_eating_joints' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'hobbies' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'food' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'role_model' =>'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'car' => 'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'brand' => 'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'tv_shows' => 'nullable|min:2|max:400|commasaperator',
            'actors' =>'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'sports_person' => 'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'politician' => 'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',
            'diet' => 'nullable|min:2|max:400|not_regex:/^[0-9]+$/|commasaperator',
            'zodiac_sign' =>'nullable|min:2|max:200|not_regex:/^[0-9]+$/|commasaperator',          
            
        );
        $rules = [
            'file.max' => trans('custom.file_max_size_image'),
            'biography.min' => trans('custom.js_min_lengths_serverside'),
            'biography.not_regex' => trans('custom.js_only_number_not_allowed'),            

            'place_of_birth.max' => trans('custom.js_max_lengths_serverside'),
            'place_of_birth.min' => trans('custom.js_min_lengths_serverside'),
            'place_of_birth.not_regex' => trans('custom.js_only_number_not_allowed'),       
            // 'place_of_birth.regex' => trans('custom.5_comma_val_message'),
            'place_of_birth.commasaperator' => trans('custom.5_comma_val_message'),

            'favourite_movie.max' => trans('custom.js_max_lengths_serverside'),
            'favourite_movie.min' => trans('custom.js_min_lengths_serverside'),
            'favourite_movie.not_regex' => trans('custom.js_only_number_not_allowed'),
            'favourite_movie.commasaperator' => trans('custom.5_comma_val_message'),

            'favourite_song.max' => trans('custom.js_max_lengths_serverside'),
            'favourite_song.min' => trans('custom.js_min_lengths_serverside'),
            'favourite_song.not_regex' => trans('custom.js_only_number_not_allowed'),
            'favourite_song.commasaperator' => trans('custom.5_comma_val_message'),

            'favourite_book.max' => trans('custom.js_max_lengths_serverside'),
            'favourite_book.min' => trans('custom.js_min_lengths_serverside'),
            'favourite_book.not_regex' => trans('custom.js_only_number_not_allowed'),
            'favourite_book.commasaperator' => trans('custom.5_comma_val_message'),

            'favourite_eating_joints.max' => trans('custom.js_max_lengths_serverside'),
            'favourite_eating_joints.min' => trans('custom.js_min_lengths_serverside'),
            'favourite_eating_joints.not_regex' => trans('custom.js_only_number_not_allowed'),
            'favourite_eating_joints.commasaperator' => trans('custom.5_comma_val_message'),

            'hobbies.max' => trans('custom.js_max_lengths_serverside'),
            'hobbies.min' => trans('custom.js_min_lengths_serverside'),
            'hobbies.not_regex' => trans('custom.js_only_number_not_allowed'),
            'hobbies.commasaperator' => trans('custom.5_comma_val_message'),

            'food.max' => trans('custom.js_max_lengths_serverside'),
            'food.min' => trans('custom.js_min_lengths_serverside'),
            'food.not_regex' => trans('custom.js_only_number_not_allowed'),
            'food.commasaperator' => trans('custom.5_comma_val_message'),

            'role_model.max' => trans('custom.js_max_lengths_serverside'),
            'role_model.min' => trans('custom.js_min_lengths_serverside'),
            'role_model.not_regex' => trans('custom.js_only_number_not_allowed'),
            'role_model.commasaperator' => trans('custom.5_comma_val_message'),

            'car.max' => trans('custom.js_max_lengths_serverside'),
            'car.min' => trans('custom.js_min_lengths_serverside'),
            'car.not_regex' => trans('custom.js_only_number_not_allowed'),
            'car.commasaperator' => trans('custom.5_comma_val_message'),

            'brand.max' => trans('custom.js_max_lengths_serverside'),
            'brand.min' => trans('custom.js_min_lengths_serverside'),
            'brand.not_regex' => trans('custom.js_only_number_not_allowed'),
            'brand.commasaperator' => trans('custom.5_comma_val_message'),

            'tv_shows.max' => trans('custom.js_max_lengths_serverside'),
            'tv_shows.min' => trans('custom.js_min_lengths_serverside'),
            'tv_shows.not_regex' => trans('custom.js_only_number_not_allowed'),
            'tv_shows.commasaperator' => trans('custom.5_comma_val_message'),

            'actors.max' => trans('custom.js_max_lengths_serverside'),
            'actors.min' => trans('custom.js_min_lengths_serverside'),
            'actors.not_regex' => trans('custom.js_only_number_not_allowed'),
            'actors.commasaperator' => trans('custom.5_comma_val_message'),

            'sports_person.max' => trans('custom.js_max_lengths_serverside'),
            'sports_person.min' => trans('custom.js_min_lengths_serverside'),
            'sports_person.not_regex' => trans('custom.js_only_number_not_allowed'),
            'sports_person.commasaperator' => trans('custom.5_comma_val_message'),

            'politician.max' => trans('custom.js_max_lengths_serverside'),
            'politician.min' => trans('custom.js_min_lengths_serverside'),
            'politician.not_regex' => trans('custom.js_only_number_not_allowed'),
            'politician.commasaperator' => trans('custom.5_comma_val_message'),

            'diet.max' => trans('custom.js_max_lengths_serverside'),
            'diet.min' => trans('custom.js_min_lengths_serverside'),
            'diet.not_regex' => trans('custom.js_only_number_not_allowed'),
            'diet.commasaperator' => trans('custom.5_comma_val_message'),

            'zodiac_sign.max' => trans('custom.js_max_lengths_serverside'),
            'zodiac_sign.min' => trans('custom.js_min_lengths_serverside'),
            'zodiac_sign.not_regex' => trans('custom.js_only_number_not_allowed'), 
            'zodiac_sign.commasaperator' => trans('custom.5_comma_val_message'),
        ];
        
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array, $rules);

        $update_usermyself_array = array(
            //  'banner_image' => $file_name,
              'essence_of_life' => $request->essence_of_life,
              'biography' => $request->biography,            
              'place_of_birth' => $request->place_of_birth,                        
              'favourite_movie' => $request->favourite_movie,
              'favourite_song' => $request->favourite_song,
              'favourite_book' => $request->favourite_book,
              'favourite_eating_joints' => $request->favourite_eating_joints,
              'hobbies' => $request->hobbies,
              'food' => $request->food,
              'role_model' => $request->role_model,
              'car' => $request->car,
              'brand' => $request->brand,
              'tv_shows' => $request->tv_shows,
              'actors' => $request->actors,
              'sports_person' => $request->sports_person,
              'politician' => $request->politician,
              'diet' => $request->diet,
              'zodiac_sign' => $request->zodiac_sign,
              'updated_ip' => ip2long(\Request::ip()),            
              'updated_at' => Carbon::now(),
        );
        $file = $request->file('file');
        $file_name='';
        
        if(!empty($file)){
            $fileExtn = $file->getClientOriginalExtension();
            $tmp_path = config('custom_config.s3_banner_big');
            $file_name = Common_function::fileNameTrimmer($file->hashName());
            $path = $tmp_path.$file_name;
          //  $image = Image::make($file)->orientate();

            $img = $request->oldPhoto;
            
            $img = str_replace('data:image/png;base64,', '', $img);
            $cropped_image_file = str_replace(' ', '+', $img);
           
            $image = Image::make($cropped_image_file)->orientate();

            $image = $image->stream()->__toString();
            $thumb_path = str_replace('big','thumb',$path);
            Storage::disk('s3')->put($path, $image,'');
            Common_function::crop_thumb_storage_bannerimg($path,$thumb_path,$cropped_image_file);
            if($request->input('oldimage') != ''){
                Storage::disk('s3')->delete(config('custom_config.s3_banner_big').$request->input('oldimage'));
                Storage::disk('s3')->delete(config('custom_config.s3_banner_thumb').$request->input('oldimage'));
            }
           
            $update_usermyself_array['banner_image'] = str_replace($tmp_path,'',$path);
        }    
        User::where('id','=',Auth::user()->id)
            ->update($update_usermyself_array);
        return redirect()->route('myself.index')->withInput()->withSuccess(trans('custom.succ_myself_updated'));
    }   
    public function deleteFile(Request $request){
        $extn = explode(".",$request->banner_image);
        Storage::disk('s3')->delete(config('custom_config.s3_diary_big').$request->banner_image);
        Storage::disk('s3')->delete(config('custom_config.s3_diary_thumb').$request->banner_image);
        $update_myselffile_array['banner_image'] = null;

        User::where('id','=',Auth::user()->id)->update($update_myselffile_array);
        return response()->json(['code' => '1']);exit;
    }
   
}