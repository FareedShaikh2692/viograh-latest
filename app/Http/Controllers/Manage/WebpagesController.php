<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WebPages;
use Auth;
use Common_function;
use Carbon\Carbon;

class WebpagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('manage');
        $this->main_module = 'Web Page';
    }
    public function index(Request $request)
    {
        $data = array('title'=>"Web Pages",'main_module'=>$this->main_module);
        $data['tbl'] = Common_function::encrypt('web_pages');

        $data['results'] = WebPages::where('Status','!=','Delete')->orderBy('Id','DESC')->paginate(config('custom_config.settings.admin_pagination_limit'))->onEachSide(2);

        return view('manage.webpages',$data);
    }

    public function add(){
        $data = array('title'=>"Add Web Page",'main_module'=>$this->main_module,'method'=>'Add','action'=>url('manage/web-pages/insert'),'frm_id'=>'frm_webpages_add');
        return view('manage.webpages_edit', $data);
    }

    public function insert(Request $request){
        // VALIDATION RULE
        $validation_array = array(
            'title' => 'required|max:100',
            'heading' => 'required|max:100',
            'meta_key'=>'required|max:100',
            'meta_desc'=>'required|max:500',
            'page_content'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'heading.required' => 'The heading is required',
            'heading.max' => 'Maximum 100 characters allowed for heading',            
            'meta_key.required' => 'The meta key is required',
            'meta_key.max' => 'Maximum 100 characters allowed for meta key',
            'meta_desc.required' => 'The meta desc is required',
            'meta_desc.max' => 'Maximum 500 characters allowed for meta desc',
            'page_content.required' => 'The page content is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // GENERATE UNIQUE SLUG
        $slug = str_replace(' ','-',strtolower($request->heading));
        $slug_res = WebPages::where('status','!=',"delete")
            ->where('page_title','LIKE','%'.$request->heading)
            ->orWhere('slug','=',$slug)
            ->get()->count();

        $slug = ($slug_res==0)?$slug:$slug.'-'.$slug_res;

        // INSERT ARRAY
        $insert_array = array(
            'slug'=>$slug,
            'page_title'=>$request->title,
            'page_heading'=>$request->heading,
            'meta_tag'=>$request->meta_key,
            'meta_description'=>$request->meta_desc,
            'page_content'=>$request->page_content,
            'status'=>'enable',
            'created_by'=>Auth::guard('manage')->user()->id,
            'updated_by'=>'0',
            'created_ip'=>ip2long(\Request::ip()),
            'created_at'=>Carbon::now(),
            'updated_at' => Carbon::now(),
        );

        WebPages::insert($insert_array);

        return redirect('/manage/web-pages')->withSuccess(trans('adminlte::custom.succ_webpage_added'));
    }

    public function edit($id){
        $data = array('title'=>"Edit Web Page",'main_module'=>$this->main_module,'method'=>'Edit','action'=>url('manage/web-pages/update/'.$id),'frm_id'=>'frm_webpages_edit');

        $data['webpage'] = WebPages::where('Status','!=','Delete')->where('id','=',$id)->limit(1)->first();

        if(!empty($data['webpage'])) {
            return view('manage.webpages_edit', $data);
        }
        else{
            return redirect('/manage/web-pages');
        }
    }

    public function update(Request $request,$id){
        // VALIDATION RULE
        $validation_array = array(
            'title' => 'required|max:100',
            'heading' => 'required|max:100',
            'meta_key'=>'required|max:100',
            'meta_desc'=>'required|max:500',
            'page_content'=>'required',
        );
        $rules = [
            'title.required' => 'The title is required',
            'title.max' => 'Maximum 100 characters allowed for title',
            'heading.required' => 'The heading is required',
            'heading.max' => 'Maximum 100 characters allowed for heading',
            'meta_key.required' => 'The meta key is required',
            'meta_key.max' => 'Maximum 100 characters allowed for meta key',
            'meta_desc.required' => 'The meta desc is required',
            'meta_desc.max' => 'Maximum 500 characters allowed for meta desc',
            'page_content.required' => 'The page content is required',
        ];
        // CHECK SERVER SIDE VALIDATION
        $this->validate($request,$validation_array,$rules);

        // UPDATE ARRAY
        $update_array = array(
            'page_title'=>$request->title,
            'page_heading'=>$request->heading,
            'meta_tag'=>$request->meta_key,
            'meta_description'=>$request->meta_desc,
            'page_content'=>$request->page_content,
            'updated_at' => Carbon::now(),
            'updated_ip' => ip2long(\Request::ip()),
            'updated_by' => auth()->guard('manage')->id(),
        );
        WebPages::where('id','=',$id)->update($update_array);

        return redirect('/manage/web-pages')->with('success',trans('adminlte::custom.succ_webpage_updated'));
    }
}
