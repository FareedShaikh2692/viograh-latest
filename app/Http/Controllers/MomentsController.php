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
use App\UserMomentFiles;
use App\UserMoments;
use Session;
use App\FeedDocument;
use Illuminate\Support\Collection;

class MomentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'Special-Moments';
        $this->module_type = config('custom_config.user_feed_type')['moments'];
    }
    public function index(Request $request){
        $data = array('title' => 'Special Moments','module'=>$this->main_module,'mainTable'=> 1,'filetable'=> 30);
        $data['momentCount'] = UserFeed::wherehas('getUserMoment')
                        ->select('id')->where('type_id','=',$this->module_type)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->orderBy('id','DESC')->get();

        $data['result'] = UserFeed::wherehas('getUserMoment')
                        ->where('type_id','=',$this->module_type)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->orderBy('id','DESC')->offset(0)->limit(4)->get();
        $data['offset'] = 4;
  
        return view('moments',$data);
    }
    public function add(){
        $data = array('title' => 'Add To Your Special Moments','method'=>'Add','action'=>route('moments.insert'),'frn_id'=>'frm_moments_add' ,'module'=>$this->main_module);
        return view('add_moment', $data);
    }
    public function insert(Request $request){
        try{
            $module=$this->main_module;
            $file = $request->file('file'); 

            $mime_type=array();
            if($file != ''){
                foreach($file as $k => $val){
                    $array=$val->getClientOriginalExtension();
                    array_push($mime_type,$array);
                }
            }
            $save_draft = $request->save_draft;
            // VALIDATION RULE
            $validation_array = array(
                'title'=> ($save_draft == '') ? 'required|max:200' : '',
                'description'=> ($save_draft == '')  ? 'required|min:10' : '',
                'file.*' => (in_array('jpg',$mime_type) || in_array('jpeg',$mime_type)  || in_array('png',$mime_type) ? 'max:10240|' : 'max:102400|')
                .'mimes:jpeg,png,jpg,mp4,ogv,webm',
                'file' =>  'max:20',
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                'uploaddoc' => 'max:5',
            );
            
            $rules = [
                'title.required' =>  trans('custom.js_required_with_attribute'),
                'title.max' =>  trans('custom.js_max_lengths_serverside'),
                'description.required' => trans('custom.js_required_with_attribute'),
                'description.min' =>  trans('custom.js_min_lengths_serverside'),
                'file.*.max' => (in_array('jpg',$mime_type) || in_array('jpeg',$mime_type)  || in_array('png',$mime_type) ? trans('custom.file_max_size_image') : trans('custom.file_max_size')),
                'file.*.mimes' => 'Valid Extentions for file type are jpeg, png, jpg, mp4, ogv, webm.',
                'file.max' => 'Files must be less than or equals 20.',            
                'uploaddoc.*.mimes' => 'Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
                'uploaddoc.*.max' => trans('custom.Doc_size'),
                'uploaddoc.max' => 'Maximum file limit is 5',
            ];
            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);
        
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
        
            $insert_moment_array = array(
                'feed_id' => $feedId->id,
                'user_id' => Auth::user()->id,
                'title' => $request->title,
                'description' => $request->description,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            UserMoments::insert($insert_moment_array);
            
            $exp =  $request->remove_new_file_hidden !='' ? explode(",", $request->remove_new_file_hidden) : array();
            
            if(!empty($file)){
                $insertfile = array();
                foreach($file as $filekey => $filevalue){
                    if(!in_array($filekey,$exp)){
                        
                        $fileExtn = $filevalue->getClientOriginalExtension(); 
                        
                        if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){

                            $tmp_path = config('custom_config.s3_moment_big');
                            $file_name = Common_function::fileNameTrimmer($filevalue->hashName());
                            $path = $tmp_path.$file_name;
                            $image = Image::make($filevalue)->orientate();
                            $image = $image->stream()->__toString();
            
                            $thumb_path = str_replace('big','thumb',$path);
                            Storage::disk('s3')->put($path, $image,'');
                            Common_function::thumb_storage($path,$thumb_path);
                        }else{
            
                            $tmp_path = config('custom_config.s3_moment_video');
                            $file_name = Common_function::fileNameTrimmer($filevalue->hashName());
                            $path = $tmp_path.$file_name;
                            $image = file_get_contents($filevalue);
                            Storage::disk('s3')->put($path, $image,'');
                        }
                        array_push($insertfile, array(
                            'feed_id' => $feedId->id,
                            'user_id' => Auth::user()->id,
                            'file' => $file_name,
                            'created_at'=>Carbon::now(),
                            'created_ip' => ip2long(\Request::ip()),
                        ));
                    }
                }
                
                UserMomentFiles::insert($insertfile); 
            }  
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
            if($save_draft == ''){
                Common_function::PostCreationEmail($feedId->type_id,$feedId->id,$module,'',$feedId->privacy);
                return redirect()->route('moments.index')->withSuccess(trans('custom.succ_moment_added'));
            }else{
                return redirect('/special-moments/edit/'.$feedId->id )->withSuccess(trans('custom.succ_moment_added_draft'));
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
        
    }
    public function edit($id){
        $data = array('title'=>" Edit Your Special Moment", 'method'=>'Edit','action'=>route('moments.update',$id),'frn_id'=>'frm_moment_edit' ,'module'=>$this->main_module);
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                        ->where('id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->first();
        $data['docfiles'] = FeedDocument::where('feed_id','=',$id)->get();
        if(empty($data['result'])){
            return redirect()->route('moments.index');
        }else{
            return view('add_moment',$data);
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
                $docfilescount = UserMomentFiles::where('feed_id','=',$id)->get();
                $remainfilecount = (20) - ($docfilescount->count());
                
                $file = $request->file('file'); 
                $mime_type = array();

                if($file != ''){
                    foreach($file as $k=>$v){
                        $extention=$v->getClientOriginalExtension();
                        array_push($mime_type,$extention);
                    }
                } 
                // VALIDATION RULE
                
                $validation_array = array(
                    'title'=>($request->save_draft == '') ? 'required|max:200' : '',
                    'description'=>($request->save_draft == '') ? 'required|min:10' : '',
                    'file.*' => (in_array('jpg',$mime_type) || in_array('jpeg',$mime_type)  || in_array('png',$mime_type) ? 'max:10240|' : 'max:102400|').'mimes:jpeg,png,jpg,mp4,ogv,webm',
                    'file' =>  'max:'.$remainfilecount,
                    'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                    'uploaddoc' =>  'max:5',
                    
                );   
                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $validation_array['uploaddoc'] = 'max:0';
                    }
                }
                $rules = [
                    'title.required' => trans('custom.js_required_with_attribute'),
                    'title.max' =>  trans('custom.js_max_lengths_serverside'),
                    'description.required' => trans('custom.js_required_with_attribute'),
                    'description.min' =>  trans('custom.js_min_lengths_serverside'),
                    'file.*.max' =>(in_array('jpg',$mime_type) || in_array('jpeg',$mime_type)  || in_array('png',$mime_type) ? trans('custom.file_max_size_image') : trans('custom.file_max_size')),
                    'file.*.mimes' => 'Valid Extentions for file type are jpeg, png, jpg, mp4, ogv, webm.',           
                    'file.max' => 'Files must be less than or equals to 20 files.',
                    'uploaddoc.*.mimes' => 'Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
                    'uploaddoc.*.max' => trans('custom.Doc_size'),
                    'uploaddoc.max' => 'Maximum file limit is 5',
                ];
                // CHECK SERVER SIDE VALIDATION
                $this->validate($request,$validation_array, $rules);
                $save_draft = $request->save_draft;
                $update_feed_array = array(
                    'privacy' => ($request->privacy != '')?$request->privacy:'public',
                    'is_save_as_draft' => ($request->save_draft != '') ?  1 : 0,
                    'updated_at' => Carbon::now(),
                    'updated_ip' => ip2long(\Request::ip()),
                );
                UserFeed::where('id','=',$id)->update($update_feed_array);
                
                $update_moment_array = array(
                    'title' => $request->title,
                    'description' => $request->description,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );
            
                $exp =  $request->remove_new_file_hidden !='' ? explode(",", $request->remove_new_file_hidden) : array();
            
                if(!empty($file)){
                    $updatefile = array();
                    foreach($file as $filekey => $filevalue){  
                        if(!in_array($filekey,$exp)){
                            $fileExtn = $filevalue->getClientOriginalExtension(); 
                            if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){
        
                                $tmp_path = config('custom_config.s3_moment_big');
                                $file_name = Common_function::fileNameTrimmer($filevalue->hashName());
                                $path = $tmp_path.$file_name;
                                $image = Image::make($filevalue)->orientate();
                                $image = $image->stream()->__toString();
                
                                $thumb_path = str_replace('big','thumb',$path);
                                Storage::disk('s3')->put($path, $image,'');
                                Common_function::thumb_storage($path,$thumb_path);
                            }else{
                
                                $tmp_path = config('custom_config.s3_moment_video');
                                $file_name = Common_function::fileNameTrimmer($filevalue->hashName());
                                $path = $tmp_path.$file_name;
                                $image = file_get_contents($filevalue);
                                Storage::disk('s3')->put($path, $image,'');
                            }
                            if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                                Storage::disk('s3')->delete(config('custom_config.s3_moment_big').$request->input('oldPhoto'));
                                Storage::disk('s3')->delete(config('custom_config.s3_moment_thumb').$request->input('oldPhoto'));
                            }else{
                                Storage::disk('s3')->delete(config('custom_config.s3_moment_video').$request->input('oldPhoto'));
                            }
                            array_push($updatefile, array(
                                'feed_id' => $id,
                                'user_id' => Auth::user()->id,
                                'file' => $file_name,
                                'created_at'=>Carbon::now(),
                                'created_ip' => ip2long(\Request::ip()),
                            ));
                            
                        }    
                    }   
            
                    UserMomentFiles::insert($updatefile);
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
                    UserMoments::where('feed_id','=',$id)->update($update_moment_array);
                    if($save_draft == '' ){
                        return redirect()->route('moments.index')->withInput()->withSuccess(trans('custom.succ_moment_updated'));
                    }else if($request->draft == 2){
                        Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                        return redirect()->route('moments.index')->withSuccess(trans('custom.succ_moment_updated'));
                    }else{
                        return redirect('/special-moments/edit/'.$id )->withSuccess(trans('custom.succ_moment_updated_draft'));
                    }
            } else{
                return redirect()->route('moments.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
       
    }

    public function information($id){
        $data = array('title' => 'Moment Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>31,'commentTable'=>32);
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
        if(!empty($data['results'])){
            return view('moments_detail',$data);
        }else{
            return redirect()->route('moments.index')->withError(trans('custom.error_delete_post'));
        }
    }

    public function loadMoreMoments(Request $request){
        $output = array();

        $offset = $request->id;
        $public_profile_module = $request->public_profile_module;
        $user_id = $public_profile_module ? $request->user_id : Auth::user()->id;  

        $data['result'] = UserFeed::where('status', '=', 'enable')
            ->where(function ($query) use ($public_profile_module){
                if($public_profile_module == 'public-profile'){
                    $query = $query->where('is_save_as_draft','!=','1');
                }
            })
            ->where('user_id','=',$user_id)
            ->where('type_id', '=', $request->type_id)
            ->orderBy('id','DESC');           
        
        $data['result'] =$data['result']->where(function ($query) {
            $query->where('privacy','=','public')
            ->orWhere('user_id','=',Auth::user()->id)
            ->orWhere(function ($q)  {
                $q->where('privacy','=','family')
                    ->WhereRaw('EXISTS ( SELECT * FROM `vg_users` as utbl WHERE utbl.`id` = vg_user_feeds.user_id  AND (SELECT COUNT(id) as tot FROM vg_user_family_trees as tbl1 WHERE tbl1.user_id = '.Auth::user()->id.' AND tbl1.email = utbl.email AND tbl1.status = "enable") > 0 )');
            });
        })->offset($offset)->limit(4)->get();   
     
        if(!$data['result']->isEmpty())
        {
            $html = view('include.moment-block',$data)->render();      
            $op = ($data['result']->count() < 4) ? 0:1;                     
            
            return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$offset+4,'output'=>$output,'html'=>$html]);exit;            
        }
        else{
            return response()->json(['code' => '0']);exit;
        }
    }

    public function deleteFile(Request $request){
        $id = $request->id; 
        $file = $request->file_name;
        $feed_id = $request->feed_id;
        $extn = explode(".",$request->file);
    
        if($request->file != '' && $extn[1] != 'mp4')
        {
            Storage::disk('s3')->delete(config('custom_config.s3_moment_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_moment_thumb').$request->file);           
        }
        elseif($extn[1] == 'mp4'){
            Storage::disk('s3')->delete(config('custom_config.s3_moment_video').$request->file);
        }    
        UserMomentFiles::where('id','=',$id)
                ->where('user_id','=',Auth::user()->id)
                ->delete();
        $dcid = 'doc_'.$id;
        return response()->json(['code' => '1','docId' => $dcid]);exit;
        
    }
    
}
