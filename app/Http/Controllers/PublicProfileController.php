<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\UserFeed;
use App\UserEducation;
use App\UserCareer;
use App\UserMomentFiles;
use App\UserMoments;
use App\UserExperience;
use App\UserDiary;
use App\UserWishList;
use App\UserFamilyTree;
use App\UserMilestone;
use DB;

class PublicProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');                   
    }

    public function index(Request $request,$id)
     {      
        $data = array('user_id'=> $id,'mainTable'=>1,'public_profile_module'=>'public-profile');
        $data['results'] = User::where('id','=',$id)
                ->where('status','=','enable')               
                ->first();  
        if($data['results'] != ''){
            //CHECK FOR USER IN FAMILY TREE                     
            $check_family_tree = UserFamilyTree::where('user_id','=', Auth::user()->id)
                ->where('family_tree_id','!=','0')
                ->where('status','=','enable')
                ->where('request_status','=','accept')
                ->where('email','=', $data['results']->email)
                ->first();
           
            //CHECK PROFILE PRIVACY IN USER TABLE
            $allow = false;
            if($data['results']->profile_privacy == 'public'){
                $allow = true;
            }
            else if($data['results']->profile_privacy == 'family'){    
                if(!empty($check_family_tree)){
                    $allow = true;
                }
            }
            if($id == Auth::user()->id){
                $allow = true; 
            }
            
            if($allow == false){
                $data['title'] = 'Profile - '.$data['results']->first_name.' '.$data['results']->last_name;
                return view('public_profile_excess_denide',$data); 
            }
            else { 
                    $data['title'] = 'Profile - '.$data['results']->first_name.' '.$data['results']->last_name;

                    $data['education_data'] = UserEducation::whereHas('getUserEducationFeed',function($query) use ($id,$check_family_tree){ 
                            $query->where('status', '=','enable')
                                ->where('user_id','=',$id)      
                                ->where('is_save_as_draft','!=','1')             
                                ->where('type_id','=',config('custom_config.user_feed_type')['education']);

                                if( $id != Auth::user()->id){
                                    if(!empty($check_family_tree)){
                                        $query = $query->where('privacy', '!=', 'private');
                                    }
                                    else{
                                        $query = $query->where('privacy', '=', 'public');
                                    }                    
                                }
                    })
                    ->where('user_id','=',$id)
                    ->orderBy('start_date','DESC')
                    ->get();             
                    
                    $data['career_data'] = UserCareer::whereHas('getUserCareerFeed',function($query) use ($id,$check_family_tree){
                            $query->where('status', '=','enable')
                                ->where('user_id','=',$id)  
                                ->where('is_save_as_draft','!=','1')
                                ->where('type_id','=',config('custom_config.user_feed_type')['career']);

                                if($id != Auth::user()->id){
                                    if(!empty($check_family_tree)){
                                        $query = $query->where('privacy', '!=', 'private');
                                    }
                                    else{
                                        $query = $query->where('privacy', '=', 'public');
                                    }       
                                }                       
                    })
                    ->where('user_id','=',$id)
                    ->orderBy('start_date','DESC')
                    ->get();

                    

                    //GET SECTIONS
                    $section= $request->input('section');        
                    if($section!='') {
                        $data['section'] = $section;
                    }    

                    //MILESTONE DATA
                    $data['milestone_data'] = UserMilestone::whereHas('getUserMilestoneFeed',function($query) use ($id,$check_family_tree){
                        $query->where('status','=','enable')
                        ->where('user_id','=',$id)      
                        ->where('is_save_as_draft','!=','1')                      
                        ->where('type_id','=',config('custom_config.user_feed_type')['milestones']);

                        if($id != Auth::user()->id){
                            if(!empty($check_family_tree)){
                                $query = $query->where('privacy', '!=', 'private');
                            }
                            else{
                                $query = $query->where('privacy', '=', 'public');
                            } 
                        }                              
                    })
                    ->where('user_id','=',$id)
                    ->orderBy('achieve_date','DESC');
                
                    $data['milestone_count'] = clone $data['milestone_data'];
                    $data['milestone_data_tab'] = clone $data['milestone_data'];
                    $data['milestone_data_tab']= $data['milestone_data_tab']->get();
                    $data['milestone_data'] = $data['milestone_data']->offset(0)->limit(4)->get(); 
                    $data['offset']=4;
                    $data['milestone_count']= $data['milestone_count']->count();
                    //COMMON QUERY
                    $data['common_query'] = UserFeed::where('user_id','=',$id)
                            ->where('status','=','enable')
                            ->where('is_save_as_draft','!=','1');
                
                    $data['resultcount'] =  UserFeed::select('type_id', DB::raw('count(id) as total'))
                        ->where('is_save_as_draft','!=','1')
                        ->where('user_id','=',$id)
                        ->where('status','=','enable');

                    if($id != Auth::user()->id){
                        if(!empty($check_family_tree)){
                            $data['common_query'] = $data['common_query']->where('privacy', '!=', 'private');
                            $data['resultcount'] = $data['resultcount']->where('privacy', '!=', 'private');
                        }
                        else{
                            $data['common_query'] = $data['common_query']->where('privacy', '=', 'public');
                            $data['resultcount'] = $data['resultcount']->where('privacy', '=', 'public');
                        }
                    }

                    $data['common_query'] = $data['common_query']->orderBy('id','DESC')->
                    offset(0)->limit(4);
                    $data['offset']=4;

                    if($section == 'Diary'){     
                      
                        $data['diary_data'] = UserDiary::whereHas('getUserDiaryFeed',function($query) use ($id,$check_family_tree){
                                $query->where('status','=','enable')
                                ->where('user_id','=',$id)  
                                ->where('is_save_as_draft','!=','1')                          
                                ->where('type_id','=',config('custom_config.user_feed_type')['diary']);
                               
                                if($id != Auth::user()->id){
                                    if(!empty($check_family_tree)){
                                        $query = $query->where('privacy', '!=', 'private');
                                    }
                                    else{
                                        $query = $query->where('privacy', '=', 'public');
                                    } 
                                }                              
                        })
                        ->where('user_id','=',$id)
                        ->orderBy('date','DESC');
                        
                        $data['diary_count'] = clone $data['diary_data'];
                        $data['diary_data'] = $data['diary_data']->offset(0)->limit(4)->get();
                        $data['offset'] = 4;
                        $data['diary_count']=$data['diary_count']->count();
                    }
                    elseif($section == 'Experiences'){           
                        $data['experience_data'] =  $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['experiences'])
                                ->get();
                        
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['experiences']);
                    }  
                    elseif($section == 'Ideas'){
                        $data['idea_data'] =  $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['idea'])
                            ->get();

                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['idea']);
                    } 
                    elseif($section == 'Dreams'){
                        $data['dreams_data'] =  $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['dreams'])
                        ->get();
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['dreams']);
                    }  
                    elseif($section == 'Moments'){
                        $data['moments_data'] = $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['moments'])
                        ->get();
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['moments']);
                    } 
                    elseif($section == 'Life'){
                        $data['life_lessons_data'] = $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['life_lessons'])
                        ->get();
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['life_lessons']);
                    }  
                    elseif($section == 'Firsts'){
                        $data['first_data'] = $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])                
                        ->whereHas('getUserFirst',function($query) use ($id){
                            $query->where('type', '=','first')                    
                                ->where('user_id','=',$id);
                        })->get(); 
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                        ->whereHas('getUserFirst',function($query) use ($id){
                            $query->where('type', '=','first')                    
                                ->where('user_id','=',$id);
                        });
                    } 
                    elseif($section == 'Lasts'){               
                        $data['last_data'] =  $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])                           
                        ->whereHas('getUserLast',function($query) use ($id){
                            $query->where('type', '=','last')                    
                                ->where('user_id','=',$id);
                        })->get();
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])
                        ->whereHas('getUserLast',function($query) use ($id){
                            $query->where('type', '=','last')                    
                                ->where('user_id','=',$id);
                        });
                    } 
                    elseif($section == 'Spiritual'){
                        $data['spiritual_journeys_data'] = $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['spiritual_journeys'])
                        ->get();
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['spiritual_journeys']);
                    }
                    elseif($section == 'Milestone'){    
                        $data['milestone_data']  =  $data['milestone_data'];
                       /*  $data['milestone_data'] = UserMilestone::whereHas('getUserMilestoneFeed',function($query) use ($id,$check_family_tree){
                                $query->where('status','=','enable')
                                ->where('user_id','=',$id)      
                                ->where('is_save_as_draft','!=','1')                      
                                ->where('type_id','=',config('custom_config.user_feed_type')['milestones']);

                                if($id != Auth::user()->id){
                                    if(!empty($check_family_tree)){
                                        $query = $query->where('privacy', '!=', 'private');
                                    }
                                    else{
                                        $query = $query->where('privacy', '=', 'public');
                                    } 
                                }                              
                        })
                        ->where('user_id','=',$id)
                        ->orderBy('achieve_date','DESC');
                      
                        $data['milestone_count'] = clone $data['milestone_data'];
                        $data['milestone_data']=$data['milestone_data']->offset(0)->limit(4)->get(); 
                        $data['offset']=4;
                        $data['milestone_count']= $data['milestone_count']->count(); */
                    }
                    else{                      
                        $data['wishlist_data'] = $data['common_query']->where('type_id','=',config('custom_config.user_feed_type')['wish_list'])
                            ->get();                           
                        $data['resultcount'] = $data['resultcount']->where('type_id','=',config('custom_config.user_feed_type')['wish_list']);
                    }
                    
                    $data['resultcount'] = $data['resultcount']->groupBy('type_id')->first();
                    return view('public_profile', $data); 
                }
        }else{
            return redirect()->route('index');
        } 
    }
}
