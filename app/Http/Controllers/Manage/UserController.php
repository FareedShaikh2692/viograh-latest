<?php

namespace App\Http\Controllers\Manage;

use App\User;
use App\UserFeed;
use App\UserMoments;
use App\UserMomentFiles;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controller\Mange;
use App\Helpers\Common_function;
use DB;
use App\UserFamilyTree;
use App\UserMilestone;
use App\UserDiary;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'User';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"User",'main_module'=>$this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = User::where('status','!=','delete');
            
        if($data['search']!=''){

            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->orWhere('first_name','LIKE','%'.$data['search'].'%' )
                ->orWhere('email', 'LIKE', '%'.$data['search'].'%');});  
        }

        $data['search_date'] =  $request->input('search_date');
        $data['search_end_date'] =  $request->input('search_end_date');
     
        if($data['search_date'] != ''){
            $start = Carbon::createFromFormat('jS M, Y', $data['search_date'])->format('Y-m-d');
            $data['results'] = $data['results']->where([[DB::raw('DATE(created_at)'),'>=',$start]]);
        }

        if($data['search_end_date'] != ''){
            $end = Carbon::createFromFormat('jS M, Y', $data['search_end_date'])->format('Y-m-d');
            $data['results'] = $data['results']->where([[DB::raw('DATE(created_at)'),'<=',$end]]);  
        }

        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'));
        $data['tbl'] = Common_function::encrypt('users');

        $data['results']->appends(array('search'=>$data['search'],'search_date'=>$data['search_date'],'search_end_date'=>$data['search_end_date']));
        return view('manage.users',$data);
    }

    public function information(Request $request,$id){
        
        $data = array('title'=>"User Information", 'main_module'=>$this->main_module,'method'=>'Information');
        $section= $request->input('section');
       
        if($section!='') {
            $data['section'] = $section;
        }
        
        $data['user'] = User::where('status','!=','delete')            
            ->where('id','=',$id)
            ->first();
        
        if(!empty($data['user'])){

            $data['common_query'] = UserFeed::where('user_id','=',$id)
                ->where('status','=','enable')            
                ->orderBy('id','DESC');
            
            $data['milestone'] = UserMilestone::whereHas('getUserMilestoneFeed',function($query) use ($data,$id){
                $query->where('status', '=','enable')->where('user_id','=',$id)->where('type_id','=',config('custom_config.user_feed_type')['milestones']); 
            })->orderBy('achieve_date','DESC')
            ->get();

            $data['diary'] = UserDiary::whereHas('getUserDiaryFeed',function($query) use ($data,$id){
                $query->where('status', '=','enable')->where('user_id','=',$id)->where('type_id','=',config('custom_config.user_feed_type')['diary']); 
            })
            ->orderBy('date','DESC')
            ->get();
            
            $data['first_data'] = UserFeed::where('user_id','=',$id)
                    ->where('status','=','enable')
                    ->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])                
                    ->whereHas('getUserFirst',function($query) use ($id){
                        $query->where('type', '=','first')                    
                            ->where('user_id','=',$id);
                    })->get(); 

            $data['last_data'] = UserFeed::where('user_id','=',$id)
                    ->where('status','=','enable')
                    ->where('type_id','=',config('custom_config.user_feed_type')['first_lasts'])                
                    ->whereHas('getUserLast',function($query) use ($id){
                        $query->where('type', '=','last')                    
                            ->where('user_id','=',$id);
                    })->get();
            
            $data['family_list'] = UserFamilyTree::where('status','=','enable')
            ->where('family_tree_id','!=','0')
            ->where('user_id','=',$id)->get();
            return view('manage.userinfo', $data);
        }
        else{
            return redirect('/manage/users');
        }
    }
}