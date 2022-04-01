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
use App\UserFamilyTree;


class FamilyListController extends Controller
{
    public function __construct()
    {
        $this->middleware('VerifyUser');
        $this->main_module = 'familylist';
    }
    public function index(Request $request){
        $data = array('title' => 'Family List','module'=>$this->main_module);
       
        $data['results'] = UserFamilyTree::with('getFamilyTreeUserEmail')->where('status','=','enable')
                        ->where('family_tree_id','!=','0')
                        ->where('user_id','=',Auth::user()->id)
                        ->orderBy('id','DESC')
                        ->get();
        return view('family_list',$data);
    }
}
