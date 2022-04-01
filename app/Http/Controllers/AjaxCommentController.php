<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\User;
use App\UserFeed;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\MailSetting;
use Carbon\Carbon;
use App\UserNotification;
use Validator;

class AjaxCommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('VerifyUser');
    }


    public function index(Request $request){
        //GET DATABASE PREFIX VG_
        $db_pre = DB::getTablePrefix();

        $feed_id = $request->id;
        
        $name = config('custom_config.tableType');

        $commenttbl = $name[$request->type];

        $userId = Auth::user()->id;

        //CHECK IN COMMENTTABLE IF ENTRY EXISTS OR NOT
        $checkcomment = DB::table($commenttbl)
                    ->join('users','users.id','=',$commenttbl.'.user_id')
                    ->select($commenttbl.'.*', 'users.first_name', 'users.last_name','users.profile_image')
                    ->where($commenttbl.'.feed_id', '=', $feed_id)
                    ->orderBy($commenttbl.'.id','DESC')
                    ->limit(3)
                    ->get();

        if(!$checkcomment->isEmpty()){
            $output = array();
            foreach($checkcomment as $key => $value){
                array_push($output, array(
                    'user_id' => $value->user_id,
                    'user_name' => $value->first_name.' '.$value->last_name,
                    'user_image' => $value->profile_image ? Common_function::get_s3_file(config("custom_config.s3_user_big"),$value->profile_image) : url("images/userdefault-1.svg"),
                    'comment' => $value->comment,
                    'createdTime' => Common_function::get_time_ago(strtotime($value->created_at)),
                ));
            }
             //GET COMMENT COUNTS
            $commentCounts = DB::table($commenttbl)
            ->select(DB::raw('count(*) as comment_count'))
            ->where('feed_id', '=', $feed_id)
            ->get();

            $commentCount = ($commentCounts->isEmpty())?0:$commentCounts[0]->comment_count;
            $maintbl = $db_pre.''.$name[$request->maintype];

            //UPDATING USER FEED TABLE WITH COUNTS
            DB::statement("UPDATE ".$maintbl." SET comment_count = $commentCount, updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$feed_id);
            
            return response()->json(['code' => '1','module' => $request->module,'output' => $output,'commentCount'=> Common_function::like_comment_count($commentCount), 'lastId'=>$value->id]);exit;
        }


    }

    public function newComment(Request $request){
        //GET DATABASE PREFIX VG_
        $db_pre = DB::getTablePrefix();

        $feed_id = $request->id;
        
        $name = config('custom_config.tableType');

        $commenttbl = $name[$request->type];

        $userId = Auth::user()->id;

        $cmtId = DB::table($commenttbl)->insertGetId(
            ['feed_id' => $feed_id, 'user_id' => $userId, 'comment'=>strip_tags($request->comment),'created_at' => Carbon::now(), 'created_ip' => ip2long(\Request::ip())]
        );

        //CHECK IN COMMENTTABLE IF ENTRY EXISTS OR NOT
        $getcomment = DB::table($commenttbl)
                    ->select('*')
                    ->where('id', '=', $cmtId)
                    ->first();
        
        if(!empty($getcomment)){
                $user = DB::table('users')
                ->select('*')
                ->where('id', '=', $getcomment->user_id)
                ->first();

            $output =  array(
                'user_id' => $getcomment->user_id,
                'user_name' => $user->first_name.' '.$user->last_name,
                'user_image' => $user->profile_image ? Common_function::get_s3_file(config("custom_config.s3_user_big"),$user->profile_image) : url("images/userdefault-1.svg"),
                'comment' => $getcomment->comment,
                'createdTime' => Common_function::get_time_ago(strtotime($getcomment->created_at)),
            );
        }
        

        //GET COMMENT COUNTS
        $commentCounts = DB::table($commenttbl)
                    ->select(DB::raw('count(*) as comment_count'))
                    ->where('feed_id', '=', $feed_id)
                    ->get();

        $commentCount = ($commentCounts->isEmpty())?0:$commentCounts[0]->comment_count;

        $maintbl = $db_pre.''.$name[$request->maintype];

        //LAST COMMENT COUNT
        $cmtCurrentCount = ($request->currentCmtCount == 0) ? $commentCount : (1+(int)$request->currentCmtCount);

        //UPDATING USER FEED TABLE WITH COUNTS
        DB::statement("UPDATE ".$maintbl." SET comment_count = $commentCount, updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$feed_id);

        //NOTIFICATION CODE
        $userfeed = UserFeed::where('id','=',$feed_id)->first();
       
        if($userfeed->user_id != $userId ){
            $data = array(
                'user_id' => $userfeed->user_id,
                'from_id' => Auth::user()->id,
                'unique_id' => $userfeed->id,
                'message' => 'Commented on your '.config('custom_config.user_feed_type_name')[$userfeed->type_id].' Post.',
                'type_id' => $userfeed->type_id, 
                'type' =>'comment',
                'is_read' => 0,
                'created_at' => Carbon::now(),
                'created_ip' => ip2long(\Request::ip()),
            );
           UserNotification::create($data);
           
            if($userfeed->getUser->email_notification == 1){
                $mail_data = array(
                    'subject' => Auth::user()->first_name .' '. Auth::user()->last_name .' commented on your post',
                    'user_name' => $userfeed->getUser->first_name.' '.$userfeed->getUser->last_name,
                    'user_email' => $userfeed->getUser->email,
                    'sentence' => Auth::user()->first_name .' '. Auth::user()->last_name .' Commented on your '.config('custom_config.user_feed_type_name')[$userfeed->type_id].' Post',
                    'post_url' => strtolower('/'.$request->module.'/information/'.$feed_id),
                );
                Common_function::addnotification($mail_data);
            }
            
        }
        return response()->json(['code' => '1','module' => $request->module,'output' => $output,'commentCount'=> Common_function::like_comment_count($commentCount), 'lastId'=>$request->lastCmtId, 'lastCmtcount'=> $cmtCurrentCount]);exit;
    }

    public function loadMoreComment(Request $request){
        $id = $request->id;
        //GET DATABASE PREFIX VG_
        $db_pre = DB::getTablePrefix();

        $feed_id = $request->id;
        
        $name = config('custom_config.tableType');

        $commenttbl = $name[$request->type];

        $loadcomment = DB::table($commenttbl)
                    ->join('users','users.id','=',$commenttbl.'.user_id')
                    ->select($commenttbl.'.*', 'users.first_name', 'users.last_name','users.profile_image')
                    ->where($commenttbl.'.id', '<', $id)
                    ->where($commenttbl.'.feed_id', '=', $request->feed_id)
                    ->orderBy($commenttbl.'.id','DESC')
                    ->limit(3)
                    ->get();
                
        //GET COMMENT COUNTS
        $commentCounts = DB::table($commenttbl)
        ->select(DB::raw('count(*) as comment_count'))
        ->where('feed_id', '=', $request->feed_id)
        ->get();
        
        $commentCount = ($commentCounts->isEmpty())?0:$commentCounts[0]->comment_count;
        if(!$loadcomment->isEmpty())
        {
            $outputarr = array();
            foreach($loadcomment as $comment)
            {
                array_push($outputarr, array(
                    'user_id' => $comment->user_id,
                    'user_name' => $comment->first_name.' '.$comment->last_name,
                    'user_image' => $comment->profile_image ? Common_function::get_s3_file(config("custom_config.s3_user_big"),$comment->profile_image) : url("images/userdefault-1.svg"),
                    'comment' => $comment->comment,
                    'createdTime' => Common_function::get_time_ago(strtotime($comment->created_at)),
                ));
            }

            //OUTOF COMMENT ex:- 3 OF 12
            $cmmnt = DB::table($commenttbl)
            ->select('*')
            ->where([['feed_id', '=', $request->feed_id],['id', '>=', ($comment->id)]])
            ->get();

            return response()->json(['code' => '1','output' => $outputarr,'commentCount'=> Common_function::like_comment_count($commentCount),'ofCmtCount'=>$cmmnt->count(), 'lastId'=>$comment->id]);exit;
        }
    }
}
