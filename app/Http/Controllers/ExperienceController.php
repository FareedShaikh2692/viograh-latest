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
use App\UserFeed;
use App\UserExperience;
use App\UserExperienceLike;
use App\UserExperienceComment;
use App\FeedDocument;

class ExperienceController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'experience';
        $this->module_type = config('custom_config.user_feed_type')['experiences'];
    }

    public function index(Request $request){
        $data = array('title' => 'Experiences','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>21,'commentTable'=>22);
        $data['experienceCount'] = UserFeed::whereHas('getUserExperience')
                        ->select('id')->where('type_id','=',$this->module_type)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->orderBy('id','DESC')->get();
        $data['results'] = UserFeed::whereHas('getUserExperience')
                        ->where('type_id','=',$this->module_type)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->orderBy('id','DESC')->offset(0)->limit(4)->get();
        $data['offset'] = 4;
        return view('experiences',$data);
    }

    public function add(){
        $data = array('title' => 'Add To Your Experience','method'=>'Add','action'=>route('experience.insert'),'frn_id'=>'frm_experience_add' ,'module'=>$this->main_module);
        return view('add_experience', $data);
    }

    public function insert(Request $request){
        try{
            $module=$this->main_module;         
            $save_draft = $request->save_draft;
            // VALIDATION RULE
            $validation_array = array(
                'title' => ($save_draft == '')  ? 'required|max:200' : '',
                'date' => ($save_draft == '')  ? 'required' : '',
                'description'=> ($save_draft == '')  ? 'required|min:10' : '',            
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,xlsx,xls,txt,ods,docx|max:10240',
                'uploaddoc' => 'max:5',
            );
            $rules = [
                'title.required' => 'The title is required',
                'title.max' => 'Maximum 200 characters allowed for title',
                'date.required' => 'The date is required',
                'description.required' => 'The description is required',
                'description.min' =>  trans('custom.js_min_lengths_serverside'),
                'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',
                'uploaddoc.*.mimes' => 'File should of a file type: jpeg, png, jpg, txt, doc, docx, pdf, xlsx, xls and ods.',
                'uploaddoc.*.max' => 'Documents size should be less than 10MB',
                'uploaddoc.max' => 'Maximum file limit is 5',
            ];
            //VALIDATION FOR FILE TYPE IMAGE AND VIDEO
            $file = $request->file('file'); 
            if(!empty($file)){
                $file_type = $file->getClientOriginalExtension();
                $validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                $rules['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
            }

            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);

            $exp = explode(" ", $request->date);
            $actualdate = implode("-",str_replace(',','',$exp));

            $file = $request->file('file');
            $file_name='';

            if(!empty($file)){

                $fileExtn = $file->getClientOriginalExtension();

                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_experience_big');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    Storage::disk('s3')->put($path, $image,'');
                    Common_function::thumb_storage($path,$thumb_path);
                }else{

                    $tmp_path = config('custom_config.s3_experience_video');
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

            $insert_experience_array = array(
                'feed_id' => $feedId->id,
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'date' => ($request->date != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL,
                'description' => $request->description,
                'file' => $file_name,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            
            UserExperience::insert($insert_experience_array);
            
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
                return redirect('/experience')->withInput()->withSuccess(trans('custom.succ_experience_added'));
            }else{
                return redirect('/experience/edit/'.$feedId->id )->withSuccess(trans('custom.succ_experience_added_draft'));
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
           return redirect()->back()->withInput();
        }
    }

    public function edit($id){
        $data = array('title'=>" Edit Your Experience", 'method'=>'Edit','action'=>url('experience/update/'.$id),'frn_id'=>'frm_experience_edit' ,'module'=>$this->main_module);
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                        ->where('id','=',$id)
                        ->where('status','=','enable')
                        ->where('user_id','=',Auth::user()->id)
                        ->first();
        if(empty($data['result'])){
            return redirect('/experience');
        }else{
            return view('add_experience',$data);
        }
    }

    public function update(Request $request,$id){
        try{
            $result = UserFeed::where('type_id','=',$this->module_type)
                            ->where('id','=',$id)
                            ->where('status','=','enable')
                            ->where('user_id','=',Auth::user()->id)
                            ->first();
            if(!empty($result)){
                $totaldocs = FeedDocument::where('feed_id','=',$id)->count();     
                $save_draft = $request->save_draft;       
                // VALIDATION RULE
                $validation_array = array(
                    'title' => ($save_draft == '') ? 'required|max:200' : '',
                    'description'=>($save_draft == '') ? 'required' : '',
                    'date' => ($save_draft == '') ? 'required':'',                
                    'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,xlsx,txt,xls,ods,docx|max:10240',
                );

                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $this->validation_array['uploaddoc'] = 'max:0';
                        $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
                    }
                }             
                $rules = [
                    'title.required' => 'The title is required',
                    'title.max' => 'Maximum 200 characters allowed for title',
                    'date.required' => 'The date is required',
                    'description.required' => 'The description is required',
                    'description.min' =>  trans('custom.js_min_lengths_serverside'),
                    'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',
                    'uploaddoc.*.mimes' => 'File should of a file type: jpeg, png, txt, jpg, doc, docx, pdf, xlsx, xls and ods.',
                    'uploaddoc.*.max' => 'Documents size should be less than 10MB',
                    'uploaddoc.max' => 'Maximum file limit is 5',
                ];
                //VALIDATION FOR FILE TYPE IMAGE AND VIDEO
                $file = $request->file('file'); 
                if(!empty($file)){
                    $file_type = $file->getClientOriginalExtension();
                    $validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                    $rules['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
                } 

                // CHECK SERVER SIDE VALIDATION
                $this->validate($request,$validation_array, $rules);
                
                $exp = explode(" ", $request->date);
                $actualdate = implode("-",str_replace(',','',$exp));
                
                $update_feed_array = array(
                    'privacy' => ($request->privacy != '')?$request->privacy:'public',
                    'is_save_as_draft' => ($save_draft != '') ?  1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_ip' => ip2long(\Request::ip()),
                );
            
                UserFeed::where('id','=',$id)->update($update_feed_array);
                
                $update_experience_array = array(
                    'title' => $request->title,
                    'description' => $request->description,
                    'date' => ($request->date != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );

                $file = $request->file('file');
                
                if(!empty($file)){
                    $fileExtn = $file->getClientOriginalExtension();
                    
                    if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){
                        $tmp_path = config('custom_config.s3_experience_big');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = Image::make($file)->orientate();
                        $image = $image->stream()->__toString();

                        $thumb_path = str_replace('big','thumb',$path);
                        Storage::disk('s3')->put($path, $image,'');
                        Common_function::thumb_storage($path,$thumb_path);
                    }else{
                        $tmp_path = config('custom_config.s3_experience_video');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = file_get_contents($file);
                        Storage::disk('s3')->put($path, $image,'');
                    }


                    if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_experience_big').$request->input('oldPhoto'));
                        Storage::disk('s3')->delete(config('custom_config.s3_experience_thumb').$request->input('oldPhoto'));
                    }else{
                        Storage::disk('s3')->delete(config('custom_config.s3_experience_video').$request->input('oldPhoto'));
                    }

                    $update_experience_array['file'] = str_replace($tmp_path,'',$path);
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
                UserExperience::where('feed_id','=',$id)->update($update_experience_array);


                if($save_draft == '' ){
                    return redirect('/experience')->withSuccess(trans('custom.succ_experience_updated'));
                }else if($request->draft == 2){
                    Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                    return redirect('/experience')->withSuccess(trans('custom.succ_experience_updated'));
                }else{
                    return redirect('/experience/edit/'.$id )->withSuccess(trans('custom.succ_experience_updated_draft'));
                }

            } else{
                return redirect()->route('experience.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }

    public function loadMoreExperience(Request $request){
        $output = array();

        $offset = $request->id;
        $public_profile_module = $request->public_profile_module;
        $user_id = $public_profile_module ? $request->user_id : Auth::user()->id;
       
        $data['results'] = UserFeed::where('status', '=', 'enable')
                    ->where(function ($query) use ($public_profile_module){
                        if($public_profile_module == 'public-profile'){
                            $query = $query->where('is_save_as_draft','!=','1');
                        }
                    })->where('user_id','=',$user_id)
                    ->where('type_id', '=', $request->type_id)
                    ->orderBy('id','DESC');

        $data['results'] =$data['results']->where(function ($query)  {
            $query->where('privacy','=','public')
            ->orWhere('user_id','=',Auth::user()->id) 
            ->orWhere(function ($q)  {
                $q->where('privacy','=','family')
                    ->WhereRaw('EXISTS ( SELECT * FROM `vg_users` as utbl WHERE utbl.`id` = vg_user_feeds.user_id  AND (SELECT COUNT(id) as tot FROM vg_user_family_trees as tbl1 WHERE tbl1.user_id = '.Auth::user()->id.' AND tbl1.email = utbl.email AND tbl1.status = "enable") > 0 )');
            });
        })->offset($offset)->limit(4)->get();                           

     
        if(!$data['results']->isEmpty())
        {
            $html = view('include.experience-block',$data)->render();

            $op = ($data['results']->count() < 4) ? 0:1;       
            
            return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$offset+4,'output'=>$output,'html'=>$html]);exit;            
        }
        else{
            return response()->json(['code' => '0']);exit;
        }
    }

    public function deleteFile(Request $request){
        $extn = explode(".",$request->file);
        if($request->file != '' && $extn[1] != 'mp4' || $extn[1] != 'ogv' || $extn[1] != 'webm'){
            Storage::disk('s3')->delete(config('custom_config.s3_experience_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_experience_thumb').$request->file);
        }else{
            Storage::disk('s3')->delete(config('custom_config.s3_experience_video').$request->file);
        }
        $update_experiencefile_array['file'] = null;

        UserExperience::where('feed_id','=',$request->id)->update($update_experiencefile_array);
        return response()->json(['code' => '1']);exit;
    }

    public function information($id){
        $data = array('title' => 'Experience Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>21,'commentTable'=>22);
        
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
                        
        if(empty($data['results'])){
            return  redirect()->route('experience.index')->withError(trans('custom.error_delete_post'));
        }else{
            return view('experiences_detail',$data);
        }
    }

}
