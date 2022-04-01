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
use App\UserEducation;
use App\FeedDocument;
use App\UserFeed;
use Session;
use getUserEducationFeed;
class EducationController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'education'; 
        $this->module_type = config('custom_config.user_feed_type')['education']; 
        
        $save_draft = $request->save_draft;
        $this->validation_array = array(
            'name' => ($save_draft == '') ? 'required|min:2|max:200|not_regex:/^[0-9]+$/' : '',
            'my_buddies' => ($save_draft == '') ? 'required|min:2|max:200|not_regex:/^[0-9]+$/' : '', 
            'achievement' => ($save_draft == '') ? 'required|min:1|max:200' : '',
            'start_date'=> ($save_draft == '') ? 'required' : '',
            'description' => ($save_draft == '') ? 'required|min:10' : '',            
            'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
        );        
        
        $this->validation_message = array(
            
            'name.required' => trans('custom.js_required_with_attribute'),
            'name.max' => trans('custom.js_max_lengths_serverside'),
            'name.min' => trans('custom.js_min_lengths_serverside'),
            'name.not_regex' => trans('custom.js_only_number_not_allowed'),

            'my_buddies.required' => "School Mates are required",
            'my_buddies.max' => "Maximum :max characters allowed for colleagues.",
            'my_buddies.min' =>  "Minimum :min characters required for colleagues.",
            'my_buddies.not_regex' => "Only numbers are not allowed for colleagues.",

            'start_date.required' => trans('custom.js_required_with_attribute'),
            'achievement.required' => "Class/Grade is required.",
            'achievement.max' => "Maximum :max characters allowed for Class/Grade.",
            'achievement.min' => "Minimum :min characters required for Class/Grade.",            
            
            'description.required' => trans('custom.js_required_with_attribute'),
            'description.min' => trans('custom.js_min_lengths_serverside'),              
                       
            'file.mimes' => trans('custom.file_mimes'),
            'uploaddoc.*.mimes' => trans('custom.Doc_mimes'),
            'uploaddoc.*.max' => trans('custom.Doc_size'),
        );
    }
    public function index(Request $request)
    {           
        $data = array('title' => 'Education','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>35,'commentTable'=>36);  

        $data['results'] = UserEducation::whereHas('getUserEducationFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)->where('type_id','=',$this->module_type); 
        })->orderBy('start_date','DESC')->get();         
        
        return view('education',$data);            
    }

    public function add(){
        $data = array('title' => 'Add To Your School/College','method'=>'Add','action'=>route('education.insert'),'frn_id'=>'frm_education_add' ,'module'=>$this->main_module);
        return view('add_education', $data);
    }

     public function insert(Request $request)     
     {       
        try{
            $module=$this->main_module;   
            $this->validation_array['uploaddoc'] = 'max:5';
            $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
            $save_draft = $request->save_draft;
            //VALIDATION FOR FILE TYPE IMAGE AND VIDEO
            $file = $request->file('file'); 
            if(!empty($file)){
                $file_type = $file->getClientOriginalExtension();
                $this->validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                $this->validation_message['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
            } 
        
            // CHECK SERVER SIDE VALIDATION        
            $this->validate($request,$this->validation_array,$this->validation_message);

            $exp_start = explode(" ", $request->start_date);
            $actualdate = implode("-",str_replace(',','',$exp_start));
            $exp_end = explode(" ", $request->end_date);
            $actualdate1 = implode("-",str_replace(',','',$exp_end));
            $file = $request->file('file');    
            $file_name='';
            if(!empty($file)){
                $fileExtn = $file->getClientOriginalExtension();
                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_education_big');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    Storage::disk('s3')->put($path, $image,'');
                    Common_function::thumb_storage($path,$thumb_path);
                }else{

                    $tmp_path = config('custom_config.s3_education_video');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = file_get_contents($file);
                    Storage::disk('s3')->put($path, $image,'');
                }
            }
            $insert_feed_array = array(
                'user_id' => Auth::user()->id,
                'type_id' => $this->module_type,
                'like_count' => 0,
                'comment_count' => 0,
                'privacy' => ($request->privacy != '')?$request->privacy:'public',
                'status' => 'enable',
                'is_save_as_draft'=> ($request->save_draft != '')?1 : 0,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            $feedId = UserFeed::create($insert_feed_array);        
            
            $insert_education_array = array(
                'feed_id' => $feedId->id,
                'user_id'=>Auth::user()->id,
                'name' => $request->name,            
                'start_date' => ($actualdate != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL,
                'end_date' => ($actualdate1 != '') ? date_format( date_create($actualdate1), 'Y-m-d') : NULL,
                'my_buddies' => $request->my_buddies,
                'achievements' => $request->achievement,
                'description' => $request->description,
                'file' => $file_name,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            UserEducation::insert($insert_education_array);
            
            $docfile = $request->file('uploaddoc');
            if(!empty($docfile)){
                $insertDoc = array();
                foreach($docfile as $dockey => $docvalue){

                    $docExtn = $docvalue->getClientOriginalExtension();
                    $tmpPath = config('custom_config.s3_feed_document');
                    $tmpPath = str_replace('[id]',$feedId->id,$tmpPath);
                    
                    $fileName = Common_function::fileNameTrimmer( $docvalue->getClientOriginalName());                
                    $paths = $tmpPath.$fileName;

                    if($docExtn == 'png' || $docExtn == 'jpg' || $docExtn == 'jpeg'){
                        $fileType = 'image';
                        $filecontents = Image::make($docvalue)->orientate();
                        $filecontents = $filecontents->stream()->__toString();
                    }else if($docExtn == 'xlsx' || $docExtn == 'xls' || $docExtn == 'ods'){
                        $fileType = 'excel';
                        $filecontents = file_get_contents($docvalue);
                    }else if($docExtn == 'pdf'){
                        $fileType = 'pdf';
                        $filecontents = file_get_contents($docvalue);
                    }else{
                        $fileType = 'doc';
                        $filecontents = file_get_contents($docvalue);
                    }                
        
                    Storage::disk('s3')->put($paths, $filecontents,'');
                    array_push($insertDoc, array(
                        'feed_id' => $feedId->id,
                        'file_type' => $fileType,
                        'file' => $fileName,
                        'created_at'=>Carbon::now(),
                        'created_ip' => ip2long(\Request::ip()),
                    ));
                }
                FeedDocument::insert($insertDoc);
            }      
            if($save_draft == '' ){
                Common_function::PostCreationEmail($feedId->type_id,$feedId->id,$module,'',$feedId->privacy); 
                return redirect()->route('education.index')->withInput()->withSuccess(trans('custom.succ_education_added'));
            }else{
                return redirect('/education/edit/'.$feedId->id )->withSuccess(trans('custom.succ_education_added_draft'));
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
     }

     public function edit($id){
        $data = array('title'=>" Edit Your School/College", 'method'=>'Edit','action'=>url('education/update/'.$id),'frn_id'=>'frm_education_add' ,'module'=>$this->main_module);
        
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                ->where('id','=',$id)
                ->where('status','=','enable')
                ->where('user_id','=',Auth::user()->id)
                ->first();        
                
       if($data['result'] != ''){
            return view('add_education',$data);
       }else{
            return redirect()->route('education.index');
       }
    }

    public function update(Request $request,$id)
    {
        try{
            $save_draft = $request->save_draft;
            $result = UserFeed::where('type_id','=',$this->module_type)
                    ->where('id','=',$id)
                    ->where('status','=','enable')
                    ->where('user_id','=',Auth::user()->id)
                    ->first();        
                    
            if(!empty($result)){
                $totaldocs = FeedDocument::where('feed_id','=',$id)->count();

                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $this->validation_array['uploaddoc'] = 'max:0';
                        $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
                    }
                }
                //VALIDATION FOR FILE TYPE IMAGE AND VIDEO
                $file = $request->file('file'); 
                if(!empty($file)){
                    $file_type = $file->getClientOriginalExtension();
                    $this->validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                    $this->validation_message['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
                } 
                // CHECK SERVER SIDE VALIDATION        
                $this->validate($request,$this->validation_array,$this->validation_message);

                $exp_start = explode(" ", $request->start_date);
                $actualdate = implode("-",str_replace(',','',$exp_start));
                $exp_end = explode(" ", $request->end_date);
                $actualdate1 = implode("-",str_replace(',','',$exp_end));

                $update_feed_array = array(
                    'privacy' => ($request->privacy != '')?$request->privacy:'public',
                    'is_save_as_draft' => ($request->save_draft != '') ?  1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_ip' => ip2long(\Request::ip()),
                );
                UserFeed::where('id','=',$id)->update($update_feed_array);

                $update_education_array = array(            
                    'name' => $request->name,
                    'start_date' => ($actualdate != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL,
                    'end_date' => ($actualdate1 != '') ? date_format( date_create($actualdate1), 'Y-m-d') : NULL,
                    'my_buddies' => $request->my_buddies,
                    'achievements' => $request->achievement,
                    'description' => $request->description,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );
                $file = $request->file('file');
                
                if(!empty($file)){
                    $fileExtn = $file->getClientOriginalExtension();
                    
                    if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){
                        $tmp_path = config('custom_config.s3_education_big');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());

                        $path = $tmp_path.$file_name;
                        $image = Image::make($file)->orientate();
                        $image = $image->stream()->__toString();

                        $thumb_path = str_replace('big','thumb',$path);
                        Storage::disk('s3')->put($path, $image,'');
                        Common_function::thumb_storage($path,$thumb_path);
                    }else{
                        $tmp_path = config('custom_config.s3_education_video');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = file_get_contents($file);
                        Storage::disk('s3')->put($path, $image,'');
                    }

                    if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_education_big').$request->input('oldPhoto'));
                        Storage::disk('s3')->delete(config('custom_config.s3_education_thumb').$request->input('oldPhoto'));
                    }elseif($extn[1] == 'mp4' || $extn[1] == 'ogv' || $extn[1] == 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_education_video').$request->input('oldPhoto'));
                    }

                    $update_education_array['file'] = str_replace($tmp_path,'',$path);
                }
                $docfile = $request->file('uploaddoc');
                if(!empty($docfile)){
                    $insertDoc = array();
                    foreach($docfile as $dockey => $docvalue){

                        $docExtn = $docvalue->getClientOriginalExtension();
                        $tmpPath = config('custom_config.s3_feed_document');
                        $tmpPath = str_replace('[id]',$id,$tmpPath);
                        $fileName = Common_function::fileNameTrimmer( $docvalue->getClientOriginalName());                
                        $paths = $tmpPath.$fileName;

                        if($docExtn == 'png' || $docExtn == 'jpg' || $docExtn == 'jpeg'){
                            $fileType = 'image';
                            $filecontents = Image::make($docvalue)->orientate();
                            $filecontents = $filecontents->stream()->__toString();
                        }else if($docExtn == 'xlsx' || $docExtn == 'xls' || $docExtn == 'ods'){
                            $fileType = 'excel';
                            $filecontents = file_get_contents($docvalue);
                        }else if($docExtn == 'pdf'){
                            $fileType = 'pdf';
                            $filecontents = file_get_contents($docvalue);
                        }else{
                            $fileType = 'doc';
                            $filecontents = file_get_contents($docvalue);
                        }                
            
                        Storage::disk('s3')->put($paths, $filecontents,'');
                        array_push($insertDoc, array(
                            'feed_id' => $id,
                            'file_type' => $fileType,
                            'file' => $fileName,
                            'created_at'=>Carbon::now(),
                            'created_ip' => ip2long(\Request::ip()),
                        ));
                    }
                    FeedDocument::insert($insertDoc);
                }
                UserEducation::where('feed_id','=',$id)->update($update_education_array);     
                if($save_draft == '' ){
                    return redirect()->route('education.index')->withSuccess(trans('custom.succ_education_updated'));
                }else if($request->draft == 2){
                    Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                    return redirect()->route('education.index')->withSuccess(trans('custom.succ_education_updated'));
                }else{
                    return redirect('/education/edit/'.$id )->withSuccess(trans('custom.succ_education_updated_draft'));
                }   
            } else{
                return redirect()->route('education.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
                return redirect()->back()->withInput();
            }
    }

    public function information($id)
     {
        $data = array('title' => 'Education Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>35,'commentTable'=>36);
        
        $data['results'] = UserFeed::where('type_id','=',$this->module_type)
        ->where('id','=',$id)
        ->where('status','=','enable')
        ->where(function ($query)  {
            $query->where('privacy','=','public')
            ->where('is_save_as_draft','!=',1)
            ->orWhere('user_id','=',Auth::user()->id) 
            ->orWhere(function ($q)  {
                $q->where('privacy','=','family')
                    ->WhereRaw('EXISTS ( SELECT * FROM `vg_users` as utbl WHERE utbl.`id` = vg_user_feeds.user_id  AND (SELECT COUNT(id) as tot FROM vg_user_family_trees as tbl1 WHERE tbl1.user_id = '.Auth::user()->id.' AND tbl1.email = utbl.email AND tbl1.status = "enable" AND tbl1.request_status = "accept") > 0 )');
            });
        })->first();
        
        if($data['results'] != ''){
            return view('education_detail', $data);
        }else{
            return redirect()->route('education.index')->withError(trans('custom.error_delete_post'));
        }        
    }

    public function deleteFile(Request $request)
     {       
        $extn = explode(".",$request->file);
        if($request->file != '' && $extn[1] != 'mp4' || $extn[1] != 'ogv' || $extn[1] != 'webm'){
            Storage::disk('s3')->delete(config('custom_config.s3_education_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_education_thumb').$request->file);
        }elseif($extn[1] == 'mp4' || $extn[1] == 'ogv' || $extn[1] == 'webm'){
            Storage::disk('s3')->delete(config('custom_config.s3_education_video').$request->file);
        }
        $update_educationfile_array['file'] = null;
        UserEducation::where('feed_id','=',$request->id)->update($update_educationfile_array);
        
        return response()->json(['code' => '1']);exit;
    }
}

