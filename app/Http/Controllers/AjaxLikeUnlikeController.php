<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\UserFeed;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\MailSetting;
use App\UserNotification;
use Carbon\Carbon;

class AjaxLikeUnlikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
    }

    public function index(Request $request)
    {
        //GET DATABASE PREFIX VG_
        $db_pre = DB::getTablePrefix();

        $feed_id = $request->id;
        
        $name = config('custom_config.tableType');

        $liketbl = $name[$request->type];

        $userId = Auth::user()->id;

        //CHECK IN LIKETABLE IF ENTRY EXISTS OR NOT
        $checklike = DB::table($liketbl)
                    ->select('*')
                    ->where('feed_id', '=', $feed_id)
                    ->where('user_id', '=', $userId)
                    ->first();
        //ACTION IS LIKE OR DISLIKE
        $action = '';
        if(empty($checklike)){
            $action = 1;
        }else{
            if($checklike->is_like == 0){
                $action = 1;
            }else{
                $action = 0;
            }
        }
        //UPDATE IF EXISTS OR INSERT INTO LIKE TABLE
        DB::table($liketbl)
        ->updateOrInsert(
            ['user_id' => $userId, 'feed_id' => $feed_id],
            ['is_like' => $action,'created_ip' => ip2long(\Request::ip()),'updated_ip' => ip2long(\Request::ip()),'updated_at' => Carbon::now()]
        );
        //GET LIKE COUNTS
        $likeCounts = DB::table($liketbl)
                    ->select(DB::raw('count(*) as like_count, is_like'))
                    ->where('feed_id', '=', $feed_id)
                    ->where('is_like', '=', 1)
                    ->groupBy('is_like')
                    ->get();
       
        $likeCount = ($likeCounts->isEmpty())?0:$likeCounts[0]->like_count;
          
        //UPDATING USER FEED TABLE WITH COUNTS
        $updatearray = array(
            'like_count' => $likeCount,
            'updated_at' => Carbon::now(),
            'updated_ip' => ip2long(\Request::ip()),
        );
        UserFeed::where('id','=',$feed_id)->update($updatearray);

        //NOTIFICATION CODE
        $userfeed = UserFeed::where('id','=',$feed_id)->first();
        
        if($action == 1 && $userfeed->user_id != $userId){
            $data = array(
                'user_id' => $userfeed->user_id,
                'from_id' => Auth::user()->id,
                'unique_id' => $userfeed->id,
                'type' => 'like',
                'message' => ($type_name == 'first') ? 'Liked your First post.' : (($type_name == 'last') ? 'Liked your Last post.' : 'Liked your '.config('custom_config.user_feed_type_name')[$userfeed->type_id].' post.'),
                'type_id' => $userfeed->type_id,
                'is_read' => 0,
                'created_at' => Carbon::now(),
                'created_ip' => ip2long(\Request::ip()),
            );
            UserNotification::create($data);
          
            if($userfeed->getUser->email_notification == 1){
                $mail_data = array(
                    'subject' => Auth::user()->first_name .' '. Auth::user()->last_name .' liked your post',
                    'user_name' => $userfeed->getUser->first_name.' '.$userfeed->getUser->last_name,
                    'user_email' => $userfeed->getUser->email,
                    'sentence' =>($type_name == 'first') ? Auth::user()->first_name .' '. Auth::user()->last_name. 'Liked your First post. Please check post using below button' : (($type_name == 'last') ? Auth::user()->first_name .' '. Auth::user()->last_name.'Liked your Last post. Please check post using below button' : Auth::user()->first_name .' '. Auth::user()->last_name.'Liked your '.config('custom_config.user_feed_type_name')[$userfeed->type_id].' Post. Please check post using below button'),
                    'post_url' => '/'.$request->module.'/information/'.$feed_id,
                );
                Common_function::addnotification($mail_data);
            }  
        }
        return response()->json(['code' => '1','module' => $request->module,'likeCount'=> Common_function::like_comment_count($likeCount)]);exit;
    }
}
