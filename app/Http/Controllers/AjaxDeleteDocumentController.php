<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\FeedDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\UserEducationFiles;
use App\UserCareerFiles;

class AjaxDeleteDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
    }

    public function index(Request $request)
    {
        $id = $request->id;
        $file = $request->file;
        
        $feed_id = $request->feed_id;

        $db_pre = DB::getTablePrefix();
        $tbl = $db_pre.'user_'.$request->tbl;
        
        if($request->file != ''){
            Storage::disk('s3')->delete(str_replace('[id]',$feed_id,config('custom_config.s3_feed_document').$file));
        }

        FeedDocument::where('id','=',$id)->whereHas('get_feed_details', function ($query) {
            $query->whereHas('getUser',function($query){
                $query->where('id', '=', Auth::user()->id);
            });
        })->delete();

        $dcid = 'doc_'.$id;
        return response()->json(['code' => '1','docId' => $dcid]);exit;
        
    }
}
