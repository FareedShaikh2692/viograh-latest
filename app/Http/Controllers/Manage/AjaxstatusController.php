<?php
namespace App\Http\Controllers\Manage;

use App\Helpers\Common_function;
use App\Manage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AjaxstatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
    }
    public function index(Request $request)
    {
        $db_pre = DB::getTablePrefix();

        $tbl = $db_pre.''.Common_function::decrypt($request->input('tbl'));
        
        DB::statement("UPDATE ".$tbl." SET status =  (CASE WHEN status = 'enable' THEN 'disable' ELSE 'enable' End), updated_at = '".Carbon::now()."',updated_ip = ".ip2long(\Request::ip())." Where id=".$request->input('id'));
        return response()->json(['code' => '1']);exit;
    }
}
