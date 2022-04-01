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
use App\MailSetting;
use App\UserNotification;
use App\UserFamilyTree;

class FamilyTreeController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'Family Tree';
    }

    public function index(Request $request){
        $data = array('title' => 'Family Tree','module'=>$this->main_module);
        $data['results'] = UserFamilyTree::where('user_id',Auth::user()->id)->first();
        return view('family_tree',$data);
    }

    public function insert(Request $request){
        //echo"<pre>";print_r($request->all());exit;
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'relation' => 'required',
            'gender' => 'required',
            'contact' => 'nullable|numeric',
            'age' => 'required|numeric|between:0.1,150',
            'image' => 'mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'code' => '0']);

        }else if($request->email == Auth::user()->email){

            return response()->json(['errors'=>trans('custom.tree_email_issue'),'emailError'=> 1,'code' => '0']);

        }else{

            if(empty($request->memberId)){

                $getuser = UserFamilyTree::where('user_id',Auth::user()->id)->first();
                $familyTreeId = $getuser->id;

            }else{

                $familyTreeId = $request->memberId;

            }
             //GENERATE TOKEN
            $token = Common_function::generateToken(20).time().Common_function::generateToken(20); 

            $insert_tree_array = array(
                'user_id' => Auth::user()->id,
                'family_tree_id' => $familyTreeId,
                'name' => $request->name,
                'email' => ($request->email != '')?$request->email:'',
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->contact,
                'is_alive' => ($request->not_alive == '')?0:$request->not_alive,
                'relationship' => config('custom_config.user_relation_type')[$request->relation],
                'token' => $token,
                'request_status' => 'pending',
                'status' => 'enable',
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
          
            $url = '';
            $file = $request->file('image');

            if(!empty($file)){

                $fileExtn = $file->getClientOriginalExtension();

                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_family_tree');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    
                    Storage::disk('s3')->put($path, $image,'public');
                }

                $insert_tree_array['image'] = str_replace($tmp_path,'',$path);

                $url = Common_function::get_public_s3_file(config('custom_config.s3_family_tree'),$file_name);
            }
         
            $insertedId = UserFamilyTree::create($insert_tree_array);
            
            if($request->email != ''){
                try{
                    // GET SITE EMAIL
                    $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'user-family-tree')->first();
                    // parameter for mail template and function to send
                    $msg = $mail_temp['content'];
                    
                    $msg = str_replace('[USER_NAME]', ucfirst($request->name), $msg);
                    $msg = str_replace('[SITE_URL]', url(''), $msg);
                    $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
                    $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
                    $msg = str_replace('[YEAR]', date('Y'), $msg);
                    $msg = str_replace('[CONTACT_EMAIL]', config('custom_config.settings.site_email'), $msg);
                    $msg = str_replace('[SENTENCE]', Auth::user()->first_name.' '.Auth::user()->last_name .' added you as a Family member. If you accept the request then ' .Auth::user()->first_name.' '.Auth::user()->last_name. ' will be able to show your post with family privacy otherwise he/she can not see your family privacy post. You can accept or reject request using button below.', $msg);
                    
                    $msg = str_replace('[ACCEPT_LINK]', url('/request_status/'.'?id='.$insertedId->id.'&token='.$insertedId->token.'&action='.'accept'), $msg);
                    $msg = str_replace('[REJECT_LINK]', url('/request_status/'.'?id='.$insertedId->id.'&token='.$insertedId->token.'&action='.'reject'), $msg);
                    $msg = str_replace('[SIGNUP_LINK]', url('signup'), $msg);
                    
                    $email_data['to_email'] = $request->email;
                    $email_data['subject'] = $mail_temp['subject'].' by '.ucfirst(Auth::user()->first_name).'.';
                    $email_data['from_email'] = $mail_temp['from_email'];
                    $email_data['message'] = $msg;                      
                    Mail::send([], [], function ($message) use ($email_data) {
                    $message->to($email_data['to_email'])
                        ->from($email_data['from_email'], config('custom_config.settings.site_name'))
                        ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                        ->setBody($email_data['message'], 'text/html');
                    });

                }catch(\Exception $e){      
                         
                }
                $userexists = User::where('email',$request->email)->where('status','enable')->first();

                if(!empty($userexists)){

                    $notification_data = array(
                        'user_id' =>  $userexists->id,
                        'type_id' => 39,
                        'from_id' => Auth::user()->id,
                        'unique_id' => $insertedId->id,
                        'message' => ' added you as a Family member',
                        'created_at' => Carbon::now(),
                        'created_ip' => ip2long(\Request::ip()),
                    );

                    UserNotification::insert($notification_data);
                    
                }
            }

            return response()->json(['code' => '1','btnId' => $insertedId->id,'image' => $url]);exit;
        }
    }
    public function insertTree(Request $request){

        User::where('id','=',Auth::user()->id)
                ->update(['tree' => $request->tree]);

        return response()->json(['code' => '1']);exit;
    }

    public function removeTree(Request $request){

        $update_tree_array = array(
            'status' => 'delete',
            'updated_ip' => ip2long(\Request::ip()),
            'updated_at' => Carbon::now(),
        );

        $query = UserFamilyTree::where('user_id',Auth::user()->id);

        if($request->ids != ''){

            $ids = explode(",",$request->ids);
            $query = $query->whereIn('id',$ids);

        }else{

            $query = $query->where('id','=',$request->memberId);

        }

        $query = $query->update($update_tree_array);

        User::where('id','=',Auth::user()->id)
                ->update(['tree' => $request->tree]);

        return response()->json(['code' => '1']);exit;
    }

    public function edit(Request $request){

        $data['results'] = UserFamilyTree::where('id',$request->memberId)->first();
        $data['rlnship'] = config('custom_config.fmailylist_relation_type')[$data['results']->relationship];
        
        return response()->json(['code' => '1','data' => $data]);exit;
    }

    public function update(Request $request){
        // echo "<br>";
        // print_r($request->all());exit;
        $getfamilydetail = UserFamilyTree::where('id','=',$request->memberId)->first();
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'contact' => 'nullable|numeric',
            'age' => 'required|numeric|between:0.1,150',
            'image' => 'mimes:jpeg,png,jpg|max:5120',
         ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(),'code' => '0']);
        }else{

            $update_tree_array = array(
                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'email' => $request->email,
                'phone_number' => $request->contact,
                'is_alive' => ($request->not_alive == '')?0:$request->not_alive,
                'status' => 'enable',
                'created_ip' => ip2long(\Request::ip()),
                'updated_ip' => ip2long(\Request::ip()),
                'updated_at' => Carbon::now(),
            );
            // echo "<pre>";
            // print_r($update_tree_array);exit;

            $file = $request->file('image');

            $url = '';
            //CHECK IF NEW IMAGE EXISTS OR NOT
            if(!empty($file)){

                $fileExtn = $file->getClientOriginalExtension();

                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_family_tree');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    
                    Storage::disk('s3')->put($path, $image,'public');
                }

                $update_tree_array['image'] = str_replace($tmp_path,'',$path);

                $url = Common_function::get_public_s3_file(config('custom_config.s3_family_tree'),$file_name);
                if($request->storedImage != ''){
                    Storage::disk('s3')->delete(config('custom_config.s3_family_tree').$request->storedImage);
                }
            }elseif($request->storedImage != ''){
                $url = Common_function::get_public_s3_file(config('custom_config.s3_family_tree'),$request->storedImage);
            }else{
                $url = ($request->gender == 'male')? url('images/userdefault.jpg'):url('images/female-user.jpg');
            }
            UserFamilyTree::where('id','=',$request->memberId)->update($update_tree_array);

            $data['result'] = array(
                'id' => $request->memberId,
                'image' => $url,
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
                'gender' => $request->gender,
                'phone_number' => $request->contact,
                'is_alive' => ($request->not_alive == '')?'':'Late ',
                'relationship' => config('custom_config.fmailylist_relation_type')[$getfamilydetail->relationship] ,
            );
            return response()->json(['code' => '1','data' => $data]);exit;
        }
    }

    public function updateTree(Request $request){

        User::where('id','=',Auth::user()->id)
                ->update(['tree' => $request->tree]);

        return response()->json(['code' => '1']);exit;
    }
}
