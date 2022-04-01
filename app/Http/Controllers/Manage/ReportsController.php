<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\User;
use DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request){
        $data = array('title'=> 'Reports');
        return view('manage.reports',$data);
    }
    public function UserReportExport($request) 
    {
        $results = User::where('status','!=','delete')
            ->where('profile_privacy','=','public')
            ->orderBy('first_name','ASC');

        $data['search'] = $request->input('search');
        //echo '<pre>';print_r($data['search']);echo '</pre>';exit();
        
        if($data['search']!=''){

            $results = $results->where(function($query) use ($data){
                $query->orWhere('first_name','LIKE','%'.$data['search'].'%' )
                ->orWhere('email', 'LIKE', '%'.$data['search'].'%');});  
        }

        $data['search_date'] =  $request->input('search_date');
        $data['search_end_date'] =  $request->input('search_end_date');
     
        if($data['search_date'] != ''){
            $start = date('Y-m-d',strtotime($data['search_date']));
            $results = $results->where([[DB::raw('DATE(created_at)'),'>=',$start]]);
        }

        if($data['search_end_date'] != ''){
            $end = date('Y-m-d',strtotime($data['search_end_date']));
            $results = $results->where([[DB::raw('DATE(created_at)'),'<=',$end]]);
        }
        
        if($data['search'] == '' && $data['search_date'] == '' && $data['search_end_date'] == ''){
            $end = date('Y-m-d'); 
            $start = date('Y-m-d', strtotime('-6 months'));
            $results = $results->where([[DB::raw('DATE(created_at)'),'>=',$start],[DB::raw('DATE(created_at)'),'<=',$end]]); 
        }
        
        $results = $results->get();
        $user_data = array();

        foreach($results as $key=>$val){

        array_push($user_data,array(
            'first_name' => $val->first_name,
            'last_name' => $val->last_name,
            'gender' => $val->gender,
            'blood_group' => $val->blood_group,
            'email' => $val->email,
            'phone_number' => $val->phone_number,
            'birth_date' => $val->birth_date,  
            'profession' => $val->profession,          
            'places_lived' => $val->places_lived,
            'address' => $val->address,
            'place_of_birth' => $val->place_of_birth,
            'favourite_movie' => $val->favourite_movie,
            'favourite_song' => $val->favourite_song,
            'favourite_book' => $val->favourite_book,
            'favourite_eating_joints' => $val->favourite_eating_joints,
            'hobbies' => $val->hobbies,
            'food' => $val->food,
            'role_model' => $val->role_model,
            'car' => $val->car,
            'brand' => $val->brand,
            'tv_shows' => $val->tv_shows,
            'actors' => $val->actors,
            'sports_person' => $val->sports_person,
            'politician' => $val->politician,
            'diet' => $val->diet,
            'zodiac_sign' => $val->zodiac_sign,
            'created_at' => date_format( date_create($val->created_at), 'd-m-Y h:i:s' ) ,
        ));            
        }           
        return $user_data;        
    }
    public function export(Request $request){
        return Excel::download(new UsersExport($this->UserReportExport($request)), 'users.xlsx');
    }
}
