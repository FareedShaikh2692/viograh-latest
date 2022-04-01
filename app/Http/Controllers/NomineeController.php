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
use App\User;

class NomineeController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'nominee'; 
    }
    public function index(Request $request)
    {
        $data['results'] = $this->common_query();
        if($data['results']->password == '' && Common_function::passwordProtectedNetworth($request)==false){
            $this->generate_otp($request);
        }
        if( Common_function::passwordProtectedNetworth($request)==true ){ 
            $data = array('title' => 'Nominees added by me','module'=>$this->main_module,);
            $data['assetCount'] = UserAsset::whereHas('getAssetUser',function($query) use($data){
                $query->where('status','=','enable');
            })->select('id')
            ->where('user_id','=',Auth::user()->id)
            ->where('is_save_as_draft','!=',1)
            ->where('status','=','enable')
            ->orderBy('id','DESC')->get();
            
            $data['results'] = UserAsset::whereHas('getAssetUser',function($query) use($data){
                $query->where('status','=','enable');
            })->where('status','=','enable')
                            ->where('user_id','=',Auth::user()->id)
                            ->Where('nominee_email','!=',null)
                            ->where('is_save_as_draft','!=',1)
                            ->Where('nominee_name','!=',null)
                            ->orderBy('id','DESC')->get(); 
            if(!empty($data['results'])){
                return view('nominee',$data);
            }else{
                return redirect()->route('nominee.index');
            }
            
        }else{
            return view('password_protected_networth',$data);
        }
    }
   
    public function myself_added(Request $request)
    {
        $data['results'] = $this->common_query();
        if($data['results']->password == '' && Common_function::passwordProtectedNetworth($request)==false){
            $this->generate_otp($request);
        }
        if( Common_function::passwordProtectedNetworth($request)==true ){  
            $data = array('title' => 'Where I am added as nominee','module'=>$this->main_module,);
            $data['assetCount'] = UserAsset::select('id')
            ->where('nominee_email','=',Auth::user()->email)
            ->where('is_save_as_draft','!=',1)
            ->where('status','=','enable')
            ->orderBy('id','DESC')->get();
        
            $data['results'] = UserAsset::whereHas('getAssetUser',function($query) use($data){
                $query->where('status','=','enable');
            })->where('status','=','enable')
            ->where('is_save_as_draft','!=',1)
            ->where('nominee_email','=',Auth::user()->email)
            ->orderBy('id','DESC')->get(); 
          
            if(empty($data['results'])  ){
                return redirect()->route('nominee.myself_added');
            }else{
                return view('nominee',$data);
            }
        }else{
            return view('password_protected_networth',$data);
        }       
    }
    public function common_query(){
        $result = User::where('status','=','enable') 
        ->where('id','=',Auth::user()->id)       
        ->first();
        return $result;
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
    }
    
}
