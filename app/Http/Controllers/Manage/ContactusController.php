<?php

namespace App\Http\Controllers\Manage;

use App\ContactUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controller\Mange;
use App\Helpers\Common_function;
use DB;


class ContactusController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Contact Us';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Contact Us",'main_module'=>$this->main_module);

        $data['search'] = $request->input('search');
        $data['results'] = ContactUs::where('status','!=','delete');
            
        if($data['search']!=''){

            $data['results'] = $data['results']->where(function($query) use ($data){
                $query->orWhere('name','LIKE','%'.$data['search'].'%')               
                ->orWhere('email', 'LIKE', '%'.$data['search'].'%');});  
                
                
        }

        $data['search_date'] =  $request->input('search_date');
        if($data['search_date']!='')
        {
            $dates = explode('-',$request->input('search_date'));
            if(count($dates) == 2)
            {
                $search_from_date = $dates[0];
                $search_to_date = $dates[1];
                
                if($search_from_date!='' && $search_to_date!=''){
                    $start_date = str_replace('/','-',$search_from_date);
                    $start = date('Y-m-d',strtotime($start_date));
                    
                    $end_date = str_replace('/','-',$search_to_date);
                    $end = date('Y-m-d',strtotime($end_date));
                }                      
                $data['results'] = $data['results']->where([[DB::raw('DATE(created_at)'),'>=',$start],[DB::raw('DATE(created_at)'),'<=',$end]]);
            }
        }
        
        $data['results'] =  $data['results']->orderBy('id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'));
        $data['tbl'] = Common_function::encrypt('contact_us');

        $data['results']->appends(['search'=>$data['search']], ['search_date'=>$data['search_date']]);
        return view('manage.contact_us',$data);
    }

    public function information($id){
        $data = array('title'=>"Contact Us Information", 'main_module'=>$this->main_module,'method'=>'Information');

        $update_array = array(
            'is_read' => 1,
        );
        ContactUs::where('id','=',$id)
            ->update($update_array);
            
        $data['contactus'] = ContactUs::where('status','!=','delete')            
            ->where('id','=',$id)
            ->limit(1)->first();

        if(!empty($data['contactus'])) {
            return view('manage.contact_usinfo', $data);
        }
        else{
            return redirect('/manage/contact-us');
        }
    }
}