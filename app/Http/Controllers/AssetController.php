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
use App\UserNotification;

use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->middleware('VerifyNetworthNomineePassword');
        $this->main_module = 'my-networth'; 
    }
    
    public function index(Request $request)
    {    
        $url =url()->previous();
        $name = explode('?',$url);
        $final = implode(',',$name);
        $final_name = explode('/',$final);
      
        $data['results'] = $this->common_query();
    
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $data = array('title' => 'Net Worth','module'=>$this->main_module,'mainTable'=> 33,'mainTableLiab'=> 34);
            
            //ASSET RESULT
            $data['results'] = UserAsset::where('status','=','enable')
                            ->where('user_id','=',Auth::user()->id)
                            ->orderBy('id','DESC')->get(); 
            $data['currency'] = User::where('status','=','enable')
            ->where('id','=',Auth::user()->id)->first();
             
            //LIABILITY RESULT
            $data['results_liability'] = UserLiability::where('status','=','enable')
                                ->where('user_id','=',Auth::user()->id)
                            ->orderBy('id','DESC')->get(); 

            $data['assetfinalsum']=0;
            $data['liabfinalsum']=0;

            if(($data['results'])->count() > 0){
                $assetsum=array();
                foreach($data['results'] as $key=>$row){
                    $expamt=str_replace(',', '', $row->amount);
                    if($row->is_save_as_draft != 1){
                        array_push($assetsum, array(
                            $assetsum[] = $expamt,
                        )); 
                    }
                }
                $data['assetfinalsum']=array_sum($assetsum);
            }
            if(($data['results_liability'])->count() > 0){
                $liabilitysum=array();
                foreach($data['results_liability'] as $key=>$row){
                    $expamt=str_replace(',', '', $row->amount);
                    if($row->is_save_as_draft != 1){
                        array_push($liabilitysum, array(
                            $liabilitysum[] = $expamt,
                        )); 
                    }
                }
                $data['liabfinalsum']=array_sum($liabilitysum);
            }
            
            $totlamount= ($data['assetfinalsum'])+($data['liabfinalsum']);
            
            if(($data['results'])->count() > 0 ){
                if ($totlamount > 0){
                    $data['totlcalasset']=($data['assetfinalsum']*100)/$totlamount;
                }else{
                    $data['totlcalasset'] = '';
                }
            }
            if(($data['results_liability'])->count() > 0){
                if ($totlamount > 0){
                    $data['totlcalliab']=($data['liabfinalsum']*100)/$totlamount;
                }else{
                    $data['totlcalliab'] = '';
                }
            }
            return view('assets_liabilities',$data);
        } else{
            return view('password_protected_networth',$data);
        }
        
    }
    public function common_query(){
        $result = User::where('status','=','enable') 
        ->where('id','=',Auth::user()->id)       
        ->first();
        return $result;
    }
    public function verify_password(Request $request){
        $pswd=$request->password;
        $authpass=Auth::user()->password;
        $data['results'] = User::where('status','=','enable') 
                ->where('id','=',Auth::user()->id)       
                ->first();
  
        $db_pass = $data["results"]->password;
        $db_otp = $data["results"]->mynetworth_verification_code;
        $otp = $request->password;
       
        if($db_pass == ''){
            if($otp != ''){
                if($otp == $db_otp){
                    $request->session()->put('verifyNetworthLogin',time());
                    return response()->json(['code' => '1']);exit;
                } else{
                    return response()->json(['error'=>trans('custom.error__otp'),'code' => '0']);exit;
                }  
            } else{
                return response()->json(['error'=>trans('custom.error_required__password'),'code' => '0']);exit;
            }
        } else{
            if($pswd != ''){
                if(Hash::check($pswd, $authpass)){          
                    $request->session()->put('verifyNetworthLogin',time());
                    return response()->json(['code' => '1']);exit;
                } else{
                    return response()->json(['error'=>trans('custom.error__password'),'code' => '0']);exit;
                }  
            } else{
                return response()->json(['error'=>trans('custom.error_required__password'),'code' => '0']);exit;
            }
        }
    }
    public function generate_otp(Request $request){
        $data['results'] = $this->common_query();
        $db_pass = $data["results"]->password;
        $db_otp = $data["results"]->otp;
        $otp = $request->password;

        $otp = Common_function::generateTokenNumeric(6);//OTP CREATE FOR LOGIN
        Common_function::mynetworth_verification_otp_email($data['results']->first_name, $otp);
        $update_otp_array = array('mynetworth_verification_code' => $otp);
        User::where('id','=',Auth::user()->id)->update($update_otp_array);
        return response()->json(['code' => '1','msg'=>'OTP has been sent on your registered Email.']);exit;
        
    }
    public function asset_add(Request $request)
    {
        $data['results'] = $this->common_query();
        if( Common_function::passwordProtectedNetworth($request)==true  ){ 
            $data = array('title' => 'Add To Your Asset','method'=>'Add','action'=>route('assets.asset-insert'),'frn_id'=>'frm_asset_add' ,'module'=>$this->main_module);
            return view('add_assets', $data);
        }
        else{
            return view('password_protected_networth',$data);
        }
    }

    public function asset_insert(Request $request)    
    { 
        try{
            $amount=$request->amount;
            $minamount= 1;  
            $save_draft = $request->save_draft;
            $validation_array = array(
                'title' => ($save_draft == '') ? 'required|min:2|max:100|not_regex:/^[0-9]+$/' : '',
                'nominee_name' => ($save_draft == '') ? 'nullable|min:3|max:100|required_with:nominee_email|not_regex:/^[0-9]+$/' : '',            
                'nominee_email' =>($save_draft == '') ?  'nullable|min:4|max:200|required_with:nominee_name|email' : '',
                'description' => ($save_draft == '') ? 'required|min:4' : '',
                'nominee_phone_number' => ($save_draft == '') ?  'nullable|min:9|max:15' : '',
                'amount' => ($save_draft == '') ? 'required|not_in:0|max:15|regex:/^[0-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/' : '',
                'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                'uploaddoc' =>  'max:5',
            );
            $rules  = array(            
                'title.required' => trans('custom.js_required_with_attribute'),
                'title.max' => trans('custom.js_max_lengths_serverside'),
                'title.min' =>'Minimum 2 characters required for title',
                'title.not_regex' => 'Only number is not allow for title',
               
                'nominee_name.max' => trans('custom.js_max_lengths_serverside'),
                'nominee_name.min' =>'Minimum 3  characters required for nominee_name',
                'nominee_name.not_regex' => 'Only number is not allow for title',
               
                'description.required' => trans('custom.js_required_with_attribute'),
                'description.min' => trans('custom.js_min_lengths_serverside'), 
                
                'nominee_email.max' => trans('custom.js_max_lengths_serverside'),
                'nominee_email.min' => trans('custom.js_min_lengths_serverside'),
                'nominee_email.email' => trans('custom.js_email'),
                
                'nominee_phone_number.max' => trans('custom.js_max_lengths_serverside'),
                'nominee_phone_number.min' => trans('custom.js_min_lengths_serverside'),
    
                'amount.required' =>  trans('custom.amt_required'),
                'amount.regex' =>'Minimum amount should be 1',
                'amount.not_in' =>'Minimum amount should be 1',
                'amount.max' =>'Maximum digits should be 15',
    
                'uploaddoc.*.mimes' =>  trans('custom.Doc_mimes'),  
                'uploaddoc.*.max' =>  trans('custom.Doc_size'),
                'uploaddoc.max' => 'Maximum 5 documents are allowed.',  
            );
            // CHECK SERVER SIDE VALIDATION
            $this->validate($request,$validation_array, $rules);
           
            $insert_assets_array = array(
                'user_id'=>Auth::user()->id,
                'title' => $request->title,
                'amount' => $request->amount,           
                'nominee_name' => $request->nominee_name,
                'nominee_email' => ($request->nominee_email == Auth::user()->email) ? 'error' : $request->nominee_email ,
                'nominee_phone_number' => $request->nominee_phone_number,
                'is_save_as_draft'=> ($request->save_draft != '')?1 : 0,
                'description' => $request->description,
                'created_ip' => ip2long(\Request::ip()),
                'created_at'=>Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            if($request->nominee_email == Auth::user()->email) {
                return redirect()->route('assets.asset-add')->withInput()->withError(trans('custom.error_email'));
            } else{
                $userAsset = UserAsset::create($insert_assets_array);                
            } 
            $docfile = $request->file('uploaddoc');  
         
            if(!empty($docfile)){
                $insertDoc = array();
                foreach($docfile as $dockey => $docvalue){
                    $docExtn = $docvalue->getClientOriginalExtension();
                    $tmpPath = config('custom_config.s3_asset_document');
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
                        'asset_id' => $userAsset->id,   
                        'file' => $fileName,                                
                        'created_ip' => ip2long(\Request::ip()),
                        'created_at'=>Carbon::now(),          
                    ));
                }
                UserAssetDocuments::insert($insertDoc);
            }
            if($request->nominee_email !=''){
                //SEND NOTIFICAITON
                if(@$userAsset->is_save_as_draft != 1){
                    $resultAsset = UserAsset::whereHas('getAssetUser',function($query) use($request){
                        $query->where('id','!=',Auth::user()->id)
                                ->where('email','=',$request->nominee_email)
                              ->where('status','=','enable');
                    })->where('status','=','enable')
                    ->where('user_id','=',Auth::user()->id)
                    ->first();
                    
                }
                if(@$resultAsset != ''){
                    $notification_data=array(
                        'user_id' => $resultAsset->getAssetUser->id ,
                        'type_id' => 0,
                        'type' => 'asset',
                        'from_id' => Auth::user()->id,
                        'unique_id' => 9,
                        'message' => 'Added as you as nominee',
                        'created_at' => Carbon::now(),
                        'created_ip' => ip2long(\Request::ip())
                    );
            
                    UserNotification::insert($notification_data);
                } 
               
                //SEND EMAIL
                if(@$userAsset->is_save_as_draft != 1){
                    $this->notification_email($request, $userAsset->id);
                }
            }
            if($save_draft == '' ){
                return redirect()->route('asset.index')->withInput()->withSuccess(trans('custom.succ_asset_added'));
            }else{
                return redirect('/assets/edit-asset/'.$userAsset->id )->withSuccess(trans('custom.succ_asset_added_draft'));
            } 
        }
        catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }
    public function asset_edit(Request $request,$id){
        $this->method='edit';
        $data['results'] = $this->common_query();
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            
                $data = array('title'=>" Edit Your Asset", 'method'=>'Edit','action'=>url('assets/asset-update/'.$id),'frn_id'=>'frm_asset_edit' ,'module'=>$this->main_module);

                $data['docfilescount'] = UserAssetDocuments::where('asset_id','=',$id)->get()->count();
                $data['remainfilecount'] = (5) - ( $data['docfilescount']);

                $data['result'] = UserAsset::where('status','=','enable')
                                ->where('user_id','=',Auth::user()->id)
                                ->where('id','=',$id)
                                ->first();
                if(empty($data['result'])){
                    return redirect()->route('asset.index');
                }else{
                    return view('add_assets',$data);
                } 
                
        }else{
            return view('password_protected_networth',$data);
        }
           
    }
    public function asset_update(Request $request,$id){
        try{
            $result = UserAsset::where('status','=','enable')
            ->where('user_id','=',Auth::user()->id)
            ->where('id','=',$id)
            ->first();
            if(!empty($result)){
                // CHECK SERVER SIDE VALIDATION
                $docfilescount = UserAssetDocuments::where('asset_id','=',$id)->get()->count();
                $remainfilecount = (5) - ($docfilescount);
                $validation_array = array(
                    'title' => ($request->save_draft == '') ? 'required|min:2|max:100|not_regex:/^[0-9]+$/' : '',
                    'nominee_name' => ($request->save_draft == '') ? 'nullable|min:3|max:100|required_with:nominee_email|not_regex:/^[0-9]+$/' : '',            
                    'nominee_email' => ($request->save_draft == '') ? 'nullable|min:4|max:200|required_with:nominee_name|email' : '',
                    'description' => ($request->save_draft == '') ? 'required|min:4' : '',
                    'nominee_phone_number' => ($request->save_draft == '') ? 'nullable|min:9|max:15' : '',
                    'amount' => ($request->save_draft == '') ? 'required|not_in:0|max:15|regex:/^[0-9]{1,3}(,[0-9]{3})*(?:\.\d{1,2})?/' : '',//gt:0
                    'uploaddoc.*' => 'mimes:jpeg,png,jpg,doc,pdf,txt,xlsx,xls,ods,docx|max:10240',
                    'uploaddoc' =>  'max:'.$remainfilecount,
                );
                $rules  = array(            
                    'title.required' => trans('custom.js_required_with_attribute'),
                    'title.max' => trans('custom.js_max_lengths_serverside'),
                    'title.min' =>'Minimum 2 characters required for title',
                    'title.not_regex' => 'Only number is not allow for title',
                
                    'nominee_name.max' => trans('custom.js_max_lengths_serverside'),
                    'nominee_name.min' =>'Minimum 3  characters required for nominee_name',
                    'nominee_name.not_regex' => 'Only number is not allow for title',

                    'description.required' => trans('custom.js_required_with_attribute'),
                    'description.min' => trans('custom.js_min_lengths_serverside'), 
                    
                    'nominee_email.max' => trans('custom.js_max_lengths_serverside'),
                    'nominee_email.min' => trans('custom.js_min_lengths_serverside'),
                    'nominee_email.email' => trans('custom.js_email'),
                
                    'nominee_phone_number.max' => trans('custom.js_max_lengths_serverside'),
                    'nominee_phone_number.min' => trans('custom.js_min_lengths_serverside'),
                    'amount.required' => trans('custom.js_required_with_attribute'),
                    'amount.not_in' =>'Minimum amount should be 1',
                    'amount.max' =>'Maximum digits should be 15',
                    'amount.regex' =>'Minimum amount should be 1',
                    'uploaddoc.*.mimes' => trans('custom.Doc_mimes'),   
                    'uploaddoc.*.max' =>  trans('custom.Doc_size'),
                    'uploaddoc.max' => 'Maximum 5 documents are allowed.',
                    
                );

                // CHECK SERVER SIDE VALIDATION
                $this->validate($request,$validation_array, $rules);
                $resultAsset = UserAsset::where('status','=','enable')
                                        ->where('user_id','=',Auth::user()->id)
                                        ->where('id','=',$id)
                                        ->first();
                                    
                if($request->nominee_email!='' && ($resultAsset['nominee_email'] != $request->nominee_email)){
                
                    if($request->nominee_email != Auth::user()->email && @$userAsset->is_save_as_draft != 1) { 
                    
                        $this->notification_email($request,$id);   
                    }
                }                     
                $resultAsset->title = $request->title;
                $resultAsset->amount = $request->amount;
                $resultAsset->description = $request->description;
                $resultAsset->nominee_name = $request->nominee_name;
                $resultAsset->nominee_email = $request->nominee_email;
                $resultAsset->nominee_phone_number = $request->nominee_phone_number;
                $resultAsset->updated_ip = ip2long(\Request::ip());
                $resultAsset->updated_at = Carbon::now();
                $resultAsset->is_save_as_draft =  ($request->save_draft != '') ?  1 : 0;
                
                $docfile = $request->file('uploaddoc');
                
                if(!empty($docfile)){
                    
                    $insertDoc = array();
                    foreach($docfile as $dockey => $docvalue){

                        $docExtn = $docvalue->getClientOriginalExtension();
                        $tmpPath = config('custom_config.s3_aset_document');
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
                            'asset_id' => $id,   
                            'file' => $fileName,                                
                            'created_ip' => ip2long(\Request::ip()),
                            'created_at'=>Carbon::now(),          
                        ));
                    }
                
                    UserAssetDocuments::insert($insertDoc);
                }
                if($resultAsset->nominee_email  == Auth::user()->email) {
                    return redirect()->route('assets.asset-edit',$id)->withInput()->withError(trans('custom.error_email'));
                }else{
                    $resultAsset->save();
                }
                $save_draft = $request->save_draft;
                if($save_draft == '' ){
                    return redirect()->route('asset.index')->withInput()->withSuccess(trans('custom.succ_asset_updated'));
                }else{
                    return redirect('/assets/edit-asset/'.$id )->withSuccess(trans('custom.succ_asset_updated_draft'));
                } 
            } else{
                return redirect()->route('asset.index');
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return redirect()->back()->withInput();
        }
    }
    public function asset_delete_File(Request $request){
        $data['results'] = $this->common_query();
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $id = $request->id; 
            $file = $request->file_name;
            $extn = explode(".",$request->file);
        
            Storage::disk('s3')->delete(config('custom_config.s3_asset_document').$file);          
        
            $userid=Auth::user()->id;
            UserAssetDocuments::where(function($query) use ($userid){
                        $query->orWhereHas('get_asset',function($query) use ($userid) {
                            $query->Where('user_id','=',$userid);
                        });
                    })->Where('id','=', $id)->delete();
            $dcid = 'doc_'.$id;
            return response()->json(['code' => '1','docId' => $dcid]);exit;
        }else{
            return view('password_protected_networth',$data);
                
        }
        
    }
    public function asset_information(Request $request,$id)
    {
        $data['results'] = $this->common_query();
        if( Common_function::passwordProtectedNetworth($request)==true ){ 
            $url = \Request::fullUrl();
            $name = explode('?',$url);
            $final = implode(',',$name);
            $final_name = explode('=',$final);
            $data = array('title' => 'Asset Information','module'=>$this->main_module,'mainTable'=> 33);
         
            $data['results'] = UserAsset::where('id','=',$id)
                            ->where('status','=',"enable");
                            
            if(empty($final_name[1]) || $final_name[1] == 'nomineeadded'){
                $data['results'] =  $data['results']->where('user_id','=',Auth::user()->id);
            }
            else{
                $data['results'] =  $data['results']->where('nominee_email','=',Auth::user()->email);
            }

            $data['results'] = $data['results']->first();

            if(empty($data['results'])  ){
                return redirect()->route('asset.index');
            }else{
                return view('asset_detail',$data);
            }
        }else{
            return view('password_protected_networth',$data);
                    
        }
   }
   public function notification_email(Request $request,$id){

    $check_email_exists = User::where('email','=',$request->nominee_email)
                        ->where('status','!=','delete')
                        ->first();  
            
    $user = auth()->user();     
    try{             
        if($check_email_exists!=''){
            $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'nominee-notification-registered')->first();
        
        } else{
            $mail_temp = MailSetting::where('status', '!=', 'Delete')->where('slug', '=', 'nominee-notification')->first();

        } 
        // parameter for mail template and function to send
        
        $msg = $mail_temp['content'];

        $msg = str_replace('[USER_NAME]', $user->first_name, $msg);
        $msg = str_replace('[NOMINEE_NAME]', $request->nominee_name, $msg);
        $msg = str_replace('[ASSET]', $request->title, $msg);
        $msg = str_replace('[SITE_URL]', url(''), $msg);
        $msg = str_replace('[LOGO_LINK]', url('/images/logo.png'), $msg);
        $msg = str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $msg);
        $msg = str_replace('[YEAR]', date('Y'), $msg);
        $msg = str_replace('[CONTACT_US]', config('custom_config.settings.site_email'), $msg);
        
        $msg = str_replace('[DETAILPAGE_LINK]', url('assets/asset-information/'.$id), $msg);
        $msg = str_replace('[SIGNUP_LINK]', url('signup'), $msg);


        $email_data['to_email'] = $request->nominee_email;
        $email_data['subject'] = $mail_temp['subject'];
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
}

public function nominee_delete(Request $request){
    
    $db_pre = DB::getTablePrefix();
    $feed_id = $request->id;
    
    $name = config('custom_config.tableType');

    $tbl = $db_pre.''.$name[$request->type];
    
    DB::statement("UPDATE ".$tbl." SET nominee_name = null,nominee_email = null,nominee_phone_number = null, updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$feed_id);
    
    return response()->json(['code' => '1','module' => $request->module]);exit;
}
}
