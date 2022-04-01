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
use App\UserDiary;
use App\UserDiaryComment;
use App\UserDiaryLike;
use App\FeedDocument;
class DiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'diary';
        $this->module_type = config('custom_config.user_feed_type')['diary'];
    }

    public function index(Request $request){
        $data = array('title' => 'Diary','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>10,'commentTable'=>11);

        $data['diaryCount'] = UserDiary::whereHas('getUserDiaryFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)->where('type_id','=',$this->module_type); 
            })
            ->orderBy('date','DESC')
            ->get();
      
        $data['results'] = UserDiary::whereHas('getUserDiaryFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)->where('type_id','=',$this->module_type); 
            })
            ->offset(0)
            ->limit(4)
            ->orderBy('date','DESC')
            ->get();
        $data['offset'] = 4;
        return view('diary',$data);
    }

    public function add(){
        $data = array('title' => 'Add to your Diary','method'=>'Add','action'=>route('diary.insert'),'frn_id'=>'frm_diary_add' ,'module'=>$this->main_module);
        return view('add_diary_article', $data);
    }

    public function insert(Request $request){
        try{
            $module=$this->main_module;
            $save_draft = $request->save_draft;
            // VALIDATION RULE
            $validation_array = array(
                'description'=> ($save_draft == '') ? 'required|min:10' : '',
                'date' => ($save_draft == '') ? 'required' : '',            
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                'uploaddoc' => 'max:5',
            );
            $rules = array(
                'description.required' => 'The description is required',
                'description.min' =>  trans('custom.js_min_lengths_serverside'),
                'date.required' => 'The date is required',            
                'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',           
                'uploaddoc.*.mimes' => 'Invalid document only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
                'uploaddoc.*.max' => 'Documents size should be less than 10MB',
                'uploaddoc.max' => 'Maximum file limit is 5',
            );
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
                    $tmp_path = config('custom_config.s3_diary_big');
                    $file_name = Common_function::fileNameTrimmer($file->hashName());
                    $path = $tmp_path.$file_name;
                    $image = Image::make($file)->orientate();
                    $image = $image->stream()->__toString();

                    $thumb_path = str_replace('big','thumb',$path);
                    Storage::disk('s3')->put($path, $image,'');
                    Common_function::thumb_storage($path,$thumb_path);
                }else{
                    $tmp_path = config('custom_config.s3_diary_video');
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

            $insert_diary_array = array(
                'feed_id' => $feedId->id,
                'user_id' => Auth::user()->id,
                'date'=> ($request->date != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL ,
                'description' => $request->description,
                'file' => $file_name,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            UserDiary::insert($insert_diary_array);
            
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
                return redirect('/diary')->withSuccess(trans('custom.succ_diary_added'));
            }else{
                return redirect('/diary/edit/'.$feedId->id )->withSuccess(trans('custom.succ_diary_added_draft'));
            }
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }

    public function edit($id){
        $data = array('title'=>" Edit Your Diary", 'method'=>'Edit','action'=>url('diary/update/'.$id),'frn_id'=>'frm_diary_edit' ,'module'=>$this->main_module);
        $data['result'] = UserFeed::where('type_id','=',$this->module_type)
                        ->where('id','=',$id)
                        ->where('user_id','=',Auth::user()->id)
                        ->where('status','=','enable')
                        ->first();
        if(empty($data['result'])){
            return redirect('/diary');
        }else{
            return view('add_diary_article',$data);
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
                    'description'=>($save_draft == '') ? 'required|min:5' : '',
                    'date' =>($save_draft == '')  ?'required' : '',                
                    'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,xlsx,xls,txt,ods,docx|max:10240',
                    'uploaddoc' =>  'max:5',
                );
                if(!empty($request->file('uploaddoc'))){
                    if($totaldocs == 5 || ($totaldocs+count($request->file('uploaddoc'))) > 5){
                        $validation_array['uploaddoc'] = 'max:0';
                    }
                }
                $rules =array(
                    'description.required' => 'The By description? is required',
                    'description.min' =>  trans('custom.js_min_lengths_serverside'),
                    'date.required' => 'The date is required',                
                    'file.mimes' => 'File should of a file type: jpeg, png, jpg, mp4, ogv and webm.',                
                    'uploaddoc.*.mimes' => 'Invalid document, only JPEG, JPG, PNG, PDF, DOC, DOCX, XLS, XLSX, TXT and ODS files are allowed in document.',
                    'uploaddoc.*.max' => 'Documents size should be less than 10MB',
                    'uploaddoc.max' => 'Maximum file limit is 5',
                );
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
                
                $update_diary_array = array(
                    'description' => $request->description,
                    'date' => ($request->date != '') ? date_format( date_create($actualdate), 'Y-m-d' ) : NULL ,
                    'updated_ip' => ip2long(\Request::ip()),
                    'updated_at' => Carbon::now(),
                );

                $file = $request->file('file');
                
                if(!empty($file)){
                    $fileExtn = $file->getClientOriginalExtension();
                    
                    if($fileExtn == 'png' || $fileExtn == 'jpg' || $fileExtn == 'jpeg'){
                        $tmp_path = config('custom_config.s3_diary_big');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = Image::make($file)->orientate();
                        $image = $image->stream()->__toString();

                        $thumb_path = str_replace('big','thumb',$path);
                        Storage::disk('s3')->put($path, $image,'');
                        Common_function::thumb_storage($path,$thumb_path);
                    }else{
                        $tmp_path = config('custom_config.s3_diary_video');
                        $file_name = Common_function::fileNameTrimmer($file->hashName());
                        $path = $tmp_path.$file_name;
                        $image = file_get_contents($file);
                        Storage::disk('s3')->put($path, $image,'');
                    }

                    if($request->input('oldPhoto') != '' && $fileExtn != 'mp4' || $fileExtn != 'ogv' || $fileExtn != 'webm'){
                        Storage::disk('s3')->delete(config('custom_config.s3_diary_big').$request->input('oldPhoto'));
                        Storage::disk('s3')->delete(config('custom_config.s3_diary_thumb').$request->input('oldPhoto'));
                    }else{
                        Storage::disk('s3')->delete(config('custom_config.s3_diary_video').$request->input('oldPhoto'));
                    }

                    $update_diary_array['file'] = str_replace($tmp_path,'',$path);
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
                UserDiary::where('feed_id','=',$id)->update($update_diary_array);

                if($save_draft == '' ){
                    return redirect('/diary')->withSuccess(trans('custom.succ_diary_updated'));
                }else if($request->draft == 2){
                    Common_function::PostCreationEmail($this->module_type,$id,$this->main_module,'');
                    return redirect('/diary')->withSuccess(trans('custom.succ_diary_updated'));
                }
                else{
                    return redirect('/diary/edit/'.$id )->withSuccess(trans('custom.succ_diary_updated_draft'));
                }
            } else{
                return redirect()->route('diary.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }

    public function loadMoreDiary(Request $request){
        $output = array();

        $offset = $request->id;// ID = DATE
        
        $public_profile_module = $request->public_profile_module;
       
        $user_of_id = $public_profile_module ? $request->user_id : Auth::user()->id;
        $data['results'] = UserDiary::whereHas('getUserDiaryFeed',function($query) use ($user_of_id,$public_profile_module){
                $query->where('status', '=','enable')->where('user_id','=',$user_of_id)->where('type_id','=',$this->module_type);
                if($public_profile_module == 'public-profile'){
                    $query = $query->where('is_save_as_draft','!=','1');
                }
                $query = $query->where(function ($q)  {
                    $q->where('privacy','=','public')
                    ->orWhere('user_id','=',Auth::user()->id) 
                    ->orWhere(function ($q2)  {
                        $q2->where('privacy','=','family')
                            ->WhereRaw('EXISTS ( SELECT * FROM `vg_users` as utbl WHERE utbl.`id` = vg_user_feeds.user_id  AND (SELECT COUNT(id) as tot FROM vg_user_family_trees as tbl1 WHERE tbl1.user_id = '.Auth::user()->id.' AND tbl1.email = utbl.email AND tbl1.status = "enable") > 0 )');
                    });
                }); 
            })
            ->offset($offset)
            ->limit(4)
            ->orderBy('date','DESC')
            ->get();
        if(!$data['results']->isEmpty())
        {
            $html = view('include.diary-block',$data)->render();
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
            Storage::disk('s3')->delete(config('custom_config.s3_diary_big').$request->file);
            Storage::disk('s3')->delete(config('custom_config.s3_diary_thumb').$request->file);
        }else{
            Storage::disk('s3')->delete(config('custom_config.s3_diary_video').$request->file);
        }
        $update_diaryfile_array['file'] = null;

        UserDiary::where('feed_id','=',$request->id)->update($update_diaryfile_array);
        return response()->json(['code' => '1']);exit;
    }

    public function information($id){
        $data = array('title' => 'Diary Information','module'=>$this->main_module,'mainTable'=> 1,'likeTable'=>10,'commentTable'=>11);

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
            return view('diary_detail',$data);
        }else{
            return redirect()->route('diary.index')->withError(trans('custom.error_delete_post'));
        }
    }

}
