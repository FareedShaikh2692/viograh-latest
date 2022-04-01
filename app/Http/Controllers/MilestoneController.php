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
use App\UserMilestone;
use Session;
use App\FeedDocument;
use Illuminate\Support\Collection;


class MilestoneController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'Milestone';
        $this->module_type = config('custom_config.user_feed_type')['milestones'];
        $save_draft = $request->save_draft;
        $this->validation_array = array(
            'title' => ($save_draft == '') ? 'required|max:200' : '',
            'description' => ($save_draft == '') ? 'required|min:10' : '',
            'milestone_completion_date' => ($save_draft == '') ? 'required' : '',
            'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
        );        
        $this->validation_message = array(
            'title.required' => trans('custom.js_required_with_attribute'),
            'title.max' => trans('custom.js_max_lengths_serverside'),
            'description.required' => trans('custom.js_required_with_attribute'),
            'description.min' => trans('custom.js_min_lengths_serverside'),
            'milestone_completion_date.required' => trans('custom.js_required_with_attribute'),
            'uploaddoc.*.mimes' => trans('custom.Doc_mimes'),
            'uploaddoc.*.max' => trans('custom.Doc_size'),
        );
    }
   
    public function index(){
        $data = array('title' => 'Milestones','module'=>$this->main_module,'mainTable'=> 1);
        
        $data['results']  = UserMilestone::select(
            "user_milestones.feed_id", 
            "user_milestones.user_id",
            "user_milestones.title",
            "user_milestones.description",
            "user_milestones.achieve_date",
            "user_milestones.file", 
            "user_feeds.is_save_as_draft",
        )
        ->leftJoin("user_feeds", "user_feeds.id", "=", "user_milestones.feed_id")
        ->where('user_feeds.status', '=','enable')
        ->where('user_milestones.user_id','=',Auth::user()->id)
        ->where('user_feeds.type_id','=',$this->module_type)
        ->orderBy('is_save_as_draft','DESC')
        ->orderBy('achieve_date','DESC');
        
        $mytempClone  = clone $data['results'] ;  
        $data['milestoneCount']   = $mytempClone->count();
        $data['results'] =  $data['results']->limit(4)->offset(0)->get();
       
        $data['offset'] = 4;            
        return view('milestones',$data);
    }
    public function add(){
        $data = array('title' => 'Add To Your Milestone','method'=>'Add','action'=>route('milestone.insert'),'frn_id'=>'frm_milestone' ,'module'=>$this->main_module);

        return view('add_milestone', $data);
    }
    public function insert(Request $request){
        try{
            $module=$this->main_module;   
            $this->validation_array['uploaddoc'] = 'max:5';
            $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
            $save_draft = $request->save_draft;
            $file = $request->file('file'); 
            if(!empty($file)){
                $file_type = $file->getClientOriginalExtension();
                $this->validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                $this->validation_message['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
            } 
            // CHECK SERVER SIDE VALIDATION        
            $this->validate($request,$this->validation_array,$this->validation_message);
            
            $achieve_aim_date = explode(" ", $request->milestone_completion_date);
            $actualdate = implode("-",str_replace(',','',$achieve_aim_date));

            $file = $request->file('file');    
            $file_name='';
        
            if(!empty($file)){
                $fileExtn = $file->getClientOriginalExtension();
                if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                    $tmp_path = config('custom_config.s3_milestone_big');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    Storage::disk('s3')->put($path, $image,'');
                    Common_function::thumb_storage($path,$thumb_path);
                }else{

                    $tmp_path = config('custom_config.s3_milestone_video');
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
        
            $insert_milestone_array = array(
                'feed_id' => $feedId->id,
                'user_id'=>Auth::user()->id,
                'title' => $request->title,            
                'achieve_date' => ($request->milestone_completion_date != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL ,
                'description' => $request->description,
                'file' => $file_name,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            UserMilestone::insert($insert_milestone_array);
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
                return redirect()->route('milestone.index')->withInput()->withSuccess(trans('custom.succ_milestone_added'));
            }else{
                return redirect('/milestone/edit/'.$feedId->id )->withSuccess(trans('custom.succ_milestone_added_draft'));
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }
    public function edit($id){
        $data = array('title'=>" Edit Your Milestone", 'method'=>'Edit','action'=>route('milestone.update',$id),'frn_id'=>'frm_milestone' ,'module'=>$this->main_module);
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                        ->where('id','=',$id)
                        ->where('status','=','enable')
                        ->where('user_id','=',Auth::user()->id)
                        ->first();
        if(empty($data['result'])){
            return redirect()->route('milestone.index');
        }else{
            return view('add_milestone',$data);
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

                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $this->validation_array['uploaddoc'] = 'max:0';
                        $this->validation_message['uploaddoc.max'] = 'Maximum file limit is 5';
                    }
                }
                $achieve_aim_date = explode(" ", $request->milestone_completion_date);
                $actualdate = implode("-",str_replace(',','',$achieve_aim_date));

                $file = $request->file('file'); 
                if(!empty($file)){
                    $file_type = $file->getClientOriginalExtension();
                    $this->validation_array['file'] = ((@$file_type == 'jpg' || @$file_type == 'jpeg' || @$file_type == 'png') ? 'max:10240|' : 'max:102400|'). 'mimes:jpeg,png,jpg,mp4,ogv,webm';
                    $this->validation_message['file.max'] = ($file_type == 'jpg' || $file_type == 'jpeg' || $file_type == 'png') ? trans('custom.file_max_size_image') : trans('custom.file_max_size');
                } 
            
                // CHECK SERVER SIDE VALIDATION        
                $this->validate($request,$this->validation_array,$this->validation_message);
                $save_draft = $request->save_draft;
                $update_feed_array = array(
                    'privacy' => ($request->privacy != '')?$request->privacy:'public',
                    'is_save_as_draft' => ($request->save_draft != '') ?  1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_ip' => ip2long(\Request::ip()),
                );
                UserFeed::where('id','=',$id)->update($update_feed_array);

                $update_milestone_array = array(            
                    'title' => $request->title,            
                    'achieve_date' =>  ($request->milestone_completion_date != '') ? date_format(date_create($actualdate), 'Y-m-d ' ) : NULL,
                    'description' => $request->description,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );
                
                if(!empty($file)){
                    $fileExtn = $file->getClientOriginalExtension();
                    
                    if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){
                        $tmp_path = config('custom_config.s3_milestone_big');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());

                        $path = $tmp_path.$file_name;
                        $image = Image::make($file)->orientate();
                        $image = $image->stream()->__toString();

                        $thumb_path = str_replace('big','thumb',$path);
                        Storage::disk('s3')->put($path, $image,'');
                        Common_function::thumb_storage($path,$thumb_path);
                    }else{
                        $tmp_path = config('custom_config.s3_milestone_video');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = file_get_contents($file);
                        Storage::disk('s3')->put($path, $image,'');
                    }

                    if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_milestone_big').$request->input('oldPhoto'));
                        Storage::disk('s3')->delete(config('custom_config.s3_milestone_thumb').$request->input('oldPhoto'));
                    }elseif($extn[1] == 'mp4' || $extn[1] == 'ogv' || $extn[1] == 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_milestone_video').$request->input('oldPhoto'));
                    }

                    $update_milestone_array['file'] = str_replace($tmp_path,'',$path);
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
                UserMilestone::where('feed_id','=',$id)->update($update_milestone_array);        
                if($save_draft == '' ){  
                    return redirect()->route('milestone.index')->withSuccess(trans('custom.succ_milestone_updated'));
                }else if($request->draft == 2){
                    Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                    return redirect()->route('milestone.index')->withSuccess(trans('custom.succ_milestone_updated'));
                }else{
                    return redirect('/milestone/edit/'.$id )->withSuccess(trans('custom.succ_milestone_updated_draft'));
                }
            } else{
                return redirect()->route('milestone.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }
    public function information($id){
        $data = array('title' => 'Milestone Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>41,'commentTable'=>42);

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
            return  redirect()->route('milestone.index')->withError(trans('custom.error_delete_post'));
        }else{
            return view('milestone_detail',$data);
        }
    }
    public function deleteFile(Request $request){
        $extn = explode(".",$request->file);
        if($request->file != '' && $extn[1] != 'mp4' || $extn[1] != 'ogv' || $extn[1] != 'webm'){
            Storage::disk('s3')->delete(config('custom_config.s3_milestone_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_milestone_thumb').$request->file);
        }else{
            Storage::disk('s3')->delete(config('custom_config.s3_milestone_video').$request->file);
        }
        $update_milestonefile_array['file'] = null;

        UserMilestone::where('feed_id','=',$request->id)->update($update_milestonefile_array);
        return response()->json(['code' => '1']);exit;
    }
    public function loadMoreMilestone(Request $request){
       
        $output = array();

        $offset = $request->id;// ID = DATE
       
        $public_profile_module = $request->public_profile_module;
        $user_of_id = $public_profile_module ? $request->user_id : Auth::user()->id;
        
        $data['results']  = UserMilestone::select(
            "user_milestones.feed_id", 
            "user_milestones.user_id",
            "user_milestones.title",
            "user_milestones.description",
            "user_milestones.achieve_date",
            "user_milestones.file", 
            "user_feeds.is_save_as_draft",
        )
        ->leftJoin("user_feeds", "user_feeds.id", "=", "user_milestones.feed_id")
        ->where('user_feeds.status', '=','enable')
        ->where('user_milestones.user_id','=',$user_of_id)
        ->where('user_feeds.type_id', '=', $this->module_type)
        ->where(function ($q)  {
            $q->where('user_feeds.privacy','=','public')
            ->orWhere('user_milestones.user_id','=',Auth::user()->id) 
            ->orWhere(function ($q2)  {
                $q2->where('user_feeds.privacy','=','family')
                    ->WhereRaw('EXISTS ( SELECT * FROM `vg_users` as utbl WHERE utbl.`id` = vg_user_feeds.user_id  AND (SELECT COUNT(id) as tot FROM vg_user_family_trees as tbl1 WHERE tbl1.user_id = '.Auth::user()->id.' AND tbl1.email = utbl.email AND tbl1.status = "enable") > 0 )');
            });
        })->where('user_feeds.type_id','=',$this->module_type)
        ->orderBy('is_save_as_draft','DESC')
        ->orderBy('achieve_date','DESC')
        ->offset($offset)
        ->limit(4);
        if($public_profile_module == 'public-profile'){
            $data['results'] =  $data['results']->where('is_save_as_draft','!=','1');
        }
        $data['results'] =  $data['results']->get();
         
        if(!$data['results']->isEmpty())
        {
            $html = ( $public_profile_module == 'public-profile') ? view('include.milestone-feed-block',$data)->render() : view('include.milestone-block',$data)->render();
           
            $op = ($data['results']->count() < 4) ? 0:1;

            return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$offset+4,'output'=>$output,'html'=>$html]);exit;
            
        }
        else{
            return response()->json(['code' => '0']);exit;
        }
    }
}
