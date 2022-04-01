<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\UserFamilyTree;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {           
            $data = array('title' => 'Search', 'module'=>'Search');
            $data['search'] = $request->input('search');
            if($data['search']!=''){
                try{
                    $data['results'] = User::where('status','=','enable')
                            ->where(DB::raw('concat(first_name," ",last_name)'),'LIKE','%'.$data['search'].'%');
                           
                    $data['results'] = $data['results']->where(function ($query){
                        $query->where('profile_privacy','=','public') 
                            ->orWhere(function ($q2)  {
                                $q2->where('profile_privacy','=','family')
                                    ->whereRaw('EXISTS(SELECT * FROM vg_user_family_trees WHERE vg_users.email = vg_user_family_trees.email AND vg_user_family_trees.request_status = "accept" AND vg_user_family_trees.status = "enable" AND vg_user_family_trees.family_tree_id != 0 AND vg_user_family_trees.user_id = '.Auth::user()->id.')');
                                    
                            });
                    });
                   
                    $searchCount  = clone $data['results'] ;  
                    $data['results_total']   = $searchCount->count();
                    $data['results'] =  $data['results']->limit(4)->offset(0)->orderBy('id','ASC')->get();
                    $data['offset']=4;              
                }catch(Exception $e){
                    
                }
            }                                                       
            return view('search_result',$data);            
    }

    public function loadMoreSearch(Request $request){
        $output = array();
        $offset = $request->id;       
        $input_text = @$request->search_inputed_text;
      
        if(@$input_text != ''){    
            try{
                $data['results'] = User::where('status','=','enable')
                            ->where(DB::raw('concat(first_name," ",last_name)'),'LIKE','%'.@$input_text.'%');
                           
                $data['results'] = $data['results']->where(function ($query){
                    $query->where('profile_privacy','=','public') 
                        ->orWhere(function ($q2)  {
                            $q2->where('profile_privacy','=','family')
                                ->whereRaw('EXISTS(SELECT * FROM vg_user_family_trees WHERE vg_users.email = vg_user_family_trees.email AND vg_user_family_trees.request_status = "accept" AND vg_user_family_trees.status = "enable" AND vg_user_family_trees.family_tree_id != 0 AND vg_user_family_trees.user_id = '.Auth::user()->id.')');
                                
                        });
                })->where('status','=','enable') 
                ->orderBy('id','ASC')
                ->offset($offset)
                ->limit(4)->get();
                
                if(!$data['results']->isEmpty()) {
                    $html = view('include.search-block',$data)->render();
                    $op = ($data['results']->count() < 4) ? 0:1;

                    return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$offset+4,'output'=>$output,'html'=>$html]);exit;            
                }
                else{
                    return response()->json(['code' => '0']);exit;
                }
            }catch(Exception $e){
                    
            }
        }
    }
}
