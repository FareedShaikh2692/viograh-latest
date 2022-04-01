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
use App\UserSpiritualJourney;
use App\UserSpiritualJourneyLike;
use App\UserSpiritualJourneyComment;
use App\FeedDocument;

class SpiritualJourneyController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'spiritual-journey';
        $this->module_type = config('custom_config.user_feed_type')['spiritual_journeys'];
    }

    public function index(Request $request){
        $data = array('title' => 'Spiritual Journeys','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>27,'commentTable'=>28);
        $data['spiritualJourneyCount'] = UserFeed::whereHas('getUserSpiritualJourney')
                        ->select('id')->where('type_id','=',$this->module_type)
                        ->where('status','=','enable')
                        ->where('user_id','=',Auth::user()->id)
                        ->orderBy('id','DESC')->get();
        $data['results'] = UserFeed::whereHas('getUserSpiritualJourney')
                        ->where('type_id','=',$this->module_type)
                        ->where('status','=','enable')
                        ->where('user_id','=',Auth::user()->id)
                        ->orderBy('id','DESC')->offset(0)->limit(4)->get();
        $data['offset'] = 4;
        return view('spiritual_journeys',$data);
    }

    public function add(){
        $data = array('title' => 'Add To Your Spiritual Journey','method'=>'Add','action'=>route('spiritual-journey.insert'),'frn_id'=>'frm_spiritual_journey_add' ,'module'=>$this->main_module);
        return view('add_spiritual_journey', $data);
    }

    public function insert(Request $request){
        try{
            $module=$this->main_module; 
            $save_draft = $request->save_draft;
            // VALIDATION RULE
            $validation_array = array(
                'when_started' => ($save_draft == '') ? 'required|max:200' : '',
                'why_started' => ($save_draft == '') ?  'required|max:200' : '',
                'who_influenced' =>  ($save_draft == '') ? 'required|max:200' : '',
                'practice' => ($save_draft == '') ? 'required|max:200' : '',
                'benefit' => ($save_draft == '') ? 'required|max:200' : '',
                'description'=>($save_draft == '') ? 'required|min:10' : '',            
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                'uploaddoc' => 'max:5',
            );
            $rules = [
                'when_started.required' => 'When it started is required',
                'when_started.max' => 'Maximum 200 characters allowed for When it started',
                'why_started.required' => 'Why you started it is required',
                'why_started.max' => 'Maximum 200 characters allowed for Why you started it',
                'who_influenced.required' => 'Who influenced it is required',
                'who_influenced.max' => 'Maximum 200 characters allowed for Who influenced it',
                'practice.required' => 'What do you practice is required',
                'practice.max' => 'Maximum 200 characters allowed for What do you practice',
                'benefit.required' => 'What benefits you got is required',
                'benefit.max' => 'Maximum 200 characters allowed for What benefits you got',
                'description.required' => 'The description is required',
                'description.min' => trans('custom.js_min_lengths_serverside'),               
                'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',
                'uploaddoc.*.mimes' => 'Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
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

            $file = $request->file('file');
            $file_name='';
            
            if(!empty($file)){

                $fileExtn = $file->getClientOriginalExtension();

                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_spiritual_journey_big');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    Storage::disk('s3')->put($path, $image,'');
                    Common_function::thumb_storage($path,$thumb_path);
                }else{

                    $tmp_path = config('custom_config.s3_spiritual_journey_video');
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

            $insert_spiritual_journey_array = array(
                'feed_id' => $feedId->id,
                'user_id' => Auth::user()->id,
                'when_started' => $request->when_started,
                'why_started' => $request->why_started,
                'who_influenced' => $request->who_influenced,
                'practice' => $request->practice,
                'benefit' => $request->benefit,
                'description' => $request->description,
                'file' => $file_name,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );            
            UserSpiritualJourney::insert($insert_spiritual_journey_array);
            
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
                return redirect('/spiritual-journey')->withInput()->withSuccess(trans('custom.succ_spiritual_journey_added'));
            }else{
                return redirect('/spiritual-journey/edit/'.$feedId->id )->withSuccess(trans('custom.succ_spiritual_journey_added_draft'));
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }

    public function edit($id){
        $data = array('title'=>" Edit Your Spiritual Journey", 'method'=>'Edit','action'=>url('spiritual-journey/update/'.$id),'frn_id'=>'frm_spiritual_journey_edit' ,'module'=>$this->main_module);
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                        ->where('id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->first();
        if(empty($data['result'])){
            return redirect('/spiritual-journey');
        }else{
            return view('add_spiritual_journey',$data);
        }
    }

    public function update(Request $request,$id){
        try{
            $result = UserFeed::where('type_id','=',$this->module_type)
            ->where('id','=',$id)
            ->where('user_id','=',Auth::user()->id)
            ->where('status','=','enable')
            ->first();
            if(!empty($result)){
                $totaldocs = FeedDocument::where('feed_id','=',$id)->count();
                $save_draft = $request->save_draft;     
                // VALIDATION RULE
                $validation_array = array(
                    'when_started' =>($save_draft == '') ? 'required|max:200' : '',
                    'why_started' => ($save_draft == '') ?'required|max:200' : '',
                    'who_influenced' => ($save_draft == '') ? 'required|max:200' : '',
                    'practice' => ($save_draft == '') ? 'required|max:200' : '',
                    'benefit' => ($save_draft == '') ? 'required|max:200' : '',
                    'description'=>($save_draft == '') ? 'required|min:10' : '',                
                    'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,xlsx,xls,txt,ods,docx|max:10240',
                );

                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $this->validation_array['uploaddoc'] = 'max:0';
                        $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
                    }
                }
                
                $rules = [
                    'when_started.required' => 'When it started is required',
                    'when_started.max' => 'Maximum 200 characters allowed for When it started',
                    'why_started.required' => 'Why you started it is required',
                    'why_started.max' => 'Maximum 200 characters allowed for Why you started it',
                    'who_influenced.required' => 'Who influenced it is required',
                    'who_influenced.max' => 'Maximum 200 characters allowed for Who influenced it',
                    'practice.required' => 'What do you practice is required',
                    'practice.max' => 'Maximum 200 characters allowed for What do you practice',
                    'benefit.required' => 'What benefits you got is required',
                    'benefit.max' => 'Maximum 200 characters allowed for What benefits you got',
                    'description.required' => 'The description is required',
                    'description.min' => trans('custom.js_min_lengths_serverside'),
                    'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',
                    'uploaddoc.*.mimes' => 'Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
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

                $update_feed_array = array(
                    'privacy' => ($request->privacy != '')?$request->privacy:'public',
                    'is_save_as_draft' => ($request->save_draft != '') ?  1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_ip' => ip2long(\Request::ip()),
                );
                UserFeed::where('id','=',$id)->update($update_feed_array);
                
                $update_spiritual_journey_array = array(
                    'when_started' => $request->when_started,
                    'why_started' => $request->why_started,
                    'who_influenced' => $request->who_influenced,
                    'practice' => $request->practice,
                    'benefit' => $request->benefit,
                    'description' => $request->description,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );

                $file = $request->file('file');
                
                if(!empty($file)){
                    $fileExtn = $file->getClientOriginalExtension();
                    
                    if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                        $tmp_path = config('custom_config.s3_spiritual_journey_big');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = Image::make($file)->orientate();
                        $image = $image->stream()->__toString();

                        $thumb_path = str_replace('big','thumb',$path);
                        Storage::disk('s3')->put($path, $image,'');
                        Common_function::thumb_storage($path,$thumb_path);
                    }else{

                        $tmp_path = config('custom_config.s3_spiritual_journey_video');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = file_get_contents($file);
                        Storage::disk('s3')->put($path, $image,'');
                    }

                    if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_big').$request->input('oldPhoto'));
                        Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_thumb').$request->input('oldPhoto'));
                    }else{
                        Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_video').$request->input('oldPhoto'));
                    }

                    $update_spiritual_journey_array['file'] = str_replace($tmp_path,'',$path);
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
                UserSpiritualJourney::where('feed_id','=',$id)->update($update_spiritual_journey_array);
                if($save_draft == '' ){
                    return redirect('/spiritual-journey')->withSuccess(trans('custom.succ_spiritual_journey_updated'));
                }else if($request->draft == 2){
                    Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                    return redirect('/spiritual-journey')->withSuccess(trans('custom.succ_spiritual_journey_updated'));
                }else{
                    return redirect('/spiritual-journey/edit/'.$id )->withSuccess(trans('custom.succ_spiritual_journey_updated_draft'));
                }
            } else{
                return redirect()->route('spiritual-journey.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }

    public function loadMoreSpiritualJourney(Request $request){ 
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
            $html = view('include.spiritual-journey-block',$data)->render();
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
            Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_thumb').$request->file);
        }else{
            Storage::disk('s3')->delete(config('custom_config.s3_spiritual_journey_video').$request->file);
        }
        $update_spiritual_journey_file_array['file'] = null;

        UserSpiritualJourney::where('feed_id','=',$request->id)->update($update_spiritual_journey_file_array);
        return response()->json(['code' => '1']);exit;
    }

    public function information($id){
        $data = array('title' => 'Journey Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>27,'commentTable'=>28);
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
            return  redirect()->route('spiritual-journey.index')->withError(trans('custom.error_delete_post'));
        }else{
            return view('spiritual_journey_detail',$data);
        }
    }
}
