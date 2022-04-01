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
use App\UserNotification;


class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'notification';  
            
    }
    public function index(Request  $request){

        $data = array('title' => 'Notification','module'=>$this->main_module);
        $updatearray=array('is_read' => 1);                            
        UserNotification::where('user_id','=',Auth::user()->id)->update($updatearray);

        $data['notification'] = UserNotification::where(array('user_id'=>Auth::user()->id))
                                ->orderBy('created_at','desc')
                                ->limit(5)
                                ->get(); 
        
        $data['notification_count'] = UserNotification::where(array('user_id'=>Auth::user()->id))
        ->orderBy('created_at','desc')
        ->get()->count(); 
        

        return view('notification', $data);
    }
    public function loadMoreNotification(Request $request){
        $output = array();

        $id = $request->id;
        
        $data['results'] = UserNotification::where('id', '<', $id)->where('user_id','=',Auth::user()->id)
        ->orderBy('created_at','desc')
        ->limit(5)
        ->get();
        
        if(!$data['results']->isEmpty()) {
            $html = view('include.notification-block',$data)->render();
           
            $last_rec = (array)$data['results']->toArray()[$data['results']->count() - 1];
            $lastId = $last_rec['id']; 
           
            $Remain = UserNotification::where([['id', '<', $lastId]])
                ->where('user_id','=',Auth::user()->id)
                ->get();
            
            $op = ($Remain->count() == 0) ? 0:1;
           
            return response()->json(['code' => '1','module' => $request->module, 'op'=> $op,'lastId'=>$lastId,'output'=>$output,'html'=>$html]);exit;            
        }
        else{
            return response()->json(['code' => '0']);exit;
        }
    }
}
