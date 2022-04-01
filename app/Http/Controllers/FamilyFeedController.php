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
use App\UserFamilyTree;


class FamilyFeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'familyfeed';
    }
    public function index(Request $request){
        $data = array('title' => 'Family Feed','module'=>$this->main_module);
        $data['result_family_data'] = UserFamilyTree::with('getFamilyTreeUserEmail')
                    ->where('status','=','enable')
                    ->where('user_id','=',Auth::user()->id)
                    ->where('request_status','=','accept')
                    ->where('family_tree_id','!=','0')
                    ->orderBy('id','DESC')
                    ->get(); 
                  
        if(!empty($data['result_family_data'])){
            $data['resultID']=array();
            foreach( $data['result_family_data'] as $key => $row){
                $resultID= @$row->getFamilyTreeUserEmail->id;
                array_push($data['resultID'],$resultID);
            }
            $data['result_family_feed'] =  UserFeed::where(function($query) use ($data){
                $query->where('privacy', '=', 'public')
                      ->orWhere('privacy', '=', 'family');
            })->where('status','=','enable')
                ->where('user_id','!=',Auth::user()->id)
                ->where('is_save_as_draft','!=',1)
                ->whereIn('user_id',$data['resultID'])
                ->orderBy('created_at','DESC')
                ->limit(24)
                ->get();   
        }
        $data['familyfeed_count'] =  UserFeed::select('id')->where(function($query) use ($data){
            $query->where('privacy', '=', 'public')
                  ->orWhere('privacy', '=', 'family');
        })->where('status','=','enable')
            ->where('user_id','!=',Auth::user()->id)
            ->whereIn('user_id',$data['resultID'])
            ->orderBy('created_at','DESC')
            ->get();
        
        return view('family_feed',$data);
    }
    public function loadMoreFamilyFeed(Request $request){
        $output = array();

        $id = $request->id;
        
        $public_profile_module = $request->public_profile_module;
        $user_id = $public_profile_module ? $request->user_id : Auth::user()->id;
       
        $data['result_family_data'] = UserFamilyTree::with('getFamilyTreeUserEmail')
        ->where('status','=','enable')
        ->where('user_id','=',Auth::user()->id)
        ->where('family_tree_id','!=','0')
        ->orderBy('id','DESC')
        ->get(); 
        
        $data['resultID']=array();
        foreach( $data['result_family_data'] as $key => $row){
            $resultID= @$row->getFamilyTreeUserEmail->id;
            array_push($data['resultID'],$resultID);
        }
        $data['results'] =  UserFeed::where(function($query) use ($data){
            $query->where('privacy', '=', 'public')
                    ->orWhere('privacy', '=', 'family');
        })->where('status','=','enable')
            ->where('id', '<', $id)
            ->where('is_save_as_draft','!=',1)
            ->where('user_id','!=',Auth::user()->id)
            ->whereIn('user_id',$data['resultID'])
            ->orderBy('id','DESC');
         
        if($public_profile_module){            
            $data['results'] =  $data['results']->where('privacy','=','public');
        }
       
        $data['results'] =  $data['results'] 
                    ->limit(24)
                    ->get(); 
                    
        if(!$data['results']->isEmpty())
        {
            $html = view('include.home-family-block',$data)->render();
          
            $last_rec = (array)$data['results']->toArray()[$data['results']->count() - 1];
            $lastId = $last_rec['id'];

            $Remain =  UserFeed::where('id', '<', $lastId)->where(function($query) use ($data){
                    $query->where('privacy', '=', 'public')
                            ->orWhere('privacy', '=', 'family');
                })->where('status','=','enable')
                    ->where('user_id','!=',Auth::user()->id)
                    ->whereIn('user_id',$data['resultID'])
                    ->orderBy('created_at','DESC')
                    ->get();
          
            $op = ($Remain->count() == 0) ? 0 : 1;
          
            return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$lastId,'output'=>$output,'html'=>$html]);exit;            
        }
        else{
            return response()->json(['code' => '0']);exit;
        }
    }
}
