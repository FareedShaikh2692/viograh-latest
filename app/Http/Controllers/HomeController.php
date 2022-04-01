<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Helpers\Common_function;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Manage;
use App\User;
use App\UserCareer;
use App\UserFeed;
use App\UserDiary;
use App\UserFamilyTree;
use App\UserEducation;
use App\UserFirstLast;
use App\UserMilestone;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('VerifyUser');
        
    }

    public function index(Request $request)
    {
        $data = array('title' => 'Home');
        
        //MOMENTS RESULT
        $data['result_moments'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['moments'])
                                ->where('user_id','=',Auth::user()->id)
                                ->where('is_save_as_draft','!=',1)
                                ->where('status','=','enable')
                                ->orderBy('id','DESC')->limit(3)->get();

        //DIARY RESULT
        $data['results'] = UserDiary::orderBy('date','DESC')->limit(3);

        $data['result_diary'] = $data['results']->whereHas('getUserDiaryFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)
                ->where('is_save_as_draft','!=',1)->where('type_id','=',config('custom_config.user_feed_type')['diary']); 
        })->get();

        //DIARY RESULT COUNT

        $data['result_count_diary'] = UserDiary::orderBy('date','DESC');

        $data['result_diary_count'] = $data['result_count_diary']->whereHas('getUserDiaryFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)
                ->where('is_save_as_draft','!=',1)->where('type_id','=',config('custom_config.user_feed_type')['diary']); 
        })->get();

        //MILESTONE RESULT
        $data['results'] = UserMilestone::orderBy('achieve_date','DESC')->limit(3);

        $data['result_milestone'] = $data['results']->whereHas('getUserMilestoneFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)
                ->where('is_save_as_draft','!=',1)->where('type_id','=',config('custom_config.user_feed_type')['milestones']); 
        })->get();
       
        //MILESTONE RESULT COUNT

        $data['result'] = UserMilestone::orderBy('achieve_date','DESC');

        $data['result_milestone_count'] = $data['result']->whereHas('getUserMilestoneFeed',function($query) use ($data){
                $query->where('status', '=','enable')->where('user_id','=',Auth::user()->id)
                ->where('is_save_as_draft','!=',1)->where('type_id','=',config('custom_config.user_feed_type')['milestones']); 
        })->get();
    
        //EXPERIANCE RESULT
        $data['result_experience'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['experiences'])
                                ->where('user_id','=',Auth::user()->id)
                                ->where('is_save_as_draft','!=',1)
                                ->where('status','=','enable')
                                ->orderBy('id','DESC')->limit(3)->get();

        //WISHLIST RESULT
        $data['result_wishlist'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['wish_list'])
                                ->where('user_id','=',Auth::user()->id)
                                ->where('is_save_as_draft','!=',1)
                                ->where('status','=','enable')
                                ->orderBy('id','DESC')->limit(3)->get(); 
                                
        //IDEA RESULT
        $data['result_idea'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['idea'])
                            ->where('user_id','=',Auth::user()->id)
                            ->where('is_save_as_draft','!=',1)
                            ->where('status','=','enable')
                            ->orderBy('id','DESC')->limit(3)->get(); 

        //DREAMS RESULT
        $data['result_dreams'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['dreams'])
                            ->where('user_id','=',Auth::user()->id)
                            ->where('is_save_as_draft','!=',1)
                            ->where('status','=','enable')
                            ->orderBy('id','DESC')->limit(3)->get(); 

        //LIFE LESSON RESULT
        $data['result_life'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['life_lessons'])
                            ->where('user_id','=',Auth::user()->id)
                            ->where('is_save_as_draft','!=',1)
                            ->where('status','=','enable')
                            ->orderBy('id','DESC')->limit(3)->get(); 

        //SPIRITUAL RESULT
        $data['result_spiritual'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['spiritual_journeys'])
                            ->where('user_id','=',Auth::user()->id)
                            ->where('is_save_as_draft','!=',1)
                            ->where('status','=','enable')
                            ->orderBy('id','DESC')->limit(3)->get(); 

        //COMMON COUNT FOR VIEWALL BUTTON
        $resultcount = UserFeed::select('type_id', DB::raw('count(*) as total'))
                            ->where('status','=','enable')
                            ->where('is_save_as_draft','!=',1)
                            ->where('user_id','=',Auth::user()->id)
                            ->groupBy('type_id')
                            ->get();
     
        $data['resultcount']=array();
        foreach($resultcount as $key => $row){
            $data['resultcount'][$row->type_id] = $row->total;
        }
        
        //FIRST MODULE RESULTS
        $data['userFirstList'] = UserFirstLast::select('feed_id')->where('type','=','first')
                            ->where('user_id','=',Auth::user()->id)
                            ->orderBy('feed_id','DESC')->get();

        $feedIds = array();
        foreach($data['userFirstList'] as $val){
            array_push($feedIds, $val->feed_id);
        }

        $data['result_first'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                            ->where('status','=','enable')
                            ->where('user_id','=',Auth::user()->id)
                            ->where('is_save_as_draft','!=',1)
                            ->whereIn('id',$feedIds)
                            ->orderBy('id','DESC')
                            ->limit(3)
                            ->get();
        
        //FIRT COUNT FOR VIEW ALL BUTTON
        $data['firstCount'] = UserFeed::select('id')->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                        ->where('status','=','enable')
                        ->where('user_id','=',Auth::user()->id)
                        ->where('is_save_as_draft','!=',1)
                        ->whereIn('id',$feedIds)
                        ->orderBy('id','DESC')->get()->count();
      
        //LAST MODULE RESULTS
        $data['userLastList'] = UserFirstLast::select('feed_id')
                        ->where('type','=','last')
                        ->where('user_id','=',Auth::user()->id)
                        ->orderBy('feed_id','DESC')->get();
                    
        $feedIds = array();
        foreach($data['userLastList'] as $val){
            array_push($feedIds, $val->feed_id);
        }

        $data['result_last'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                    ->where('status','=','enable')
                    ->where('user_id','=',Auth::user()->id)
                    ->where('is_save_as_draft','!=',1)
                    ->whereIn('id',$feedIds)
                    ->orderBy('id','DESC')->limit(3)->get();

        //LAST COUNT FOR VIEW ALL BUTTON
        $data['lastCount'] = UserFeed::select('id')->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                    ->where('status','=','enable')
                    ->where('user_id','=',Auth::user()->id)
                    ->where('is_save_as_draft','!=',1)
                    ->whereIn('id',$feedIds)
                    ->orderBy('id','DESC')->get()->count();

        //EDUCATION RESULT
        $data['result_education'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['education'])
                    ->where('user_id','=',Auth::user()->id)
                    ->where('is_save_as_draft','!=',1)
                    ->where('status','=','enable')
                    ->orderBy('id','DESC')->limit(3)->get();
     
        //CAREER RESULT
        $data['results_career'] = UserFeed::where('type_id','=',config('custom_config.user_feed_type')['career'])
                    ->where('user_id','=',Auth::user()->id)
                    ->where('is_save_as_draft','!=',1)
                    ->where('status','=','enable')
                    ->orderBy('id','DESC')->limit(3)->get(); 

        // SHOW FAMILY MEMBER
        $data['result_family'] = UserFamilyTree::where([['status', '=','enable'],['family_tree_id','!=','0'],['user_id','=',Auth::user()->id]]);        
        $mytempClone  = clone $data['result_family'] ;  
        $data['result_family_count']   = $mytempClone->count();
        $data['result_family'] =  $data['result_family']->limit(4)->get();
       // FOR RESULT
        $data['result_family_data'] = UserFamilyTree::with('getFamilyTreeUserEmail')
                    ->where('status','=','enable')
                    ->where('request_status','=','accept')
                    ->where('user_id','=',Auth::user()->id)
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
                ->where('is_save_as_draft','!=',1)
                ->where('user_id','!=',Auth::user()->id)
                ->whereIn('user_id',$data['resultID'])
                ->orderBy('created_at','DESC')
                ->limit(4)
                ->get();  

             //GET FEED COUNTS
            $data['feedCounts']  =  UserFeed::select('id')->where(function($query) use ($data){
                $query->where('privacy', '=', 'public')
                        ->orWhere('privacy', '=', 'family');
            })->where('status','=','enable')
                ->where('user_id','!=',Auth::user()->id)
                ->whereIn('user_id',$data['resultID'])
                ->orderBy('created_at','DESC')
                ->get()
                ->count();
              
        }
        $data['result_myelf'] = User::where('status','=','enable')
                ->where('id','=',Auth::user()->id)          
                ->first();                  
        
        return view('home', $data);
    }
}
