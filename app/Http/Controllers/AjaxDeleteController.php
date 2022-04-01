<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Common_function;
use App\UserFeed;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AjaxDeleteController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
    }

    public function index(Request $request)
    {     
        $db_pre = DB::getTablePrefix();

        $feed_id = $request->id;
        
        $name = config('custom_config.tableType');

        $tbl = $db_pre.''.$name[$request->type];
        
        DB::statement("UPDATE ".$tbl." SET status = 'delete', updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$feed_id);
        return response()->json(['code' => '1','module' => $request->module]);exit;

    }
}
