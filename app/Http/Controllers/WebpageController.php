<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WebPages;
use Session;
use App\User;
use Auth;

class WebpageController extends Controller
{
    public function index(Request $request)
    {
      $slug = request()->segment(1);
      if(request()->segment(1) == 'p')
      {
        $slug = request()->segment(2);
      }

       if($slug != '')
       {
       		$data['webpage'] = WebPages::where([['slug','=',$slug],['status', '!=','Delete']])->get();
       }

       if(!$data['webpage']->isEmpty())
       {
          $data['webpage'] = $data['webpage'][0];
          $data['title'] = ucwords($data['webpage']->page_title);
          $data['sessiondata'] = Auth::user();
       		return view('webpage',$data);
       }	
       else
       {
       		return redirect('/');
       }

    }
}