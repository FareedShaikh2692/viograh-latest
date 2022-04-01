<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\UserAsset;
use App\UserAssetDocuments;
use App\UserLiability;
use App\UserLiabilityDocuments;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Mail;
use App\MailSetting;
use App\User;
use Illuminate\Support\Facades\Hash;
use Session;
use DateTime;
use Redirect;
use Illuminate\Support\Facades\DB;


class LiabilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'my-networth'; 
    }

    public function liability_add(Request $request)
    {
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $data = array('title' => 'Add To Your Liability','method'=>'Add','action'=>route('liability.liability-insert'),'frn_id'=>'frm_liability_add' ,'module'=>$this->main_module);
            return view('add_liabilities', $data);
        }else{
            return view('password_protected_networth');
        }
    }
 
    public function liability_insert(Request $request)    
    { 
        try{
            $save_draft = $request->save_draft;
            // VALIDATION RULE
            $validation_array  = array(
                'title' => ($save_draft == '') ? 'required|min:2|max:100|not_regex:/^[0-9]+$/':'',
                'description' => ($save_draft == '') ? 'required|min:4':'',
                'amount' => ($save_draft == '') ? 'required|not_in:0|max:15|regex:/^[0-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/':'',
                'bank_name' => ($save_draft == '') ? 'min:4|max:100|nullable|required_with:account_number':'',
                'account_number' => ($save_draft == '') ? 'nullable|regex:/^[0-9]+$/|min:8|max:30|required_with:bank_name':'',
                'uploaddoc.*' => ($save_draft == '') ? 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240':'',
                'uploaddoc' =>  ($save_draft == '') ? 'max:5':'',
            );
            $rules = array(
                'title.required' =>trans('custom.js_required_with_attribute'),
                'title.max' =>trans('custom.js_max_lengths_serverside'),
                'title.min' =>'Minimum 2 characters required for title',
                'title.not_regex' => 'Only number is not allow for title',
            
                'description.required' => trans('custom.js_required_with_attribute'),
                'description.min' => trans('custom.js_min_lengths_serverside'), 
                
                'amount.required' => trans('custom.js_required_with_attribute'),
                'amount.not_in' =>'Minimum amount should be 1',
                'amount.max' =>'Maximum digits should be 15',
                'amount.regex' =>'Minimum amount should be 1',
                'bank_name.min' => trans('custom.js_min_lengths_serverside'),    
                'bank_name.max' => trans('custom.js_max_lengths_serverside'),
            
                'account_number.regex' => 'only numeric allow',
                'account_number.max' => trans('custom.js_max_lengths_serverside'),
                'account_number.min' => trans('custom.js_min_lengths_serverside'),
                'uploaddoc.*.mimes' =>  trans('custom.Doc_mimes'),
                'uploaddoc.*.max' =>  trans('custom.Doc_size'),
                'uploaddoc.max' => 'Maximum 5 documents are allowed.',
            );
            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);
            $insert_liability_array = array(
                'user_id'=>Auth::user()->id,
                'title' => $request->title,
                'amount' => $request->amount,           
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'description' => $request->description,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );

            if($save_draft != '' ){
                $update_is_draft = array('is_save_as_draft' => 1);
            }else{
                $update_is_draft = array('is_save_as_draft' => 0);
            }
            $userLiability = UserLiability::create($insert_liability_array);
            UserLiability::where('id','=',$userLiability->id)->update($update_is_draft);

            $docfile = $request->file('uploaddoc');  
        
            if(!empty($docfile)){
                $insertDoc = array();
                foreach($docfile as $dockey => $docvalue){
    
                    $docExtn = $docvalue->getClientOriginalExtension();
                    $tmpPath = config('custom_config.s3_liability_document');
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
                    }
                    else{
                        $fileType = 'doc';
                        $filecontents = file_get_contents($docvalue);
                    }
                    Storage::disk('s3')->put($paths, $filecontents,'');
                    array_push($insertDoc, array(
                        'liability_id' => $userLiability->id,   
                        'file' => $fileName,                                
                        'created_ip' => ip2long(\Request::ip()),
                        'created_at'=>Carbon::now(),          
                    ));
                }
                UserLiabilityDocuments::insert($insertDoc);
            }
            if($save_draft == '' ){
                return redirect()->route('asset.index')->withInput()->withSuccess(trans('custom.succ_liability_added'));
            }else{
                return redirect('/liability/edit-liability/'.$userLiability->id )->withSuccess(trans('custom.succ_liability_added_draft'));
            } 
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }
    public function liability_edit(Request $request,$id){
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $data = array('title'=>" Edit Your Liability", 'method'=>'Edit','action'=>url('liability/liability-update/'.$id),'frn_id'=>'frm_liability_edit' ,'module'=>$this->main_module);
            $data['result'] = UserLiability::where('status','=','enable')
                            ->where('user_id','=',Auth::user()->id)
                            ->where('id','=',$id)
                            ->first();
            if(empty($data['result'])){
                return redirect()->route('asset.index');
            }else{
                return view('add_liabilities',$data);    
            }
            
        }else{
            return view('password_protected_networth');
                
        }
 }
 public function liability_update(Request $request,$id){
    try{
        $save_draft = $request->save_draft;
        $result = UserLiability::where('status','=','enable')
        ->where('user_id','=',Auth::user()->id)
        ->where('id','=',$id)
        ->first();
        if(!empty($result)){
            // CHECK SERVER SIDE VALIDATION
            $docfilescount = UserLiabilityDocuments::where('liability_id','=',$id)->get()->count();
            $remainfilecount = (5) - ($docfilescount);
        
            // VALIDATION RULE
            $validation_array = array(
                'title' => ($save_draft == '')?'required|min:2|max:100|not_regex:/^[0-9]+$/':'',
                'description' => ($save_draft == '')?'required|min:4':'',
                'amount' => ($save_draft == '')?'required|not_in:0|max:15|regex:/^[0-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/':'',
                'bank_name' => ($save_draft == '')?'min:4|max:100|nullable|required_with:account_number':'',
                'account_number' => ($save_draft == '')?'nullable|regex:/^[0-9]+$/|min:8|max:30|required_with:bank_name':'',
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                'uploaddoc' =>  'max:'.$remainfilecount,
            );
            
            $rules   = array(
                'title.required' => trans('custom.js_required_with_attribute'),
                'title.max' => trans('custom.js_max_lengths_serverside'),
                'title.min' =>'Minimum 2 characters required for title',
                'title.not_regex' => 'Only number is not allow for title',
            
                'description.required' => trans('custom.js_required_with_attribute'),
                'description.min' => trans('custom.js_min_lengths_serverside'), 
                
                'amount.required' => trans('custom.js_required_with_attribute'),
                'amount.not_in' =>'Minimum amount should be 1',
                'amount.max' =>'Maximum digits should be 15',
                'amount.regex' =>'Minimum amount should be 1',
                'bank_name.min' => trans('custom.js_min_lengths_serverside'),    
                'bank_name.max' => trans('custom.js_max_lengths_serverside'),
            
                'account_number.regex' => 'only numeric allow',
                'account_number.max' => trans('custom.js_max_lengths_serverside'),
                'account_number.min' => trans('custom.js_min_lengths_serverside'),
                'uploaddoc.*.mimes' =>  trans('custom.Doc_mimes'),
                'uploaddoc.*.max' =>  trans('custom.Doc_size'),
                'uploaddoc.max' => 'Maximum 5 documents are allowed.',
                
            );
            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);

            $update_liability_array = array(
                'title' => $request->title,
                'amount' => $request->amount,
                'description' => $request->description,
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'updated_ip' => ip2long(\Request::ip()),
                'updated_at' => Carbon::now(),
            );
        
            $docfile = $request->file('uploaddoc');
            if(!empty($docfile)){
                
                $insertDoc = array();
                foreach($docfile as $dockey => $docvalue){
        
                    $docExtn = $docvalue->getClientOriginalExtension();
                    $tmpPath = config('custom_config.s3_liability_document');
                    $fileName =Common_function::fileNameTrimmer( $docvalue->getClientOriginalName());
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
                    } 
                    else{
                        $fileType = 'doc';
                        $filecontents = file_get_contents($docvalue);
                    }
                    Storage::disk('s3')->put($paths, $filecontents,'');
                    array_push($insertDoc, array(
                        'liability_id' => $id,   
                        'file' => $fileName,                                
                        'created_ip' => ip2long(\Request::ip()),
                        'created_at'=>Carbon::now(),          
                    ));
                }
                UserLiabilityDocuments::insert($insertDoc);
            }
                UserLiability::where('id','=',$id)->update($update_liability_array);
                if($save_draft == '' ){
                    return redirect()->route('asset.index')->withInput()->withSuccess(trans('custom.succ_liability_updated'));
                }else{
                    return redirect('/liability/edit-liability/'.$id )->withSuccess(trans('custom.succ_liability_updated_draft'));
                } 
            } else{
                return redirect()->route('asset.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
     }
     public function liability_delete_File(Request $request){
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $id = $request->id; 
            $file = $request->file_name;
            $extn = explode(".",$request->file);
        
            Storage::disk('s3')->delete(config('custom_config.s3_liability_document').$file);       
            
            UserLiabilityDocuments::where('id','=',$id)
                    ->delete();
            $dcid = 'doc_'.$id;
            return response()->json(['code' => '1','docId' => $dcid]);exit;
        } else{
            return view('password_protected_networth');
                
        }
         
     }
     public function liability_information(Request $request,$id)
     {
        if( Common_function::passwordProtectedNetworth($request)==true ){  
             $data = array('title' => 'Liability Information','module'=>$this->main_module,'mainTable'=> 34);
             $data['results'] = UserLiability::where('id','=',$id)
                             ->where('status','=',"enable")
                             ->where('user_id','=',Auth::user()->id)
                             ->first();
             
             if(empty($data['results'])){
                 return redirect()->route('asset.index');
             }else{
                 return view('liabilities_detail',$data);
             }
         }else{
             return view('password_protected_networth');
         }
    }
    
}
